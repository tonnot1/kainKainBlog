<?php
/**
 * Created by PhpStorm.
 * User: tameryigit
 * Date: 22/12/2018
 * Time: 10:43
 */

namespace BBBundle\Controller;

use BBBundle\Entity\VisitorPaint;
use BBBundle\Entity\Winner;
use BBBundle\Form\WinnerType;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ConcoursController extends Controller
{
    /**
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('@BB/Default/painterGift.html.twig');
    }

    /**
     * @param Request $request
     *
     * @return bool|Response
     */
    public function downloadPaintAction(Request $request)
    {
        $em = $this->get('doctrine')->getManager();

        // IMAGE
        $data = $request->request->get('data');
        $email = $request->request->get('email');
        list($type, $data1) = explode(';', $data);
        list($type, $data2) = explode(',', $data1);

        $dataNew = base64_decode($data2);
        $file = __DIR__ . '/../Resources/public/img/paints/paint' . $email . '.png';
        $fileName = 'paint' . $email . '.png';
        $stringContent = getimagesizefromstring($dataNew);
        if ($stringContent['mime'] !== 'image/png') {
            return false;
        }
        file_put_contents($file, $dataNew);
        $webDirectory = $this->getParameter('paints_directory') . '/' . $fileName;
        copy($file, $webDirectory);

        $paint = new VisitorPaint();
        $paint->setEmail($email);
        $paint->setPath('uploads/paints/' . $fileName);
        $paint->setClientIp($request->getClientIp());
        $em->persist($paint);
        $em->flush();

        $response = new Response($file);

        // Set headers
        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-type', mime_content_type($file));
        $response->headers->set('Content-Disposition', 'attachment; filename="' . basename($fileName) . '";');
        $response->headers->set('Content-length', filesize($file));

        $response->setContent(file_get_contents($file));

        return $response;
    }

    /**
     * @param $email
     * @return RedirectResponse
     */
    public function winnerMailAction($email)
    {
        $em = $this->get('doctrine')->getManager();
        $codeGagnant = uniqid('Lot');

        $winner = new Winner();
        $winner->setEmail($email);
        $winner->setCodeWin($codeGagnant);

        $em->persist($winner);
        $em->flush();

        $winPersisted = $this->getDoctrine()->getRepository('BBBundle:Winner')->findOneBy(['codeWin' => $codeGagnant]);

        $validationWinnerUrl = 'bb_validate_winner';

        $messageOpt = 'Meilleur réalisation';

        $message = (new Swift_Message('Kainkain'))
            ->setFrom('tamouch@hotmail.com')
            ->setTo($email)
            ->setBody($this->renderView('@BB/Email/winner_email.html.twig', ['expediteur' => 'tamouch@hotmail.com', 'winUrl' => $validationWinnerUrl, 'winId' => $winPersisted->getId(), 'code' => $codeGagnant, 'optional_message' => $messageOpt]), 'text/html');

        $this->get('mailer')->send($message);

        $this->addFlash('info', 'Le gagnant a bien été choisi');
        return $this->redirect($this->generateUrl('bb_homepage'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function winnerValidationAction(Request $request, $id)
    {
        $em = $this->get('doctrine')->getManager();
        $doctrine = $this->getDoctrine()->getRepository('BBBundle:Winner');
        $winner = $doctrine->findOneBy(['id' => $id]);
        $codeWin = $winner->getCodeWin();
        $form = $this->createForm(WinnerType::class, $winner);

        $form->handleRequest($request);
        $formData = $form->getData();

        if ($form->isSubmitted() && $form->isValid() && $formData->getCodeGagnant() === $codeWin) {

            $winner->setNom($formData->getNom());
            $winner->setPrenom($formData->getPrenom());
            $winner->setEmail($formData->getEmail());
            $winner->setAdresse($formData->getAdresse());
            $winner->setCodeGagnant($formData->getCodeGagnant());
            $em->flush();

            $this->addFlash('info', 'Le formulaire a bien été envoyé');
            return $this->redirect($this->generateUrl('bb_homepage'));
        } else {
            $this->addFlash('danger', 'Problème de formulaire... Vérifiez que tous les champs sont remplis ou que le code gagnant soit bon.');
        }

        $data = ['form' => $form->createView()];

        return $this->render('@BB/Winner/validation.html.twig', $data);
    }
}
