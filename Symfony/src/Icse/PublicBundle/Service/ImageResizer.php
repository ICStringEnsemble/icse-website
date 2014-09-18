<?php
namespace Icse\PublicBundle\Service;

use Icse\PublicBundle\Entity\Image;
use Imagine\Image\Box as ImagineBox;
use Imagine\Image\ImageInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ImageResizer
{
    /**
     * @var \Imagine\Imagick\Imagine
     */
    private $imagine;

    /**
     * @var Filesystem
     */
    private $filesystem;

    private $cache_base_dir;

    const CROPPED = ImageInterface::THUMBNAIL_OUTBOUND;
    const NOT_CROPPED = ImageInterface::THUMBNAIL_INSET;

    private static $size_map =
        [
            'original'         => [],
            'thumb'            => [100, 100],
            'hpslideshow'      => [334, 254, self::CROPPED],
            'committeeprofile' => [155, 200, self::CROPPED],
            'hpmainthumb'      => [200, INF],
            'hpsidethumb'      => [100, INF],
            'hpimagestrip'     => [205, 205],
            'imageadmin'       => [238, INF],
            'article'          => [380, 380],
            'enlarge'          => [1920, 1920],
        ];

    public function __construct($cache_dir, Filesystem $filesystem, $imagine)
    {
        $this->imagine = $imagine;
        $this->cache_base_dir = $cache_dir;
        $this->filesystem = $filesystem;
    }

    private function getResizeSpec($id)
    {
        if ($id == null) return null;

        if (!array_key_exists($id, self::$size_map))
        {
            throw new NotFoundHttpException("Invalid size: $id");
        }

        $spec = self::$size_map[$id];

        if (count($spec) < 2) return null;
        if (count($spec) < 3) $spec[] = self::NOT_CROPPED;

        return $spec;
    }

    private function resizeImageToDimensions ($path, $dest_suffix, $width, $height, $mode)
    {
        $dest_dir = $this->cache_base_dir . '/icse_resized_images';
        $fs = $this->filesystem;
        $fs->mkdir($dest_dir);

        $path_parts = pathinfo($path);
        $dest_path = $dest_dir . '/' . $path_parts['filename'] . '_' . $dest_suffix . '.' .  $path_parts['extension'];

        if (!$fs->exists($dest_path))  // !!!!!!!!!!!
        {
            $image = $this->imagine->open($path);

            if ($mode == self::NOT_CROPPED)
            {
                $orig_size = $image->getSize();
                list ($width, $height) = $this->fitToContainer([$orig_size->getWidth(), $orig_size->getHeight()], [$width, $height]);
            }

            $size = new ImagineBox(ceil($width), ceil($height));

            $image->thumbnail($size, $mode)->save($dest_path);
            $fs->chmod($dest_path, 0660);
        }
        return $dest_path;
    }

    public function resize($path, $size_id)
    {
        $size = $this->getResizeSpec($size_id);

        if (!is_null($size))
        {
            list($width, $height, $mode) = $size;
            $path = $this->resizeImageToDimensions($path, $size_id, $width, $height, $mode);
        }

        return $path;
    }

    private function fitToContainer($inner, $outer)
    {
        list($inner_width, $inner_height) = $inner;
        list($outer_width, $outer_height) = $outer;

        $inner_wideness = $inner_width / $inner_height;
        $outer_wideness = $outer_width / $outer_height;

        if ($inner_wideness > $outer_wideness)
        {
            $width = $outer_width;
            $height = $outer_width / $inner_wideness;
        }
        else
        {
            $height = $outer_height;
            $width = $outer_height * $inner_wideness;
        }

        return [$width, $height];
    }

    public function getSize($img, $size_id=null)
    {
        if ($img instanceof Image)
        {
            $width = $img->getWidth();
            $height = $img->getHeight();
        }
        else
        {
            $path = $img;
            $original = $this->imagine->open($path)->getSize();
            $width = $original->getWidth();
            $height = $original->getHeight();
        }

        $resize_spec = $this->getResizeSpec($size_id);

        if (!is_null($resize_spec))
        {
            list($r_width, $r_height, $mode) = $resize_spec;

            if ($mode == self::CROPPED)
            {
                $width = $r_width;
                $height = $r_height;
            }
            else
            {
                list($width, $height) = $this->fitToContainer([$width, $height], [$r_width, $r_height]);
            }
        }

        return [(int)floor($width), (int)floor($height)];
    }
}