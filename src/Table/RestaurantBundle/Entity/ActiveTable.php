<?php

namespace Table\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ActiveTable
 *
 * @ORM\Table(name="active_table")
 * @ORM\Entity(repositoryClass="Table\RestaurantBundle\Entity\Repository\ActiveTableRepository")
 */
class ActiveTable
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
     * @var Table\RestaurantBundle\Entity\TableType $tableType
     * 
     * @ORM\ManyToOne(targetEntity="Table\RestaurantBundle\Entity\TableType", inversedBy="activeTables")
     * @ORM\JoinColumn(name="table_type_id", referencedColumnName="id", onDelete="CASCADE")
     * */
    private $tableType;

    /**
     * @var integer
     *
     * @ORM\Column(name="table_number", type="integer")
     */
    private $tableNumber;

    /**
     * @var float
     *
     * @ORM\Column(name="top_position", type="float", scale=2)
     */
    private $topPosition;

    /**
     * @var float
     *
     * @ORM\Column(name="left_position", type="float", scale=2)
     */
    private $leftPosition;

    /**
     * @var float
     *
     * @ORM\Column(name="angle", type="integer", options={"default" = 0}, nullable=true)
     */
    private $angle;

    /**
     * @var Table\RestaurantBundle\Entity\TableMap $tableMap
     * 
     * @ORM\ManyToOne(targetEntity="Table\RestaurantBundle\Entity\TableMap", inversedBy="activeTables")
     * @ORM\JoinColumn(name="table_map_id", referencedColumnName="id", onDelete="CASCADE")
     * */
    private $tableMap;

    /**
     * @ORM\OneToMany(targetEntity="ActiveTableOrder", mappedBy="activeTable", orphanRemoval=true, cascade={"persist", "remove"})
     */
    protected $activeTableOrders;

    /**
     * LeftPosition % 1.25
     *
     * @var float 
     */
    protected $leftPositionResized;

    /**
     * topPosition % 1.25
     *
     * @var float 
     */
    protected $topPositionResized;

    const MAP_SCALE = 1.25;

    public function __construct()
    {
        $this->activeTableOrders = new ArrayCollection();
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
     * Set tableType
     *
     * @param Table\RestaurantBundle\Entity\TableType $tableType
     * @return ActiveTable
     */
    public function setTableType($tableType)
    {
        $this->tableType = $tableType;

        return $this;
    }

    /**
     * Get tableType
     *
     * @return Table\RestaurantBundle\Entity\TableType 
     */
    public function getTableType()
    {
        return $this->tableType;
    }

    /**
     * Set tableNumber
     *
     * @param integer $tableNumber
     * @return ActiveTable
     */
    public function setTableNumber($tableNumber)
    {
        $this->tableNumber = $tableNumber;

        return $this;
    }

    /**
     * Get tableNumber
     *
     * @return integer 
     */
    public function getTableNumber()
    {
        return $this->tableNumber;
    }

    /**
     * Get topPosition
     *
     * @return float 
     */
    public function getTopPosition()
    {
        return $this->topPosition;
    }

    /**
     * Get leftPosition
     *
     * @return float 
     */
    public function getLeftPosition()
    {
        return $this->leftPosition;
    }

    /**
     * Get angle
     *
     * @return integer 
     */
    public function getAngle()
    {
        return $this->angle;
    }

    /**
     * Get tableMap
     *
     * @return Table\RestaurantBundle\Entity\TableMap 
     */
    public function getTableMap()
    {
        return $this->tableMap;
    }

    /**
     * Set top positionPosition
     *
     * @param float $topPosition
     * @return ActiveTable
     */
    public function setTopPosition($topPosition)
    {
        $this->topPosition = $topPosition;

        return $this;
    }

    /**
     * Set Left position
     *
     * @param float $leftPosition
     * @return ActiveTable
     */
    public function setLeftPosition($leftPosition)
    {
        $this->leftPosition = $leftPosition;

        return $this;
    }

    /**
     * Set Angle
     *
     * @param integer $angle
     * 
     * @return ActiveTable
     */
    public function setAngle($angle)
    {
        $this->angle = $angle;

        return $this;
    }

    /**
     * Set tableMap
     *
     * @param Table\RestaurantBundle\Entity\TableMap  $tableMap
     * @return ActiveTable
     */
    public function setTableMap($tableMap)
    {
        $this->tableMap = $tableMap;

        return $this;
    }

    /**
     * Get left postion after resize
     * 
     * @return float
     */
    public function getLeftPositionResized()
    {
        if (is_null($this->getLeftPosition())) {
            return 0;
        }
        if (is_null($this->leftPositionResized)) {
            $leftPositionResized = $this->getLeftPosition() / self::MAP_SCALE;
            $this->setLeftPositionResized($leftPositionResized);
            return $leftPositionResized;
        } else {
            return $this->leftPositionResized;
        }
    }

    /**
     * Get top postion after resize
     * 
     * @return float
     */
    public function getTopPositionResized()
    {
        if (is_null($this->getTopPosition())) {
            return 0;
        }
        if (is_null($this->topPositionResized)) {
            $topPositionResized = $this->getTopPosition() / self::MAP_SCALE;
            $this->setTopPositionResized($topPositionResized);
            return $topPositionResized;
        } else {
            return $this->topPositionResized;
        }
    }

    /**
     * Set leftPositionResized
     *
     * @param float  $leftPositionResized
     * @return ActiveTable
     */
    public function setLeftPositionResized($leftPositionResized)
    {
        $this->leftPositionResized = $leftPositionResized;
        return $this;
    }

    /**
     * Set topPositionResized
     *
     * @param float  $topPositionResized
     * @return ActiveTable
     */
    public function setTopPositionResized($topPositionResized)
    {
        $this->topPositionResized = $topPositionResized;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return strval($this->getTableNumber());
    }

}
