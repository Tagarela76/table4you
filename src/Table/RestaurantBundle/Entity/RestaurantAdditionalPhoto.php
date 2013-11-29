<?php

namespace Table\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * RestaurantAdditionalPhoto
 * 
 * @ORM\Table(name="restaurant_additional_photo")
 * @ORM\Entity
 * @Vich\Uploadable
 */
class RestaurantAdditionalPhoto
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @Assert\File(
     *     maxSize="20M",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     * )
     * @Vich\UploadableField(mapping="restaurant_additional_photo", fileNameProperty="fileName")
     */
    protected $file;

    /**
     * @ORM\Column(name="file_name", type="string", nullable=true)
     */
    protected $fileName;

    /**
     * @ORM\ManyToOne(targetEntity="Restaurant", inversedBy="additionalPhotos")
     * @ORM\JoinColumn(name="restaurant_id", referencedColumnName="id")
     */
    protected $restaurant;
    
    public function getId()
    {
        return $this->id;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function getFileName()
    {
        return $this->fileName;
    }

    public function getRestaurant()
    {
        return $this->restaurant;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setFile($file)
    {
        $this->file = $file;
    }

    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    public function setRestaurant($restaurant)
    {
        $this->restaurant = $restaurant;
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        return strval($this->getFileName());
    }
}