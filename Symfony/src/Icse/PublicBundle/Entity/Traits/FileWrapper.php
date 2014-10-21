<?php

namespace Icse\PublicBundle\Entity\Traits;

use Symfony\Component\HttpFoundation\File\UploadedFile;


trait FileWrapper
{
    abstract protected function getFileDirectory();
    abstract protected function getFileName();

    private $uploaded_file;
    private $file_to_remove;

    /**
     * @param UploadedFile $file
     */
    public function setUploadedFile(UploadedFile $file = null)
    {
        $this->uploaded_file = $file;
    }

    /**
     * @return UploadedFile
     */
    public function getUploadedFile()
    {
        return $this->uploaded_file;
    }

    public function getFilePath()
    {
        return $this->getFileDirectory().'/'.$this->getFileName();
    }

    public function _upload()
    {
        $this->getUploadedFile()->move($this->getFileDirectory(), $this->getFileName());
        $this->setUploadedFile(null);
    }

    public function _storeFilenameForRemove()
    {
        $this->file_to_remove = $this->getFilePath();
    }

    public function _removeUpload()
    {
        unlink($this->file_to_remove);
    }
}