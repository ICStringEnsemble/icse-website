<?php

namespace Icse\PublicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Icse\PublicBundle\Entity\Image
 */
class Image
{
    private $tmp_dir = 'Symfony/uploads/tmp/';
    private $image_dir = 'Symfony/uploads/images/';

    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $file
     */
    private $file;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $category
     */
    private $category;

    /**
     * @var \DateTime $updated_at
     */
    private $updated_at;

    /**
     * @var integer $updated_by
     */
    private $updated_by;

    private $original_tmp_file;


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
     * Set file
     *
     * @param string $file
     * @return Image
     */
    public function setFile($file)
    {
        $this->file = $file;
    
        return $this;
    }

    /**
     * Get file
     *
     * @return string 
     */
    public function getFile()
    {
        return $this->file;
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

    /**
     * Set updated_by
     *
     * @param integer $updatedBy
     * @return Image
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updated_by = $updatedBy;
    
        return $this;
    }

    /**
     * Get updated_by
     *
     * @return integer 
     */
    public function getUpdatedBy()
    {
        return $this->updated_by;
    }

    public function sanitiseFilename()
    {
        $this->setFile(basename($this->getFile()));
        if (strlen($this->getFile()) < 5 || !file_exists($this->tmp_dir.$this->getFile()))
            throw $this->createNotFoundException('File does not exist'); 

        $this->original_tmp_file = $this->getFile();
        $new_filename = $this->getFile();
        $extension = pathinfo($new_filename)['extension'];
        while (file_exists($this->image_dir.$new_filename))
          {
            $new_filename = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36).'.'.$extension;
          }
        $this->setFile($new_filename);
    }

    public function storeFile()
    {
        if(rename($this->tmp_dir.$this->original_tmp_file, $this->image_dir.$this->getFile()))
          {
            return;
          }
        else
          {
            throw new \Exception('Could not relocate file');
          }
    }
}
