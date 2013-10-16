<?php

namespace Icse\MembersBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\HttpKernel\Exception\HttpException; 
use Icse\MembersBundle\Form\Type\FileInfoType;

use RobertoTru\ToInlineStyleEmailBundle\Converter\ToInlineStyleEmailConverter; 

class EmailController extends Controller
{
    public function mainAction($arg)
    {
        if ($arg == 'preview')
        {

            $style_converter = $this->get('css_to_inline_email_converter');

            $style_converter->setCSS(file_get_contents($this->container->getParameter('kernel.root_dir') . '/../web/bundles/icsemembers/css/email.css')); 

            $style_converter->setHTMLByView('IcseMembersBundle:Email:dummy.html.twig'); 
            $html_body = $style_converter->generateStyledHTML();
            
            return new Response($html_body);
        }



        return $this->render('IcseMembersBundle:Admin:email.html.twig');

    }
}
