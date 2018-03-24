<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\ContactFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SupportController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(ContactFormType::Class, null, [
          'action' => $this->generateUrl('handle_form_submission'),
        ]);

        return $this->render('support/index.html.twig', ['our_form' => $form->createView()] );
    }

    /**
    * @param Request $request
    * @Route("/form-submission", name="handle_form_submission")
    * @Method("POST")
    */
    public function handleFormSubmissionACtion(Request $request)
    {

      $form = $this->createForm(ContactFormType::Class);
      $form->handleRequest($request);

      if(! $form->isSubmitted() || ! $form->isValid()){

          return $this->redirectToRoute("homepage");
      }

          $data = $form->getData();
          dump($data);

          $message = \Swift_Message::newInstance()
              ->setSubject('Support Test')
              ->setFrom($data['from'])
              ->setTo('anava@tkinov.com.mx')
              ->setBody($data['message'], 'text/plain')
          ;

          $this->get('mailer')->send($message);

          $this->addFlash('success', 'Your message was send');

          return $this->redirectToRoute('homepage');
     }
}
