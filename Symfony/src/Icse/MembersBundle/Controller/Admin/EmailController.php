<?php

namespace Icse\MembersBundle\Controller\Admin;

use Icse\MembersBundle\Entity\SentNewsletter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response; 


class EmailController extends Controller
{
    private static $SEND_TO_MAP = [
        'members' => 'icse-talk@imperial.ac.uk',
        'public' => 'icu-stringensemble-public@imperial.ac.uk',
        'none' => null
    ];

    private static $NEWSLETTER_TYPE_MAP = [
        'members' => SentNewsletter::TYPE_MEMBERS,
        'public' => SentNewsletter::TYPE_PUBLIC,
        'none' => SentNewsletter::TYPE_OTHER,
    ];

    private static $NEWSLETTER_DEST_MAP = [
        'mailing_list' => SentNewsletter::DEST_MAILINGLIST,
        'other' => SentNewsletter::DEST_OTHER,
    ];

    public function routerAction(Request $request, $arg)
    {
        if ($arg == null)
        {
            return $this->indexAction();
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

    public function indexAction()
    {
        $upcoming_rehearsals = $this->getDoctrine()->getRepository('IcseMembersBundle:Rehearsal')->findNextN(2);
        $email_params = [
            'upcoming_rehearsals' => $upcoming_rehearsals,
            'mailinglist' => 'all',
            'form' => $this->getEmailForm()->createView()
        ];

        return $this->render('IcseMembersBundle:Admin:email.html.twig', $email_params);
    }

    private function getEmailForm($method='POST')
    {
        $form = $this->createFormBuilder()
            ->setMethod($method)
            ->add('body', 'hidden')
            ->add('email_subject', 'text', [
                'data' => 'ICSE Weekly Update',
            ])
            ->add('mailing_list', 'choice', [
                'choices'   => ['members'=>'Members', 'public'=>'Public', 'none'=>'Neither'],
                'expanded' => true,
                'data' => 'members',
            ])
            ->add('send_to_option', 'choice', [
                'choices'   => ['mailing_list'=>'Straight to the mailing list', 'other'=>'Just to: '],
                'expanded' => true,
                'data' => 'mailing_list',
            ])
            ->add('send_to_address', 'hidden', [
                'data' => 'icse@imperial.ac.uk',
            ])
            ->getForm();
        return $form;
    }

    private function mailerFromData($data, &$to_addresses=null)
    {
        $mailer = $this->get('icse.mailer');
        $mailer->setBodyFields(['content' => $data['body'], 'mailinglist' => $data['mailing_list']]);
        $mailer->setSubject($data['email_subject']);
        $mailer->setFromName('ICSE');

        if ($data['send_to_option'] == 'mailing_list')
        {
            $to_addresses = self::$SEND_TO_MAP[$data['mailing_list']];
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
            $mailer = $this->mailerFromData($form->getData());
            return new Response($mailer->preview());
        }
        else
        {
            return $this->get('ajax_response_gen')->returnFail($form);
        }
    }

    public function archiveNewletter($data)
    {
        $newsletter = new SentNewsletter();
        $newsletter->setSubject($data['email_subject']);
        $newsletter->setBody($data['body']);
        $newsletter->setSentAt(new \DateTime);
        $newsletter->setSentBy($this->getUser());
        $newsletter->setType(self::$NEWSLETTER_TYPE_MAP[$data['mailing_list']]);
        $newsletter->setDest(self::$NEWSLETTER_DEST_MAP[$data['send_to_option']]);

        $em = $this->getDoctrine()->getManager();
        $em->persist($newsletter);
        $em->flush();
    }

    public function sendAction(Request $request)
    {
        $form = $this->getEmailForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            $form_data = $form->getData();
            $this->archiveNewletter($form_data);
            $recipients = [];
            $mailer = $this->mailerFromData($form_data, $recipients);
            $fails = $mailer->send($recipients);
            if ($fails == 0)
            {
                return $this->get('ajax_response_gen')->returnSuccess();
            }
            else
            {
                return $this->get('ajax_response_gen')->returnFail($fails." failed");
            }
        }
        else
        {
            return $this->get('ajax_response_gen')->returnFail($form);
        }
    }

}
