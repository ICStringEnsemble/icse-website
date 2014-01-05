<?php

namespace Icse\PublicBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * EventRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class NewsArticleRepository extends EntityRepository
{
    public function listAll()
    {
      return $this->getEntityManager()
                  ->createQuery ('SELECT a 
                                  FROM IcsePublicBundle:NewsArticle a
                                  ORDER BY a.posted_at DESC')
                  ->getResult();
    }

    public function listN($n)
    {
      return $this->getEntityManager()
                  ->createQuery ('SELECT a 
                                  FROM IcsePublicBundle:NewsArticle a
                                  ORDER BY a.posted_at DESC')
                  ->setMaxResults($n) 
                  ->getResult();
    }

    public function homePageMostRecent()
    {
      return $this->getEntityManager()
                  ->createQuery ('SELECT a
                                  FROM IcsePublicBundle:NewsArticle a
                                  WHERE a.updated_at > :time
                                  ORDER BY a.posted_at DESC')
                  ->setMaxResults(1)
                  ->setParameters(array('time' => new \DateTime("4 months ago")))
                  ->getResult();
    }
}
