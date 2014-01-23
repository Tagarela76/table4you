<?php

namespace Table\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * TableType
 *
 * @ORM\Table(name="table_type")
 * @ORM\Entity(repositoryClass="Table\RestaurantBundle\Entity\Repository\TableTypeRepository")
 */
class TableType
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
     * @var integer
     *
     * @ORM\Column(name="people_count", type="integer")
     */
    private $peopleCount;

    /**
     * @var Application\Sonata\MediaBundle\Entity\Media $image
     * 
     * @ORM\ManyToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media")
     * @ORM\JoinColumn(name="photo", referencedColumnName="id")
     *
     * @Assert\NotBlank
     * */
    private $image;
    
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

    
    /**
     * Get image
     *
     * @return Application\Sonata\MediaBundle\Entity\Media 
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set image
     *
     * @param Application\Sonata\MediaBundle\Entity\Media $image
     * @return Restaurant
     */
    public function setImage($image)
    {
        $this->image = $image;
    }
}
