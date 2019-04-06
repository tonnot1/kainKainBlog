<?php

namespace BBBundle\Controller;

use BBBundle\Configuration\EltPerPage;
use BBBundle\Entity\Adress;
use BBBundle\Entity\Comments;
use BBBundle\Entity\Draw;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @param $page
     * @param Request $request
     * @return Response
     */
    public function indexAction($page, Request $request)
    {
        $max = EltPerPage::Elements;

        /** @var Paginator $dws */
        $draws = $this->get('doctrine')
            ->getRepository('BBBundle:Draw')
            ->findDrawByPost($page, $max);

        $dates = $this->get('doctrine')
            ->getRepository('BBBundle:Draw')
            ->findDates();

        $nbPage = round(ceil(count($draws) / $max));

        $ip = $request->getClientIp();
        $adr = new Adress();
        $adr->setIp($ip);

        $entityManager = $this->get('doctrine')->getManager();
        $entityManager->persist($adr);
        $entityManager->flush();

        $data = ['draws' => $draws,
            'page' => $page,
            'nbPage' => $nbPage,
            'dates' => $dates];

        return $this->render('BBBundle:Default:index.html.twig', $data);
    }

    /**
     * @param $date
     * @return Response
     */
    public function drawsDateAction($date)
    {
        $dByDate = $this->get('doctrine')
            ->getRepository('BBBundle:Draw')
            ->findByDate($date);

        $dates = $this->get('doctrine')
            ->getRepository('BBBundle:Draw')
            ->findDates();

        $data = ['dByDate' => $dByDate,
            'dates' => $dates];

        return $this->render('BBBundle:Default:byDate.html.twig', $data);
    }

    /**
     * @param Draw $draw
     * @param Request $request
     * @return Response
     */
    public function postsingleAction(Draw $draw, Request $request)
    {
        $commentsCount = $this->get('doctrine')
            ->getRepository('BBBundle:Comments')
            ->countCommentsAllowed($draw->getId());

        $data = ['draw' => $draw, 'commentsCount' => $commentsCount];

        $form = $this->createFormBuilder($data)
            ->add('pseudo', TextType::class, array('label' => 'pseudo (obligatoire)', 'attr' => array('class' => 'form-control', 'placeholder' => 'Pseudo...')))
            ->add('email', EmailType::class, array('label' => 'email', 'attr' => array('class' => 'form-control', 'placeholder' => 'Email...'), 'required' => false))
            ->add('url', UrlType::class, array('label' => 'url', 'attr' => array('class' => 'form-control', 'value' => '', 'placeholder' => 'http://...'), 'required' => false))
            ->add('message', TextareaType::class, array('label' => 'message (obligatoire)', 'attr' => array('class' => 'form-control', 'style' => 'height:150px', 'placeholder' => 'Votre commentaire ici...')))
            ->add('submit', SubmitType::class, array('label' => 'Soumettre', 'attr' => array('class' => 'btn btn-default')))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->get('doctrine')->getManager();
            $data = $form->getData();

            $comm = new Comments();
            $comm->setPseudo($data['pseudo']);
            $comm->setEmail($data['email']);
            $comm->setUrl($data['url']);
            $comm->setMessage($data['message']);
            $comm->setDraw($draw);

            $em->persist($comm);
            $em->flush();

            $this->addFlash('info', 'Commentaire envoyé, en attente de validation...');
            return $this->redirect($this->generateUrl('bb_singlepost', ['id' => $draw->getId()]));
        }

        $data = ['draw' => $draw, 'commentsCount' => $commentsCount, 'form_com' => $form->createView()];
        return $this->render('BBBundle:Default:singlepost.html.twig', $data);
    }

    /**
     * @param integer $drawId
     * @return JsonResponse
     */
    public function loadCommentsAction($drawId){

        $comments = $this->get('doctrine')
            ->getRepository('BBBundle:Comments')
            ->findCommentsAllowed($drawId);

        return new JsonResponse($comments);
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function contactAction(Request $request)
    {
        $data = array();
        $form = $this->createFormBuilder()
            ->add('email', EmailType::class, array('label' => 'EMAIL', 'attr' => array('class' => 'form-control', 'style' => 'border-radius:0')))
            ->add('message', TextareaType::class, array('label' => 'MESSAGE', 'attr' => array('class' => 'form-control', 'style' => 'height:250px;border-radius:0')))
            ->add('submit', SubmitType::class, array('label' => 'BANZAI !', 'attr' => array('class' => 'btn btn-danger', 'style' => 'border-radius:0')))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $message = (new Swift_Message('Message privé'))
                ->setFrom($data['email'])
                ->setTo('tonnot1@aol.com')
                ->setBody($this->renderView('@BB/Email/contact_email.html.twig', ['expediteur' => $data['email'], 'message' => $data['message'], 'optional_message' => 'MP de kainkain']), 'text/html');

            $this->get('mailer')->send($message);

            $this->addFlash('info', 'La prise de contact a bien été envoyé...');
            return $this->redirect($this->generateUrl('bb_homepage'));

        }
        $data = array('form' => $form->createView());

        return $this->render('@BB/Default/contact.html.twig', $data);
    }

    /**
     * @return Response
     */
    public function doodleAction()
    {
        /** @var Draw $draw */
        $draw = $this->get('doctrine')->getRepository('BBBundle:Draw')->findBy(['category' => '2']);


        $datas = ['draw' => $draw];
        return $this->render('@BB/Default/doodle.html.twig', $datas);
    }

    /**
     * @return Response
     */
    public function satireAction()
    {
        /** @var Draw $draw */
        $draw = $this->get('doctrine')->getRepository('BBBundle:Draw')->findBy(['category' => '3']);

        $datas = ['draw' => $draw];
        return $this->render('@BB/Default/satire.html.twig', $datas);
    }

    /**
     * @return Response
     */
    public function illustrationAction()
    {
        /** @var Draw $draw */
        $draw = $this->get('doctrine')->getRepository('BBBundle:Draw')->findBy(['category' => '4']);

        $datas = ['draw' => $draw];
        return $this->render('@BB/Default/illustration.html.twig', $datas);
    }

    /**
     * @param Draw $draw
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function getUpPoucesAction(Draw $draw, Request $request)
    {
        $Ip = $request->getClientIp();
        $adress = new Adress;

        $adrs = $draw->getAdress()->getValues();
        $i = 0;
        foreach ($adrs as $adr) {
            if ($Ip == $adr->getIp()) {
                $i++;
            }
        }

        if ($request->isXmlHttpRequest()) {
            if ($i == 0) {
                $adress->setIp($Ip);
                $draw->setPouces($draw->getPouces() + 1);
                $draw->addAdress($adress);
                $em = $this->get('doctrine')->getManager();
                $em->persist($adress);
                $em->persist($draw);
                $em->flush();
            }

            $p = $this->get('doctrine')->getRepository('BBBundle:Draw')->findPouces($draw);
            $data = ['pouce' => $p];
            return new JsonResponse($data);

        }
        return $this->redirect($this->generateUrl('bb_homepage'));
    }

    /**
     * @param Request $request
     * @param Draw $draw
     * @return JsonResponse|RedirectResponse
     */
    public function getPoucesAction(Request $request, Draw $draw)
    {
        if ($request->isXmlHttpRequest()) {
            $p = $this->get('doctrine')->getRepository('BBBundle:Draw')->findPouces($draw);
            $data = ['pou' => $p];
            return new JsonResponse($data);
        }
        return $this->redirect($this->generateUrl('bb_homepage'));
    }
}
