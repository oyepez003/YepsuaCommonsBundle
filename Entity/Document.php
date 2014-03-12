<?php

namespace Yepsua\CommonsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Document
 * 
 * @ORM\MappedSuperclass
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
    private $name;
    
    private $uploadDir;
    
    private $file;
    
    public function __construct(UploadedFile $file = null, $uploadDir = null) {
      $this->setFile($file);
      $this->setUploadDir($uploadDir);
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
    
    /**
     * 
     * @param type $id
     */
    public function setId($id) {
        $this->id = $id;
    }
    
    /**
     * 
     * @return type
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * 
     * @param type $name
     */
    public function setName($name) {
        $this->name = $name;
    }
    
    /**
     * 
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $file
     */
    public function setFile(UploadedFile $file){
      if($file !== null){
        $this->file = $file;
        $this->setName($file->getClientOriginalName());
      }
    }
    
    /**
     * 
     * @return \Symfony\Component\HttpFoundation\File\UploadedFile $file
     */
    public function getFile() {
        return $this->file;
    }
    
    /**
     * The absolute directory path where uploaded 
     * documents should be saved
     * @return type
     */
    protected function getOutputDir()
    {
        return $this->getUploadRootDir().$this->getUploadDir();
    }
    
    /**
     * The absolute directory path where uploaded 
     * documents should be saved
     * @return type
     */
    protected function getUploadRootDir()
    {
        return __DIR__.'/../../../../../../web/';
    }
    
    public function getWebPath(){
        return "/symfony/web";
    }
    
    /**
     * get rid of the __DIR__ so it doesn't screw up
     * when displaying uploaded doc/image in the view.
     * @return string
     */
    protected function getUploadDir()
    {
        return ($this->uploadDir === null) ? 'uploads/documents' : $this->uploadDir;
    }
    
    public function setUploadDir($uploadDir) {
        $this->uploadDir = $uploadDir;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {

    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        
    }

    /**
     * @ORM\PreRemove()
     */
    public function storeFilenameForRemove()
    {
        
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        
    }
    
    public function __toString(){
      return sprintf($this->getHTMLTemplate(), $this->getWebPath(), $this->getUploadDir(), $this->getName());
    }
    
    public function getHTMLTemplate(){
        return '<div align="center">
                    <img width="60" align="center" height="60" src="%s/%s/%s">
                </div>';
    }
    
    public function write(){
      $this->getFile()->move(
          $this->getOutputDir(),
          $this->getName()
      );
    }
}