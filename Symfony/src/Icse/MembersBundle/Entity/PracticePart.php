<?php

namespace Icse\MembersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Common\Tools;

/**
 * PracticePart
 */
class PracticePart
{
    /**
     * @Exclude
     */
    private static $base_dir = 'Symfony/uploads/practiceparts/';

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $instrument;

    /**
     * @var integer
     */
    private $sort_index;

    /**
     * @var \Icse\PublicBundle\Entity\PieceOfMusic
     * @Groups({"piece"})
     */
    private $piece;

    private $file;

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
     * Set instrument
     *
     * @param string $instrument
     * @return PracticePart
     */
    public function setInstrument($instrument)
    {
        $this->instrument = $instrument;

        return $this;
    }

    public function getFormFileAndInstrument()
    {
        return $this->getFile();
    }

    public function setFormFileAndInstrument(UploadedFile $file)
    {
        $this->setFile($file);
        $this->setInstrumentFromFilename($file->getClientOriginalName());
    }

    /**
     * @Exclude
     */
    private static $FILE_INSTRUMENT_MAP = [
        'v1' => 'violin 1',
        'vi' => 'violin 1',
        'v2' => 'violin 2',
        'vii' => 'violin 2',
        'v' => 'viola',
        'c' => 'cello',
        'b' => 'bass',
    ];

    /**
     * Set instrument from filename
     *
     * @param string $filename
     * @return PracticePart
     */
    public function setInstrumentFromFilename($filename)
    {
        $filename = strtolower(pathinfo($filename, PATHINFO_FILENAME));
        $filename = preg_replace('/_/', ' ', $filename);
        $words = explode(' ', $filename);

        if (isset(self::$FILE_INSTRUMENT_MAP[$words[0]]))
        {
            $words[0] = self::$FILE_INSTRUMENT_MAP[$words[0]];
        }

        $filename = implode(' ', $words);
        $filename = preg_replace('/(?<=[a-z])(?=\d)/', ' ', $filename); // insert space within, say, 'violin2a'
        $filename = ucwords($filename);

        $this->instrument = $filename;

        return $this;
    }

    /**
     * Get instrument
     *
     * @return string 
     */
    public function getInstrument()
    {
        return $this->instrument;
    }

    /**
     * Set sort_index
     *
     * @param integer $sortIndex
     * @return PracticePart
     */
    public function setSortIndex($sortIndex)
    {
        $this->sort_index = $sortIndex;

        return $this;
    }

    /**
     * Get sort_index
     *
     * @return integer 
     */
    public function getSortIndex()
    {
        return $this->sort_index;
    }

    /**
     * Set piece
     *
     * @param \Icse\PublicBundle\Entity\PieceOfMusic $piece
     * @return PracticePart
     */
    public function setPiece(\Icse\PublicBundle\Entity\PieceOfMusic $piece)
    {
        $this->piece = $piece;

        return $this;
    }

    /**
     * Get piece
     *
     * @return \Icse\PublicBundle\Entity\PieceOfMusic 
     */
    public function getPiece()
    {
        return $this->piece;
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    private function getFileDirectory()
    {
        return self::$base_dir.'/'.$this->getPiece()->getId();
    }

    private function getFileName()
    {
        return $this->getId().'.pdf';
    }

    public function getFilePath()
    {
        return $this->getFileDirectory().'/'.$this->getFileName();
    }

    public function upload()
    {
        $this->getFile()->move($this->getFileDirectory(), $this->getFileName());
        $this->setFile(null);
    }

    private $file_to_remove;

    public function storeFilenameForRemove()
    {
        $this->file_to_remove = $this->getFilePath();
    }

    public function removeUpload()
    {
        unlink($this->file_to_remove);
    }

    /** @Serializer\Accessor(getter="getResourceType") */
    private static $resource_type;
    public function getResourceType()
    {
        return "practiceparts";
    }

    /** @Serializer\Accessor(getter="getUrlResourcePath") */
    private static $url_resource_path;
    public function getUrlResourcePath()
    {
        $path = $this->getId() . '/';
        $piece = $this->getPiece();
        if (!is_null($piece))
        {
            $path .= Tools::slugify($piece->getComposer()) . '/';
            $path .= Tools::slugify($piece->getName()) . '/';
        }
        $path .= Tools::slugify($this->getInstrument()) . '.pdf';
        return $path;
    }
}
