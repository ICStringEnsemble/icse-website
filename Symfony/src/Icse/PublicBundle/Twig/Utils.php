<?php

namespace Icse\PublicBundle\Twig;

use Icse\PublicBundle\Service\ImageResizer;
use Icse\PublicBundle\Entity\Image;
use Symfony\Component\Routing\Router;

class Utils extends \Twig_Extension
{
    /**
     * @var ImageResizer
     */
    private $image_resizer;

    /**
     * @var Router
     */
    private $router;

    public function __construct($image_resizer, $router)
    {
        $this->image_resizer = $image_resizer;
        $this->router = $router;
    }

    public function getFilters()
    {
        return [
            'imgWidthAndHeight' => new \Twig_Filter_Method($this, 'imgWidthAndHeight'),
            'imgSrc' => new \Twig_Filter_Method($this, 'imgSrc'),
            'imgSrcAndSize' => new \Twig_Filter_Method($this, 'imgSrcAndSize'),
            'lazyImgSrcAndSize' => new \Twig_Filter_Method($this, 'lazyImgSrcAndSize'),
        ];
    }

    public function imgWidthAndHeight(Image $img, $size_id=null)
    {
        list($width, $height) = $this->image_resizer->getSize($img, $size_id);
        return "width=$width height=$height";
    }

    public function imgSrc(Image $img, $size_id=null, $lazy=false)
    {
        $params = ['object' => $img];
        if (!is_null($size_id)) $params = array_merge($params, ["size" => $size_id]);
        $path = $this->router->generate('IcsePublicBundle_resource', $params);
        return $lazy ? "data-original=$path" : "src=$path";
    }

    public function imgSrcAndSize(Image $img, $size_id=null, $lazy=false)
    {
        return $this->imgWidthAndHeight($img, $size_id) . " " . $this->imgSrc($img, $size_id, $lazy);
    }

    public function lazyImgSrcAndSize(Image $img, $size_id=null)
    {
        return $this->imgSrcAndSize($img, $size_id, true);
    }

    public function getName()
    {
        return 'icse.twig.utils';
    }
} 
