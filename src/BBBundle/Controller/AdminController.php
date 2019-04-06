<?php
/**
 * Created by PhpStorm.
 * User: Tamer
 * Date: 20/07/2017
 * Time: 15:12
 */

namespace BBBundle\Controller;

use BBBundle\Entity\Comments;
use BBBundle\Entity\Draw;
use BBBundle\Entity\Picture;
use BBBundle\Form\DrawType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{

    /**
     * @param Comments $comments
     * @return RedirectResponse
     */
    public function deleteComAction(Comments $comments)
    {

        $em = $this->get('doctrine')->getManager();
        $em->remove($comments);
        $em->flush();

        return $this->redirect($this->generateUrl('bb_admin_create'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function publishAction(Request $request)
    {
        $repository = $this->get('doctrine')
            ->getRepository('BBBundle:Draw');
        $draws = $repository->findBy(['category' => '1']);
        $doodles = $repository->findBy(['category' => '2']);

        $em = $this->get('doctrine')->getManager();
        $datas = [];

        $doctrine = $this->get('doctrine')->getRepository('BBBundle:Adress');
        $doctrinePaints = $this->get('doctrine')->getRepository('BBBundle:VisitorPaint');

        $ips = $doctrine->findAll();
        $ipsDistinct = $doctrine->getDistinct();
        $ipsMonth = $doctrine->getOnMonth();
        $ipsWeek = $doctrine->getOnWeek();

        $comments = $this->get('doctrine')->getRepository('BBBundle:Comments')->findCommentsNotAllowed();
        $visitorPaints = $doctrinePaints->currentMonthPaints();

        $form = $this->createFormBuilder($datas, array('csrf_protection' => false,))
            ->add('title', TextType::class, array('label' => 'Nom du dessin', 'attr' => array('class' => 'form-control')))
            ->add('description', TextareaType::class, array('label' => 'Description', 'required' => false, 'attr' => array('class' => 'form-control', 'required' => 'false')))
            ->add('picture', FileType::class,
                array('multiple' => 'true', 'label' => 'Fichiers à upload', 'required' => false))
            ->add('category', EntityType::class, array('class' => 'BBBundle\Entity\Category', 'label' => 'Catégorie', 'choice_label' => 'name',))
            ->add('submit', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //création tableau à partir des infos recupérées du formbuilder
            $datas = $form->getData();

//            nouvelles instances
            $draw = new Draw();

            //definir les valeurs de nos attributs d'instance avec ce qu'on a dans le tableau $datas
            $draw->setTitle($datas['title']);
            $draw->setDescription($datas['description']);
            $draw->setCategory($datas['category']);

            $nb_pic = count($datas['picture']);

            for ($i = 0; $i < $nb_pic; $i++) {
                $picture = new Picture(); // plusieurs images plusieurs instances
                $file = $datas['picture'][$i];
                $filename = $file->getClientOriginalName();
                $file->move($this->getParameter('draws_directory'), $filename);

                $picture->setDPath('uploads/draws/' . $filename);
                $picture->setDraw($draw);
                $em->persist($picture); // à persister plusieurs pour plusieurs images
            }

            //creation fichier dans dossier specifié
            //Entity Manager + preparation données + insertion dans la DB
            $em->persist($draw);
            $em->flush();

            $this->addFlash('success', 'Le nouveau post est dans la boite...');
            return $this->redirect($this->generateUrl('bb_homepage'));
        }

        return $this->render('BBBundle:Admin:create.html.twig', array('ipsWeek' => $ipsWeek, 'ipsMonth' => $ipsMonth, 'ipsDistinct' => $ipsDistinct, 'ips' => $ips, 'comments' => $comments, 'draws' => $draws, 'doodles' => $doodles, 'visitorPaints' => $visitorPaints, 'form' => $form->createView()));
    }

    /**
     * @param Draw $draw
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function editAction(Draw $draw, Request $request)
    {
        $form_edit = $this->createForm(DrawType::class, $draw);
        $form_edit->handleRequest($request);

        if ($form_edit->isSubmitted() && $form_edit->isValid()) {
            $em = $this->get('doctrine')->getManager();
            $em->flush();

            return $this->redirect($this->generateUrl('bb_admin_create'));
        }

        return $this->render('BBBundle:Admin:edit_form.html.twig', array('form_edit' => $form_edit->createView()));
    }

    /**
     * @param Comments $comments
     * @return RedirectResponse
     */
    public function allowCommentAction(Comments $comments)
    {
        $comments->setIsAllowed(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($comments);
        $em->flush();

        return $this->redirect($this->generateUrl('bb_admin_create'));
    }

    /**
     * @param Draw $draw
     * @return RedirectResponse
     */
    public function removeAction(Draw $draw)
    {
        $em = $this->get('doctrine')->getManager();

        $em->remove($draw);
        $em->flush();

        return $this->redirect($this->generateUrl('bb_admin_create'));
    }
}
