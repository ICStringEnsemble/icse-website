<?php
namespace Icse\MembersBundle\Service;

use RobertoTru\ToInlineStyleEmailBundle\Converter\ToInlineStyleEmailConverter; 
use Symfony\Component\HttpFoundation\Response; 

class IcseMailer
{
    private $mailer;
    private $style_converter;
    private $root_dir;

    public function __construct(\Swift_Mailer $mailer, ToInlineStyleEmailConverter $css_to_inline_email_converter, $root_dir)
    {
        $this->mailer = $mailer;
        $this->style_converter = $css_to_inline_email_converter;
        $this->root_dir = $root_dir;
    }
 
    public function send($params = array())
    {
        $default_parameters = array(
            'template' => 'IcseMembersBundle:Email:account_created.html.twig',
            'template_params' => array(),
            'subject' => 'An Email from ICSE',
            'from_name' => 'ICSE Website',
            'to' => 'dphoyes@gmail.com',
            'return_body' => 'false',
        );
        $params = array_merge($default_parameters, $params);

        $this->style_converter->setCSS(file_get_contents($this->root_dir . '/../web/bundles/icsemembers/css/email.css')); 
        $this->style_converter->setHTMLByView($params['template'], $params['template_params']); 
        $html_body = $this->style_converter->generateStyledHTML();
        $email = \Swift_Message::newInstance()
                            ->setSubject($params['subject'])
                            ->setFrom(array('icse@imperial.ac.uk' => $params['from_name']))
                            ->setTo($params['to'])
                            ->setBody($html_body) 
                            ->setContentType("text/html") 
                            ;
        $this->mailer->send($email);
     
        if ($params['return_body'] === true) {
            return new Response($html_body); 
        } else {
            return true;
        }
    }
} 
