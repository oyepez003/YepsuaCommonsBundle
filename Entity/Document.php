<?php

namespace Yepsua\CommonsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Document
 * 
 * @ORM\Table(name="file_document")
 * @ORM\Entity()
 */
class Document
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $path;
                   
    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/' . $this->path;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../../../web/'.$this->getUploadDir();
    }

    protected static function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/documents';
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getPhotoFile()) {
            $this->path = $this->getPhotoFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getPhotoFile()) {
            return;
        }

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->temp);
            // clear the temp image path
            $this->temp = null;
        }

        // you must throw an exception here if the file cannot be moved
        // so that the entity is not persisted to the database
        // which the UploadedFile move() method does
        $this->getPhotoFile()->move(
            $this->getUploadRootDir(),
            $this->id.'.'.$this->getPhotoFile()->guessExtension()
        );

        $this->setPhotoFile(null);
    }

    /**
     * @ORM\PreRemove()
     */
    public function storeFilenameForRemove()
    {
        $this->temp = $this->getAbsolutePath();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if (isset($this->temp)) {
            unlink($this->temp);
        }
    }

    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->id.'.'.$this->path;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Student
     */
    public function setPath($path)
    {
        $this->path = $path;
    
        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return null === $this->path
            ? null
            : sprintf('<div align="center"><img width="60" align="center" height="60" src="/open-skool/web/%s"></div>', $this->getWebPath());
    }
    
    public function __toString(){
      return $this->getPath();
    }
    

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    public function uploadedFileMapper(UploadedFile $file){
      if($file !== null){
        $this->setPath($file->getClientOriginalName());
      }
    }
    
    public function postMapper(UploadedFile $file){
      $file->move(
          $this->getUploadRootDir(),
          $file->getPath()
      );
    }
        
    public function __construct(UploadedFile $file = null) {
      $this->uploadedFileMapper($file);
    }
}