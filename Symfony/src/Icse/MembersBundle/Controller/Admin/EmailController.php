<?php

namespace Icse\MembersBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\Response; 


class EmailController extends Controller
{
    public function mainAction(Request $request, $arg)
    {
        if ($arg == 'preview')
        {
            $style_converter = $this->get('css_to_inline_email_converter');
            $style_converter->setCSS(file_get_contents($this->container->getParameter('kernel.root_dir') . '/../web/bundles/icsemembers/css/email.css')); 


            if ($request->request->has('body'))
            {
                $style_converter->setHTMLByView('IcseMembersBundle:Email:template.html.twig', ['content' => $request->request->get('body')]);
            }
            else
            {
                $style_converter->setHTMLByView('IcseMembersBundle:Email:dummy.html.twig'); 
            }

            $html_body = $style_converter->generateStyledHTML();
            $html_body = preg_replace('/ id\s*=\s*["\']?icse_email["\']?[^a-zA-Z\d]/', ' ', $html_body, 1);

            return new Response($html_body);
        }

        $draft_body = '';

        if ($draft_body)
        {
            $email_params = ['email_body' => $draft_body];
        }
        else
        {
            $upcoming_rehearsals = $this->getDoctrine()->getRepository('IcseMembersBundle:Rehearsal')
                                                       ->findNextN(2);
            $email_params = ['upcoming_rehearsals' => $upcoming_rehearsals];
        }

        return $this->render('IcseMembersBundle:Admin:email.html.twig', $email_params);

    }
}
