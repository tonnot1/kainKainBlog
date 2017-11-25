<?php

namespace BBBundle\Controller;

use BBBundle\BBBundle;
use BBBundle\Entity\Category;
use BBBundle\Entity\Comments;
use BBBundle\Entity\Draw;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $draws = $this->getDoctrine('doctrine')
            ->getRepository('BBBundle:Draw')
//                      ->findDrawByPost();
            ->findBy(['category' => '1']);
//                      ->findAll();


        //----------------------prise en charge commentaire

        $data = [];
        $form = $this->createFormBuilder($data)
            ->add('pseudo', TextType::class, array('attr' => array('class' => 'form-control', 'placeholder' => 'Pseudo...')))
            ->add('email', EmailType::class, array('attr' => array('class' => 'form-control', 'placeholder' => 'Email...')))
            ->add('url', TextType::class, array('attr' => array('class' => 'form-control', 'placeholder' => 'Url...')))
            ->add('message', TextareaType::class, array('attr' => array('class' => 'form-control', 'placeholder' => 'Votre commentaire ici...')))
            ->add('submit', SubmitType::class)
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
            $comm->setDraw($draws);

            $em->persist($comm);
            $em->flush();

            return $this->redirect($this->generateUrl('bb_homepage'));
        }

        $data = ['draws' => $draws, 'form' => $form->createView()];


        return $this->render('BBBundle:Default:index.html.twig', $data);
    }

    /**
     * @param Draw $draw
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function postsingleAction(Draw $draw, Request $request)
    {

        $comments = $this->getDoctrine('doctrine')
            ->getRepository('BBBundle:Comments')
            ->findCommentsAllowed($draw->getId());
//                         ->findBy(['draw'=>$draw->getId()]);
//                         ->findBy(['isAllowed'=>'1']);
//        var_dump($comments);
//        die();

        $data = ['draw' => $draw, 'comments' => $comments];

        $form = $this->createFormBuilder($data)
            ->add('pseudo', TextType::class, array('attr' => array('class' => 'form-control', 'placeholder' => 'Pseudo...')))
            ->add('email', EmailType::class, array('attr' => array('class' => 'form-control', 'placeholder' => 'Email...'), 'required' => false))
            ->add('url', UrlType::class, array('attr' => array('class' => 'form-control', 'value' => '', 'placeholder' => 'http://...'), 'required' => false))
            ->add('message', TextareaType::class, array('attr' => array('class' => 'form-control', 'placeholder' => 'Votre commentaire ici...')))
            ->add('submit', SubmitType::class, array('attr' => array('class' => 'btn btn-default')))
            ->getForm();

        $form->handleRequest($request);

//        $data = ['draw'=>$draw,'form_com'=>$form->createView()];
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


            return $this->redirect($this->generateUrl('bb_singlepost', ['id' => $draw->getId()]));

        }


        $data = ['draw' => $draw, 'comments' => $comments, 'form_com' => $form->createView()];
//        return $this->render('BBBundle:Default:singlepost.html.twig', $data);
        return $this->render('BBBundle:Default:singlepost.html.twig', $data);
    }

    public function doodleAction()
    {


//       $post = $this->getDoctrine('doctrine')->getRepository('BBBundle:Draw')
//            ->findAll();

        $draw = $this->getDoctrine('doctrine')->getRepository('BBBundle:Draw')->findBy(['category' => '2']);


        $datas = ['draw' => $draw];
        return $this->render('@BB/Default/doodle.html.twig', $datas);
    }

    public function singleDoodleAction()
    {
        return $this->render('BBBundle:Default:single_doodle.html.twig');
    }

    public function getUpPoucesAction(Draw $draw, Request $request)
    {

        if ($request->isXmlHttpRequest()) {
//            var_dump($request);
            $draw->setPouces($draw->getPouces() + 1);
            $em = $this->get('doctrine')->getManager();
            $em->persist($draw);
            $em->flush();

            $p = $this->get('doctrine')->getRepository('BBBundle:Draw')->findPouces($draw);
            $data = ['pouce' => $p];
            return new JsonResponse($data);

        }
        return $this->redirect($this->generateUrl('bb_homepage'));

    }

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
