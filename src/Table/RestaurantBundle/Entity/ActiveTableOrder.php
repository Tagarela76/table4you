<?php

namespace Table\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TableOrder
 *
 * @ORM\Table(name="active_table_order")
 * @ORM\Entity(repositoryClass="Table\RestaurantBundle\Entity\Repository\ActiveTableOrderRepository")
 */
class ActiveTableOrder
{
    const RESERVE_TIMEZONE = "Europe/Moscow";
        
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

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
     * @var Table\RestaurantBundle\Entity\ActiveTable $activeTable
     * 
     * @ORM\ManyToOne(targetEntity="Table\RestaurantBundle\Entity\ActiveTable", inversedBy="activeTableOrders")
     * @ORM\JoinColumn(name="active_table_id", referencedColumnName="id", onDelete="CASCADE")
     * */
    private $activeTable;

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
     * @var string
     *
     * @ORM\Column(name="wish", type="string", length=500, nullable=true)
     */
    private $wish;   
    
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
     * User Name
     *
     * @var string 
     */
    protected $userName;
    
    /**
     * User Last Name
     *
     * @var string 
     */
    protected $userLastName;


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
    
    /**
     * 
     * Restaurant
     *
     * @var Table\RestaurantBundle\Entity\Restaurant
     */
    protected $restaurant;
    
    /**
     * Restaurant name
     *
     * @var string 
     */
    protected $restaurantName;
    
    /**
     * Table number
     *
     * @var integer 
     */
    protected $tableNumber;

    public static $STATUS_LIST = array(
        self::ORDER_NOTHING_DID_STATUS_CODE => "Не обработано",
        self::ORDER_REJECT_STATUS_CODE => "Не выполнено",
        self::ORDER_ACCEPT_STATUS_CODE => "Выполнено"
    );
    
    public static $STATUS_COLOR_LIST = array(
        "0" => "#fff",
        "1" => "#66ccff",
        "2" => "#ff9900"
    );
    
    const PER_PAGE_COUNT = 10;

    const ORDER_ACCEPT_STATUS_CODE = 2;
    const ORDER_REJECT_STATUS_CODE = 1;
    const ORDER_NOTHING_DID_STATUS_CODE = 0;

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
     * Set reserveDate
     *
     * @param \Date $reserveDate
     * @return ActiveTableOrder
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
     * @return ActiveTableOrder
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
     * Set activeTable
     *
     * @param Table\RestaurantBundle\Entity\ActiveTable  $activeTable
     * @return ActiveTableOrder
     */
    public function setActiveTable($activeTable)
    {
        $this->activeTable = $activeTable;
    
        return $this;
    }

    /**
     * Get activeTable
     *
     * @return Table\RestaurantBundle\Entity\ActiveTable 
     */
    public function getActiveTable()
    {
        return $this->activeTable;
    }

    /**
     * Set peopleCount
     *
     * @param integer $peopleCount
     * @return ActiveTableOrder
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
     * @return ActiveTableOrder
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
     * @return ActiveTableOrder
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
     * Get userName
     *
     * @return string 
     */
    public function getUserName()
    {
        // We can get user name only if we have user
        if (is_null($this->getUser())) {
            return $this->userName;
        }
        if (is_null($this->userName)) {
            $userName = $this->getUser()->getPublicName(); 
            $this->setUserName($userName);
        } 
        return $this->userName;
    }
    
    /**
     * Get userLastName
     *
     * @return string 
     */
    public function getUserLastName()
    {
        // We can get user last name only if we have user
        if (is_null($this->getUser())) {
            return $this->userLastName;
        }
        if (is_null($this->userLastName)) {
            $userLastName = $this->getUser()->getLastName(); 
            $this->setUserLastName($userLastName);
            return $userLastName;
        } else {
            return $this->userLastName;
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
            return $this->userPhone;
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
            return $this->userEmail;
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
     * @return ActiveTableOrder
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }
    
    /**
     * Set userLastName
     *
     * @param string $userLastName
     * @return ActiveTableOrder
     */
    public function setUserLastName($userLastName)
    {
        $this->userLastName = $userLastName;
    }

    /**
     * Set userPhone
     *
     * @param string $userPhone
     * @return ActiveTableOrder
     */
    public function setUserPhone($userPhone)
    {
        $this->userPhone = $userPhone;
    }

    /**
     * Set userEmail
     *
     * @param string $userEmail
     * @return ActiveTableOrder
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
     * @return ActiveTableOrder
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
     * @return ActiveTableOrder
     */
    public function setIsSms($isSms)
    {
        $this->isSms = $isSms;
    }

    /**
     * Set isEmail
     *
     * @param boolean $isEmail
     * @return ActiveTableOrder
     */
    public function setIsEmail($isEmail)
    {
        $this->isEmail = $isEmail;
    }
        
    /**
     * Set statusColor
     *
     * @param string $statusColor
     * @return ActiveTableOrder
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
     * @return ActiveTableOrder
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
     * Get restaurant
     *
     * @return Table\RestaurantBundle\Entity\Restaurant 
     */
    public function getRestaurant()
    {
        // We can get restaurant
        if (is_null($this->getActiveTable())) {
            return $this->restaurant;
        } 
        if (is_null($this->restaurant)) {
            $restaurant = $this->getActiveTable()->getTableMap()->getRestaurant();
            $this->setRestaurant($restaurant);
            return $restaurant;
        } else {
            return $this->restaurant;
        } 
    }

    /**
     * Set restaurant
     *
     * @param Table\RestaurantBundle\Entity\Restaurant $restaurant
     * @return ActiveTableOrder
     */
    public function setRestaurant($restaurant)
    {
        $this->restaurant = $restaurant;
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
     * Get tableNumber
     *
     * @return string 
     */
    public function getTableNumber()
    {       
        if (is_null($this->tableNumber)) {
            $activeTable = $this->getActiveTable();
            // We can get table number only if we have active table
            if (is_null($activeTable)) {
                return 0;
            }
            $tableNumber = $activeTable->getTableNumber();
            $this->setTableNumber($tableNumber);
            return $tableNumber;
        } else {
            return $this->tableNumber;
        }        
    }

    /**
     * Set tableNumber
     *
     * @param string $tableNumber
     * 
     * @return TableOrder
     */
    public function setTableNumber($tableNumber)
    {
        $this->tableNumber = $tableNumber;
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        return strval($this->getId());
    }
    
    
}
