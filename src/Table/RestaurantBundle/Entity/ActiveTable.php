<?php

namespace Table\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @var integer
     *
     * @ORM\Column(name="top", type="integer")
     */
    private $top;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="left", type="integer")
     */
    private $left;

    /**
     * @var Table\RestaurantBundle\Entity\TableMap $tableMap
     * 
     * @ORM\ManyToOne(targetEntity="Table\RestaurantBundle\Entity\TableMap", inversedBy="activeTables")
     * @ORM\JoinColumn(name="table_map_id", referencedColumnName="id", onDelete="CASCADE")
     * */
    private $tableMap;

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
}
