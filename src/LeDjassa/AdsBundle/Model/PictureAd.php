<?php

namespace LeDjassa\AdsBundle\Model;

use LeDjassa\AdsBundle\Model\om\BasePictureAd;

class PictureAd extends BasePictureAd
{
    /**
     * Virtual property representing a file
     * @var File
     */
	public $file = array();
	
    /**
     * Get the absolute path
     * @return string|null
     */
	public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
    }

    /**
     * Get web path
     * @return string|null
     */
    public function getWebPath()
    {
        return null === $this->path ? null : $this->getUploadDir().'/'.$this->path;
    }

    /**
     * Get upload root directory
     * @return string
     */
    protected function getUploadRootDir()
    {
        // absolute dir where picture are upload
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    /**
     * Get directory storing pictures
     * @return string
     */
    protected function getUploadDir()
    {
        return 'uploads/pictures';
    }

    /**
     * Move file to the target directory
     * @return boolean true if process success otherwise false
     */
    public function upload()
    {   
        if (null === $this->file) {
            return false;
        }

        $this->file->move($this->getUploadRootDir(), $this->path);

        unset($this->file);

        return true;
    }

    /**
     * Generate file name and set path
     * @return boolean
     */
    public function preUpload()
    {   
        if (null === $this->file) {
            return false;
        }

        // generate file name
        $extension = $this->file->guessExtension() ? $this->file->guessExtension() : 'jpg';
        $this->setPath(rand(1, 99999).'.'.$extension);

        return true;
    }

    /**
     * Remove file from directory
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }

    public function preInsert(\PropelPDO $con = null)
    {   
        return $this->preUpload();
    }

    public function preUpdate(\PropelPDO $con = null)
    {
        return $this->preUpload();
    }

    public function postInsert(\PropelPDO $con = null)
    {   
        return $this->upload();
    }

    public function postUpdate(\PropelPDO $con = null)
    {
        return $this->upload();
    }
    
    public function postDelete(\PropelPDO $con = null)
    {
        return $this->removeUpload();
    }

}
