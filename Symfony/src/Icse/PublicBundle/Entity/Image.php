<?php

namespace Icse\PublicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Icse\PublicBundle\Entity\Traits\FileWrapper;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Icse\PublicBundle\Entity\Interfaces\ResourceInterface;
use Common\Tools;


/**
 * Icse\PublicBundle\Entity\Image
 */
class Image implements ResourceInterface
{
    /**
     * @Serializer\Exclude
     */
    static private $image_dir = 'Symfony/uploads/images/';

    use FileWrapper;

    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $category
     */
    private $category;

    /**
     * @var string
     */
    private $file_extension;

    /**
     * @var integer
     */
    private $width;

    /**
     * @var integer
     */
    private $height;

    /**
     * @var string
     */
    private $legacy_name;

    /**
     * @var \DateTime $updated_at
     */
    private $updated_at;

    /**
     * @var \Icse\MembersBundle\Entity\Member $updated_by
     */
    private $updated_by;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Image
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set category
     *
     * @param string $category
     * @return Image
     */
    public function setCategory($category)
    {
        $this->category = $category;
    
        return $this;
    }

    /**
     * Get category
     *
     * @return string 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set updated_at
     *
     * @param \DateTime $updatedAt
     * @return Image
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;
    
        return $this;
    }

    /**
     * Get updated_at
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    public function setNameFromFile(UploadedFile $file)
    {
        $name = $file->getClientOriginalName();
        $name = pathinfo($name, PATHINFO_FILENAME);
        $name = preg_replace('/_/', ' ', $name);
        $this->setName($name);
    }

    public function setFileExtensionFromFile(UploadedFile $file)
    {
        $ext = $file->guessExtension();
        $orig_ext = $file->getClientOriginalExtension();
        if ($orig_ext === 'jpg' && $ext == 'jpeg') $ext = 'jpg';
        $this->setFileExtension($ext);
    }

    public function setSizeFromFile(UploadedFile $file)
    {
        list($width, $height) = getimagesize($file->getPathname());
        $this->setWidth($width);
        $this->setHeight($height);
    }

    public function getFileFromForm()
    {
        $this->getUploadedFile();
    }

    public function setFileFromForm(UploadedFile $file)
    {
        $this->setUploadedFile($file);
        $this->setNameFromFile($file);
        $this->setFileExtensionFromFile($file);
        $this->setSizeFromFile($file);
    }

    /** @Serializer\Accessor(getter="getResourceType") */
    private static $resource_type;
    public function getResourceType()
    {
        return "images";
    }

    /** @Serializer\Accessor(getter="getUrlResourcePath") */
    private static $url_resource_path;
    public function getUrlResourcePath()
    {
        $path = $this->getId();
        $base_name = preg_replace('/\.'.$this->getFileExtension()."$/", "", $this->getName());
        if ($base_name) $path .= '/' . Tools::slugify($base_name);
        $path .= '.' . $this->getFileExtension();
        return $path;
    }

    protected function getFileDirectory()
    {
        return self::$image_dir;
    }

    protected function getFileName()
    {
        return $this->getId() . '.' . $this->getFileExtension();
    }

    public function getDownloadName()
    {
        $base_name = preg_replace('/\.'.$this->getFileExtension()."$/", "", $this->getName());
        return ($base_name ? $base_name : $this->getId()) . '.' . $this->getFileExtension();
    }

    /**
     * Set file_extension
     *
     * @param string $fileExtension
     * @return Image
     */
    public function setFileExtension($fileExtension)
    {
        $this->file_extension = $fileExtension;

        return $this;
    }

    /**
     * Get file_extension
     *
     * @return string 
     */
    public function getFileExtension()
    {
        return $this->file_extension;
    }

    /**
     * Set legacy_name
     *
     * @param string $legacyName
     * @return Image
     */
    public function setLegacyName($legacyName)
    {
        $this->legacy_name = $legacyName;

        return $this;
    }

    /**
     * Get legacy_name
     *
     * @return string 
     */
    public function getLegacyName()
    {
        return $this->legacy_name;
    }

    /**
     * Set updated_by
     *
     * @param \Icse\MembersBundle\Entity\Member $updatedBy
     * @return Image
     */
    public function setUpdatedBy(\Icse\MembersBundle\Entity\Member $updatedBy)
    {
        $this->updated_by = $updatedBy;

        return $this;
    }

    /**
     * Get updated_by
     *
     * @return \Icse\MembersBundle\Entity\Member 
     */
    public function getUpdatedBy()
    {
        return $this->updated_by;
    }

    /**
     * Set width
     *
     * @param integer $width
     * @return Image
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get width
     *
     * @return integer 
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set height
     *
     * @param integer $height
     * @return Image
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return integer 
     */
    public function getHeight()
    {
        return $this->height;
    }
}
