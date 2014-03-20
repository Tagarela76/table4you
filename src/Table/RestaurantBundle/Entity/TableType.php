<?php

namespace Table\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * TableType
 *
 * @ORM\Table(name="table_type")
 * @ORM\Entity(repositoryClass="Table\RestaurantBundle\Entity\Repository\TableTypeRepository")
 * @Vich\Uploadable
 */
class TableType
{
    const IMAGE_HEIGHT = 60;
    const IMAGE_WIDTH = 60;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="people_count", type="integer")
     */
    private $peopleCount;

    /**
     * @Assert\Image(
     *     maxSize="20M",
     *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg"}
     * )
     * @Vich\UploadableField(mapping="table_type", fileNameProperty="fileName")
     */
    protected $file;

    /**
     * @ORM\Column(name="file_name", type="string", nullable=true)
     */
    protected $fileName;
    
    /**
     * @ORM\OneToMany(targetEntity="ActiveTable", mappedBy="tableType", orphanRemoval=true, cascade={"persist", "remove"})
     */
    protected $activeTables;
    
    
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
     * Set peopleCount
     *
     * @param integer $peopleCount
     * @return TableType
     */
    public function setPeopleCount($peopleCount)
    {
        $this->peopleCount = $peopleCount;
    
        return $this;
    }

    /**
     * Get peopleCount
     *
     * @return integer 
     */
    public function getPeopleCount()
    {
        return $this->peopleCount;
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
     * @return TableType
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
     * @return TableType
     */
    public function setActiveTables($activeTables)
    {
        $this->activeTables = $activeTables;
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
