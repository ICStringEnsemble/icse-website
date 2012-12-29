<?php

namespace Icse\PublicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Icse\PublicBundle\Entity\NewsArticle;

class NewsController extends Controller
{
    public function indexAction()
    {
        $dm = $this->getDoctrine(); 
        $articles = $dm->getRepository('IcsePublicBundle:NewsArticle')
                     ->listAll(); 
        return $this->render('IcsePublicBundle:News:index.html.twig', array('articles' => $articles));
    }

    public function articleAction($id, $slug)
    {
        $article = $this->getDoctrine()
                ->getRepository('IcsePublicBundle:NewsArticle')
                ->findOneById($id);

        if ($slug !== $article->getSlug()) {
            return $this->redirect($this->generateUrl('IcsePublicBundle_news_article', $article), 301); 
        }


        return $this->render('IcsePublicBundle:News:article.html.twig', array('article' => $article));
    }
}
