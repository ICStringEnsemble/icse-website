<?php

namespace Icse\MembersBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response; 


class EmailController extends Controller
{
    private $send_to_map = [
        'members' => 'icse-talk@imperial.ac.uk',
        'public' => 'icu-stringensemble-public@imperial.ac.uk',
        'none' => null
    ];

    public function routerAction(Request $request, $arg)
    {
        if ($arg == null)
        {
            return $this->indexAction($request);
        }
        elseif ($arg == 'preview')
        {
            return $this->previewAction($request);
        }
        elseif ($arg == 'send')
        {
            return $this->sendAction($request);
        }
        throw $this->createNotFoundException();
    }

    public function indexAction(Request $request)
    {
        $upcoming_rehearsals = $this->getDoctrine()->getRepository('IcseMembersBundle:Rehearsal')->findNextN(2);
        $email_params = [
            'upcoming_rehearsals' => $upcoming_rehearsals,
            'mailinglist' => 'all',
        ];

        $draft_body = '';
        if ($draft_body)
        {
            $email_params['email_body'] = $draft_body;
        }

        return $this->render('IcseMembersBundle:Admin:email.html.twig', $email_params);
    }

    private function getEmailForm()
    {
        $form = $this->get('form.factory')
            ->createNamedBuilder(null, 'form', null, array('csrf_protection' => false))
            ->add('body', 'text')
            ->add('email_subject', 'text')
            ->add('mailing_list', 'choice', array(
                'choices'   => ['members'=>'', 'public'=>'', 'none'=>'']
            ))
            ->add('send_to_option', 'choice', array(
                'choices'   => ['mailing_list'=>'', 'other'=>'']
            ))
            ->add('send_to_address', 'text')
            ->getForm();
        return $form;
    }

    private function formToMailer(Form $form, &$to_addresses=null)
    {
        $data = $form->getData();

        $mailer = $this->get('icse.mailer');
        $mailer->setBodyFields(['content' => $data['body'], 'mailinglist' => $data['mailing_list']]);
        $mailer->setSubject($data['email_subject']);
        $mailer->setFromName('ICSE');

        if ($data['send_to_option'] == 'mailing_list')
        {
            $to_addresses = $this->send_to_map[$data['mailing_list']];
        }
        else
        {
            $to_addresses = explode(',', $data['send_to_address']);
        }

        return $mailer;
    }

    public function previewAction(Request $request)
    {
        $form = $this->getEmailForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $mailer = $this->formToMailer($form);
            return new Response($mailer->preview());
        }
        else
        {
            return $this->get('ajax_response_gen')->returnFail($form);
        }
    }

    public function sendAction(Request $request)
    {
        $form = $this->getEmailForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $recipients = [];
            $mailer = $this->formToMailer($form, $recipients);
            $failures = null;
            $ret = $mailer->send($recipients, $failures);
            var_dump($ret);
            var_dump($failures);
            die();
            return new Response($ret);
        }
        else
        {
            return $this->get('ajax_response_gen')->returnFail($form);
        }
    }

}
