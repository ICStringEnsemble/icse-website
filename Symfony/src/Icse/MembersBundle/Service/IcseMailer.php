<?php
namespace Icse\MembersBundle\Service;

use RobertoTru\ToInlineStyleEmailBundle\Converter\ToInlineStyleEmailConverter; 

class IcseMailer
{
    private $mailer;
    private $style_converter;
    private $root_dir;
    private $template;
    private $email_subject;
    private $from_name;
    private $template_fields;

    public function __construct(\Swift_Mailer $mailer, ToInlineStyleEmailConverter $css_to_inline_email_converter, $root_dir)
    {
        $this->mailer = $mailer;
        $this->style_converter = $css_to_inline_email_converter;
        $this->root_dir = $root_dir;
        $this->template = 'IcseMembersBundle:Email:template.html.twig';
        $this->email_subject = 'An Email from ICSE';
        $this->from_name = 'ICSE Website';
        $this->template_fields = [];
    }

    public function setTemplate($t)
    {
        $this->template = $t;
        return $this;
    }

    public function setBodyFields($f)
    {
        $this->template_fields = $f;
        return $this;
    }

    public function setSubject($s)
    {
        $this->email_subject = $s;
        return $this;
    }

    public function setFromName($n)
    {
        $this->from_name = $n;
        return $this;
    }

    private function generateStyledEmailHtml()
    {
        $this->style_converter->setCSS(file_get_contents($this->root_dir . '/../web/bundles/icsemembers/css/email.css'));
        $this->style_converter->setHTMLByView($this->template, $this->template_fields);
        $html_body = $this->style_converter->generateStyledHTML();
        return $html_body;
    }

    public function preview()
    {
        $html_body = $this->generateStyledEmailHtml();
        $html_body = preg_replace('/ id\s*=\s*["\']?icse_email["\']?[^a-zA-Z\d]/', ' ', $html_body, 1);
        return $html_body;
    }
 
    public function send($to_addresses, $to_name=null, &$failures=null)
    {
        $html_body = $this->generateStyledEmailHtml();
        $email = \Swift_Message::newInstance()
                            ->setSubject($this->email_subject)
                            ->setFrom(['icse@imperial.ac.uk' => $this->from_name])
                            ->setTo($to_addresses, $to_name);
        $email->setBody($html_body);
        $email->setContentType("text/html");
        $n_successful = $this->mailer->send($email, $failures);
        $n_attempted = is_array($to_addresses) ? count($to_addresses) : 1;
        $n_fails = $n_attempted - $n_successful;
        return $n_fails;
    }
} 
