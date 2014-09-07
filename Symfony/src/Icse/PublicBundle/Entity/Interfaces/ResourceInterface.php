<?php

namespace Icse\PublicBundle\Entity\Interfaces;


interface ResourceInterface
{
    public function getUrlResourcePath();
    public function getResourceType();
    public function getFilePath();
    public function getDownloadName();
}