<?php

namespace Icse\MembersBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\HttpKernel\Exception\HttpException; 
use Icse\MembersBundle\Form\Type\FileInfoType;


class EmailController extends Controller
{
    public function mainAction()
    {
        return $this->render('IcseMembersBundle:Admin:email.html.twig', []);

    }
}
