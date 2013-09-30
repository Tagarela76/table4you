<?php

namespace Table\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TableOrder
 *
 * @ORM\Table(name="table_order")
 * @ORM\Entity(repositoryClass="Table\RestaurantBundle\Entity\Repository\TableOrderRepository")
 */
class TableOrder
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
     * @var Table\RestaurantBundle\Entity\Restaurant $restaurant
     * 
     * @ORM\ManyToOne(targetEntity="Table\RestaurantBundle\Entity\Restaurant", inversedBy="tableOrders")
     * @ORM\JoinColumn(name="restaurant_id", referencedColumnName="id", onDelete="CASCADE")
     * */
    private $restaurant;

    /**
     * @var \Date
     *
     * @ORM\Column(name="reserve_date", type="date")
     */
    private $reserveDate;
    
    /**
     * @var \Time
     *
     * @ORM\Column(name="reserve_time", type="time")
     */
    private $reserveTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="table_number", type="integer")
     */
    private $tableNumber;

    /**
     * @var integer
     *
     * @ORM\Column(name="people_count", type="integer")
     */
    private $peopleCount;

    /**
     * @var Application\Sonata\UserBundle\Entity\User $user
     * 
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", inversedBy="tableOrders")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     * */
    private $user;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="floor", type="integer")
     */
    private $floor;
    
    /**
     * @var string
     *
     * @ORM\Column(name="wish", type="string", length=500, nullable=true)
     */
    private $wish;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="is_smoking_zone", type="boolean")
     */
    private $isSmokingZone;
    
    protected $restaurantName;
    
    protected $userName;
    
    protected $userPhone;
    
    protected $userEmail;

    public static $STATUS_LIST = array(
        "0" => "Не выполнено",
        "1" => "Выполнено"
    );
    
    const PER_PAGE_COUNT = 10;

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
     * Set restaurant
     *
     * @param Table\RestaurantBundle\Entity\Restaurant $restaurant
     * @return TableOrder
     */
    public function setRestaurant($restaurant)
    {
        $this->restaurant = $restaurant;
    
        return $this;
    }

    /**
     * Get restaurant
     *
     * @return Table\RestaurantBundle\Entity\Restaurant $restaurant 
     */
    public function getRestaurant()
    {
        return $this->restaurant;
    }

    /**
     * Set reserveDate
     *
     * @param \Date $reserveDate
     * @return TableOrder
     */
    public function setReserveDate($reserveDate)
    {
        $this->reserveDate = $reserveDate;
    
        return $this;
    }

    /**
     * Get reserveDate
     *
     * @return \Date
     */
    public function getReserveDate()
    {
        return $this->reserveDate;
    }
    
    /**
     * Set reserveTime
     *
     * @param \Time $reserveTime
     * @return TableOrder
     */
    public function setReserveTime($reserveTime)
    {
        $this->reserveTime = $reserveTime;
    
        return $this;
    }

    /**
     * Get reserveTime
     *
     * @return \Time 
     */
    public function getReserveTime()
    {
        return $this->reserveTime;
    }
    

    /**
     * Set tableNumber
     *
     * @param integer $tableNumber
     * @return TableOrder
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
     * Set peopleCount
     *
     * @param integer $peopleCount
     * @return TableOrder
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
     * Set user
     *
     * @param Application\Sonata\UserBundle\Entity\User $user $user
     * @return TableOrder
     */
    public function setUser($user)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return Application\Sonata\UserBundle\Entity\User $user 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return TableOrder
     */
    public function setStatus($status)
    {
        $this->status = $status;
    
        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set floor
     *
     * @param integer $floor
     * @return TableOrder
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
     * Set isSmokingZone
     *
     * @param boolean $isSmokingZone
     * @return TableOrder
     */
    public function setIsSmokingZone($isSmokingZone)
    {
        $this->isSmokingZone = $isSmokingZone;
    
        return $this;
    }

    /**
     * Get isSmokingZone
     *
     * @return boolean 
     */
    public function getIsSmokingZone()
    {
        return $this->isSmokingZone;
    }
    
    /**
     * Get resaurantName
     *
     * @return string 
     */
    public function getRestaurantName()
    {
        return $this->restaurantName;
    }

    /**
     * Set restaurantName
     *
     * @param string $restaurantName
     * @return TableOrder
     */
    public function setRestaurantName($restaurantName)
    {
        $this->restaurantName = $restaurantName;
    }

    /**
     * Get userName
     *
     * @return string 
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * Get userPhone
     *
     * @return string 
     */
    public function getUserPhone()
    {
        return $this->userPhone;
    }

    /**
     * Get userEmail
     *
     * @return string 
     */
    public function getUserEmail()
    {
        return $this->userEmail;
    }

    /**
     * Set userName
     *
     * @param string $userName
     * @return TableOrder
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    /**
     * Set userPhone
     *
     * @param string $userPhone
     * @return TableOrder
     */
    public function setUserPhone($userPhone)
    {
        $this->userPhone = $userPhone;
    }

    /**
     * Set userEmail
     *
     * @param string $userEmail
     * @return TableOrder
     */
    public function setUserEmail($userEmail)
    {
        $this->userEmail = $userEmail;
    }

    /**
     * Get wish
     *
     * @return string 
     */
    public function getWish()
    {
        return $this->wish;
    }

    /**
     * Set wish
     *
     * @param string $wish
     * @return TableOrder
     */
    public function setWish($wish)
    {
        $this->wish = $wish;
    }
          
    /**
     * @return string
     */
    public function __toString()
    {
        return strval($this->getId());
    }
    
    
}
