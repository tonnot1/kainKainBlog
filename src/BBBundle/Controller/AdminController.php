<?php
/**
 * Created by PhpStorm.
 * User: Tamer
 * Date: 20/07/2017
 * Time: 15:12
 */

namespace BBBundle\Controller;

use BBBundle\BBBundle;
use BBBundle\Entity\Ad;
use BBBundle\Entity\Comments;
use BBBundle\Entity\Draw;
use BBBundle\Entity\Picture;
use BBBundle\Form\DrawType;
use BBBundle\Form\PictureType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\FormBuilderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AdminController extends Controller
{

    /**
     * @Template()
     */
    public function indexAdminAction(Request $request)
    {
            $ad = $this->getDoctrine('doctrine')->getRepository('BBBundle:Ad')
//                ->findAll();
                ->findOneBy([]);


//            $found = $ad->find(['adOne'=>'s@dwomek9ovdicvudmos']);
//            $found = $ad->findOneBy([]);

//        var_dump($found);
//        die();
//        $data=['ad'=>$ad];
        $datas=[];



        $form = $this->createFormBuilder($datas)
//                ->getFormFactory()->createNamedBuilder("")
                ->setAction($this->generateUrl('bb_admin_login_check'))
                ->setMethod('POST')

                ->add('adOne', TextType::class,array('label'=>'Premier pass','attr'=>array('class'=>'form-control', "name"=>"_adOne","id"=>"adOne")))
                 ->add('adTwo', PasswordType::class, array('label'=>'Deuxième pass', 'attr'=>array('class'=>'form-control', "name"=>"_adTwo", "id"=>"adTwo")))
                 ->add('submit',SubmitType::class)
                 ->getForm();



        $form->handleRequest($request);

//            if($form->isValid()){
//                $datas = $form->getData();
//                if(($datas['adOne'] !== $ad->getAdOne()) && ($datas['adTwo'] !== $ad->getAdTwo()) ){
//
//                    return $this->redirect($this->generateUrl('bb_homepage'));
//                }
//                else {
//                    return $this->redirect($this->generateUrl('bb_admin_create'));
//                }
//            }
        $helper = $this->get('security.authentication_utils');

        /*return $this->render('security/login.html.twig', array(
            // last username entered by the user (if any)
            'last_username' => $helper->getLastUsername(),
            // last authentication error (if any)
            'error' => $helper->getLastAuthenticationError(),
        ));*/

        /*$request = $this->getRequest();
        $session = $request->getSession();

        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }*/

        /*$params = array(

        );*/

//        return $this->render('BBBundle:Admin:create.html.twig',$params);

//        public function getName(){return null}

            $datas = ["form"=>$form->createView(),
                'last_username' => $helper->getLastUsername(),
                'error' => $helper->getLastAuthenticationError(),];

        return $this->render('BBBundle:Admin:indexAdmin.html.twig',$datas);
        //return $this->render('BBBundle:Admin:indexAdmin.html.twig');
        }
    
//    public function validAction(){
//
//    }

    public function deleteComAction(Comments $comments){

        $em = $this->get('doctrine')->getManager();
        $em->remove($comments);
        $em->flush();

        return $this->redirect($this->generateUrl('bb_admin_create'));
    }

    /**
     * @Route("/admin/create", name="bb_admin_create")
     * @Method("GET|POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function publishAction(Request $request)
    {


        $em = $this->get('doctrine')->getManager();
        $datas=[];

        $comments = $this->getDoctrine('doctrine')->getRepository('BBBundle:Comments')->findCommentsNotAllowed();
//            ->findAll();



        $form = $this->createFormBuilder($datas, array('csrf_protection' => false,))

            ->add('title', TextType::class, array('label'=>'Nom du dessin','attr'=>array('class'=>'form-control')))
            ->add('description', TextareaType::class, array('label'=>'Description', 'required'=>false,'attr'=>array('class'=>'form-control', 'required'=>'false')))
            ->add('picture', FileType::class,
                array('multiple'=>'true', 'label'=>'Fichiers à upload','required'=>false))
            ->add('category', EntityType::class, array('class'=>'BBBundle\Entity\Category','label'=>'Catégorie','choice_label'=>'name',))
            ->add('submit', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if($form->isValid()){
            //création tableau à partir des infos recupérées du formbuilder
            $datas = $form->getData();



//            nouvelles instances
            $draw = new Draw();




            //definir les valeurs de nos attributs d'instance avec ce qu'on a dans le tableau $datas
            $draw->setTitle($datas['title']);
            $draw->setDescription($datas['description']);
            //$draw->setPicture($datas['picture']);

            $draw->setCategory($datas['category']);



            $nb_pic = count($datas['picture']);

            for($i = 0; $i<$nb_pic; $i++){
                $picture = new Picture(); // plusieurs images plusieurs instances
                $file = $datas['picture'][$i];
                $filename = $file->getClientOriginalName();
                $file->move($this->getParameter('draws_directory'), $filename);

                $picture->setDPath('uploads/draws/'.$filename);
                $picture->setDraw($draw);
                $em->persist($picture); // à persister plusieurs pour plusieurs images
            }

                //creation fichier dans dossier specifié


            //Entity Manager + preparation données + insertion dans la DB
//            $em = $this->getDoctrine()->getManager();
            $em->persist($draw);
            $em->flush();


            $this->addFlash('success','Le post est dans la boite');
            return $this->redirect($this->generateUrl('bb_homepage'));
        }









        return $this->render('BBBundle:Admin:create.html.twig', array('comments'=>$comments,'form'=>$form->createView()));
    }

    public function allowCommentAction(Comments $comments){
        $comments->setIsAllowed(true);
        $em = $this->getDoctrine('doctrine')->getManager();
        $em->persist($comments);
        $em->flush();
        return $this->redirect($this->generateUrl('bb_admin_create'));

    }


}

