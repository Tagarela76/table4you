<?php

namespace Table\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * TableMap
 *
 * @ORM\Table(name="table_map")
 * @ORM\Entity(repositoryClass="Table\RestaurantBundle\Entity\Repository\TableMapRepository")
 * @Vich\Uploadable
 */
class TableMap
{
    const IMAGE_HEIGHT = 400;
    const IMAGE_WIDTH = 400;
    
    const IMAGE_HEIGHT_BIG= 800;
    const IMAGE_WIDTH_BIG  = 800;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Assert\File(
     *     maxSize="20M",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     * )
     * @Vich\UploadableField(mapping="table_map", fileNameProperty="fileName")
     */
    protected $file;

    /**
     * @ORM\Column(name="file_name", type="string", nullable=true)
     */
    protected $fileName;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="floor", type="integer")
     */
    private $floor;

    /**
     * @var integer
     *
     * @ORM\Column(name="hall", type="integer", nullable=true)
     */
    private $hall;
    
    /**
     * @var Table\RestaurantBundle\Entity\Restaurant $restaurant
     * 
     * @ORM\ManyToOne(targetEntity="Table\RestaurantBundle\Entity\Restaurant", inversedBy="tableMaps")
     * @ORM\JoinColumn(name="restaurant_id", referencedColumnName="id", onDelete="CASCADE")
     * */
    private $restaurant;

    /**
     * @ORM\OneToMany(targetEntity="ActiveTable", mappedBy="tableType", orphanRemoval=true, cascade={"persist", "remove"})
     */
    protected $activeTables;
    
    /**
     *
     * big file name
     * 
     * @var string
     */
    protected $bigFileName;
    
    public function __construct()
    {
        $this->activeTables = new ArrayCollection();
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
     * Set floor
     *
     * @param integer $floor
     * @return TableMap
     */
    public function setFloor($floor)
    {
        $this->floor = $floor;
    
        return $this;
    }

    /**
     * Get floor
     *
     * @return integer 
     */
    public function getFloor()
    {
        return $this->floor;
    }

    /**
     * Set hall
     *
     * @param integer $hall
     * @return TableMap
     */
    public function setHall($hall)
    {
        $this->hall = $hall;
    
        return $this;
    }

    /**
     * Get hall
     *
     * @return integer 
     */
    public function getHall()
    {
        return $this->hall;
    }
    
    /**
     * Set restaurant
     *
     * @param Table\RestaurantBundle\Entity\Restaurant $restaurant
     * @return TableMap
     */
    public function setRestaurant($restaurant)
    {
        $this->restaurant = $restaurant;
    
        return $this;
    }

    /**
     * Get restaurant
     *
     * @return Table\RestaurantBundle\Entity\Restaurant 
     */
    public function getRestaurant()
    {
        return $this->restaurant;
    }
    
    public function getFile()
    {
        return $this->file;
    }
    
    /**
     * 
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * 
     * @param string $fileName
     * 
     * @return TableMap
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }
    
    /**
     * 
     * @return Table\RestaurantBundle\Entity\TableMap[]
     */
    public function getActiveTables()
    {
        return $this->activeTables;
    }

    /**
     * 
     * @param Table\RestaurantBundle\Entity\TableMap[] $activeTables
     * 
     * @return TableMap
     */
    public function setActiveTables($activeTables)
    {
        $this->activeTables = $activeTables;
        return $this;
    }
    
    /**
     * Get big image
     * 
     * @return string|null
     */
    public function getBigFileName()
    {
        if (is_null($this->getFileName())) {
            return null;
        }
        if (is_null($this->bigFileName)) {
            $imageArray = explode(".", $this->getFileName());
            $ext = end($imageArray);
            // unset last elem (ext)
            unset($imageArray[count($imageArray) - 1]);
            $bigImage = implode(".", $imageArray) . "_big." . $ext;
            $this->setBigFileName($bigImage);
            return $bigImage;
                
        } else {
            return $this->bigFileName;
        }
    }

    /**
     * 
     * @param string $bigFileName
     * 
     * @return TableMap
     */
    public function setBigFileName($bigFileName)
    {
        $this->bigFileName = $bigFileName;
        return $this;
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        return strval($this->getFileName());
    }
}
