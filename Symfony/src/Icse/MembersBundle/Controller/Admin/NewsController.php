<?php

namespace Icse\MembersBundle\Controller\Admin;

use Symfony\Component\Form\FormBuilder;
use Icse\PublicBundle\Entity\NewsArticle;
use Icse\MembersBundle\Form\Type\DocEditorType;

class NewsController extends EntityAdminController
{
    protected function repository()
    {
        return $this->getDoctrine()->getRepository('IcsePublicBundle:NewsArticle');
    }

    protected function getViewName()
    {
        return 'IcseMembersBundle:Admin:news.html.twig';
    }

    protected function newInstance()
    {
        return new NewsArticle();
    }

    protected function getListContent()
    {
        $articles = $this->repository()->findBy([], ['posted_at'=>'desc']);

        $fields = [
            'Date' => function(NewsArticle $x){return $x->getPostedAt()->format('D jS F Y');},
            'Time' => function(NewsArticle $x){return $x->getPostedAt()->format('g:ia');},
            'Headline' => function(NewsArticle $x){return $x->getHeadline();},
            'Author' => function(NewsArticle $x){return $x->getPostedBy() ? $x->getPostedBy()->getFirstName() : '?';},
            'Last updated' => function(NewsArticle $x){return $this->timeagoDate($x->getUpdatedAt()) . " by " .$x->getUpdatedBy()->getFirstName();},
        ];
        return ["fields" => $fields, "entities" => $articles];
    }

    protected function buildForm(FormBuilder $form)
    {
        $form->add('headline', 'text');
        $form->add('subhead', 'text');
        $form->add('picture', 'entity', [
            'class' => 'IcsePublicBundle:Image',
            'choice_label' => 'name',
            'required' => false,
            'attr' => ['class' => 'entity-select']
        ]);
        $form->add('body', new DocEditorType(), [
            'doceditor_style' => 'newsarticle'
        ]);
    }

    /**
     * @param NewsArticle $article
     */
    protected function initialiseCreatedInstance($article)
    {
        $article->setPostedAt(new \DateTime());
        $article->setPostedBy($this->getUser());
    }
}
