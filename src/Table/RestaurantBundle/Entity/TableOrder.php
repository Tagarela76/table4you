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
     * @ORM\Column(name="status", type="integer", options={"default" = 0}, nullable=true)
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
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="is_sms", type="boolean")
     */
    private $isSms;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="is_email", type="boolean")
     */
    private $isEmail;
    
    /**
     * Status color
     *
     * @var string 
     */
    protected $statusColor;
    
    /**
     * Status name
     *
     * @var string 
     */
    protected $statusName;
    
    /**
     * Restaurant name
     *
     * @var string 
     */
    protected $restaurantName;
    
    /**
     * User Name
     *
     * @var string 
     */
    protected $userName;
    
    /**
     * User Phone
     *
     * @var string 
     */
    protected $userPhone;
    
    /**
     * User Email
     *
     * @var string 
     */
    protected $userEmail;

    public static $STATUS_LIST = array(
        "0" => "Не обработано",
        "1" => "Не выполнено",
        "2" => "Выполнено"
    );
    
    public static $STATUS_COLOR_LIST = array(
        "0" => "#fff",
        "1" => "#66ccff",
        "2" => "#ff9900"
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
     * @param #s $status
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
        // We can get restaurant name only if we have restaurant
        if (is_null($this->getRestaurant())) {
            return null;
        }
        if (is_null($this->restaurantName)) {
            $restaurantName = $this->getRestaurant()->getName();
            $this->setRestaurantName($restaurantName);
            return $restaurantName;
        } else {
            return $this->restaurantName;
        }        
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
        // We can get user name only if we have user
        if (is_null($this->getUser())) {
            return null;
        }
        if (is_null($this->userName)) {
            $userName = $this->getUser()->getUsername(); 
            $this->setUserName($userName);
            return $userName;
        } else {
            return $this->userName;
        }  
    }

    /**
     * Get userPhone
     *
     * @return string 
     */
    public function getUserPhone()
    {
        // We can get user phone only if we have user
        if (is_null($this->getUser())) {
            return null;
        }
        if (is_null($this->userPhone)) {
            $userPhone = $this->getUser()->getPhone();
            $this->setUserPhone($userPhone);
            return $userPhone;
        } else {
            return $this->userPhone;
        } 
    }

    /**
     * Get userEmail
     *
     * @return string 
     */
    public function getUserEmail()
    {
        // We can get user email only if we have user
        if (is_null($this->getUser())) {
            return null;
        }
        if (is_null($this->userEmail)) {
            $userEmail = $this->getUser()->getEmail();
            $this->setUserEmail($userEmail);
            return $userEmail;
        } else {
            return $this->userEmail;
        } 
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
     * Get isSms
     *
     * @return boolean 
     */
    public function getIsSms()
    {
        return $this->isSms;
    }

    /**
     * Get isEmail
     *
     * @return boolean 
     */
    public function getIsEmail()
    {
        return $this->isEmail;
    }

    /**
     * Set isSms
     *
     * @param boolean $isSms
     * @return TableOrder
     */
    public function setIsSms($isSms)
    {
        $this->isSms = $isSms;
    }

    /**
     * Set isEmail
     *
     * @param boolean $isEmail
     * @return TableOrder
     */
    public function setIsEmail($isEmail)
    {
        $this->isEmail = $isEmail;
    }
        
    /**
     * Set statusColor
     *
     * @param string $statusColor
     * @return TableOrder
     */
    public function setStatusColor($statusColor)
    {
        $this->statusColor = $statusColor;
    
        return $this;
    }

    /**
     * Get statusColor (depend on color list)
     *
     * @return string 
     */
    public function getStatusColor()
    {
        if (is_null($this->getStatus())) {
            return false;
        }
        if (is_null($this->statusColor)) {
            $statusColor = self::$STATUS_COLOR_LIST[$this->getStatus()]; 
            $this->setStatusColor($statusColor);
            return $statusColor;
        } else {
            return $this->statusColor;
        }
    }
    
    /**
     * Set statusName
     *
     * @param string $statusName
     * @return TableOrder
     */
    public function setStatusName($statusName)
    {
        $this->statusName = $statusName;
    
        return $this;
    }

    /**
     * Get statusName (depend on status list)
     *
     * @return string 
     */
    public function getStatusName()
    {
        if (is_null($this->getStatus())) {
            return false;
        }
        if (is_null($this->statusName)) {
            $statusName = self::$STATUS_LIST[$this->getStatus()]; 
            $this->setStatusName($statusName);
            return $statusName;
        } else {
            return $this->statusName;
        }
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        return strval($this->getId());
    }
    
    
}
