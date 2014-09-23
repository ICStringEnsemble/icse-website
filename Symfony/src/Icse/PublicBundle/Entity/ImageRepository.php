<?php

namespace Icse\PublicBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ImageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ImageRepository extends EntityRepository
{
    public function findSlideshowImages()
    {
        return $this->findBy(['in_slideshow' => true]);
    }
}
