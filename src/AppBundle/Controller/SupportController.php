<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SupportController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $form = $this->createFormBuilder()
          ->add( 'from', EmailType::class)
          ->add( 'message', TextareaType::class)
          ->add( 'send', SubmitType::class)
          ->getForm();

          $form->handleRequest($request);

          if($form->isSubmitted() && $form->isValid()){

            $data = $form->getData();
            dump($data);

            $message = \Swift_Message::newInstance()
              ->setSubject('Support Test')
              ->setFrom($data['from'])
              ->setTo('anava@tkinov.com.mx')
              ->setBody($data['message'], 'text/plain')
              ;

            $this->get('mailer')->send($message);

          }

        return $this->render('support/index.html.twig', ['our_form' => $form->createView()] );
    }
}
