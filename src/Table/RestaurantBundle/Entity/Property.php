<?php

namespace Re\PropertyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Re\PropertyBundle\Entity\Category;
use Re\PropertyBundle\Entity\PropertyType;
use Application\Sonata\MediaBundle\Entity\Media;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Property
 *
 * @ORM\Table(name="property")
 * @ORM\Entity(repositoryClass="Re\PropertyBundle\Entity\Repository\PropertyRepository")
 */
class Property
{
    public static $BEDS_LIST = array(
        '1' => '1',
        '2' => '2',
        '3' => '3',
        '4' => '4',
        '5' => '5',
        '6' => '6'
    );
    
    public static $WALL_TYPE = array(
        '1' => 'кирпич',
        '2' => 'панель',
        '3' => 'пеноблок',
        '4' => 'монолит',
        '5' => 'дерево и кирпич'
    );
    
    public static $MARKET_SEGMENT = array(
        '1' => 'Вторичный рынок',
        '2' => 'Новострой'
    );
    
    public static $OFFICE_TYPE = array(
        '0' => 'Не офис',
        '1' => 'бизнес-центр',
        '2' => 'торгово-офисный центр',
        '3' => 'административное здание',
        '4' => 'нежилое помещение в жилом доме',
        '5' => 'жилой фонд'
    );
    
    public static $OFFER_FROM = array(
        '1' => 'Агентство',
        '2' => 'Частное лицо'
    );
    
    public static $LAND_TYPE = array(
        '0' => 'Не земля',
        '1' => 'Поселений (ИЖС)',
        '2' => 'Сельхозназначения (СНТ, ДНП)',
        '3' => 'Промназначения'
    );
    
    public static $SPHERE = array(
        '0' => 'Не бизнес',
        '1' => 'торговля',
        '2' => 'производство продуктов питания',
        '3' => 'производство непродовольственных товаров',
        '4' => 'строительство',
        '5' => 'транспорт / автосервис',
        '6' => 'информационные технологии',
        '7' => 'сельское хозяйство',
        '8' => 'общественное питание',
        '9' => 'финансово / страховые услуги',
        '10' => 'образование',
        '11' => 'бытовые услуги',
        '12' => 'медицина и фармакология',
        '13' => 'спортивно-оздоровительные услуги',
        '14' => 'развлекательные услуги',
        '15' => 'гостиничные услуги',
        '16' => 'автосервис',
        '17' => 'АЗС',
        '18' => 'СМИ / полиграфия',
        '19' => 'разработка полезных ископаемых',
        '20' => 'сдача в аренду'
    );
    
    public static $PRICE_TYPE = array(
        '1' => 'за объект',
        '2' => 'за кв.м.',
        '3' => 'цена за сутки',
        '4' => 'за участок',
        '5' => 'за сотку',
        '6' => 'за Га'
    );

    const PROPERTY_TOP_TO_PAGE_COUNT = 3;
    const PROPERTY_SEARCH_TO_PAGE_COUNT = 10;
    const PROPERTY_TO_PAGE_COUNT = 10;
    const PREVIEW_CHAR_COUNT = 450;
    
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
     * @ORM\Column(name="wall_type", type="integer", nullable=true)
     */
    private $wallType;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="market_segment", type="integer", nullable=true)
     */
    private $marketSegment;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="office_type", type="integer", nullable=true)
     */
    private $officeType;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="offer_from", type="integer", nullable=true)
     */
    private $offerFrom;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="land_type", type="integer", nullable=true)
     */
    private $landType;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="distance_from_city", type="integer", nullable=false)
     */
    private $distanceFromCity;
    
    /**
     * @var string
     *
     * @ORM\Column(name="highway", type="string", length=150, nullable=false)
     */
    private $highway;
    
    /**
     * @var Re\PropertyBundle\Entity\PropertyObjectType $propertyObjectType
     * 
     * @ORM\ManyToOne(targetEntity="PropertyObjectType", inversedBy="property")
     * @ORM\JoinColumn(name="property_object_type", referencedColumnName="id")
     * */
    private $propertyObjectType;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="sphere", type="integer", nullable=true)
     */
    private $sphere;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="price_type", type="integer", nullable=true)
     */
    private $priceType;

    /**
     * @var Re\PropertyBundle\Entity\Category $categoryId
     * 
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="property")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * */
    private $categoryId;

    /**
     * @var Re\PropertyBundle\Entity\Region $region
     * 
     * @ORM\ManyToOne(targetEntity="Region", inversedBy="property")
     * @ORM\JoinColumn(name="region_id", referencedColumnName="id")
     * */
    private $region;

    /**
     * @var Re\PropertyBundle\Entity\City $city
     * 
     * @ORM\ManyToOne(targetEntity="City", inversedBy="property")
     * @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     * */
    private $city;

    /**
     * @var Re\PropertyBundle\Entity\District $district
     * 
     * @ORM\ManyToOne(targetEntity="District", inversedBy="property")
     * @ORM\JoinColumn(name="district_id", referencedColumnName="id")
     * */
    private $district;

    /**
     * @var Re\PropertyBundle\Entity\Street $streetId
     * 
     * @ORM\ManyToOne(targetEntity="Street", inversedBy="property")
     * @ORM\JoinColumn(name="street_id", referencedColumnName="id")
     * */
    private $street;

    /**
     * @var string
     *
     * @ORM\Column(name="house_number", type="string", length=50)
     */
    private $houseNumber;

    /**
     * @var float
     *
     * @ORM\Column(name="total_quadrature", type="float")
     */
    private $totalQuadrature;

    /**
     * @var float
     *
     * @ORM\Column(name="living_quadrature", type="float", nullable=true)
     */
    private $livingQuadrature;

    /**
     * @var float
     *
     * @ORM\Column(name="kitchen_quadrature", type="float", nullable=true)
     */
    private $kitchenQuadrature;

    /**
     * @var integer
     *
     * @ORM\Column(name="floor", type="integer")
     */
    private $floor;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="floors", type="integer", nullable=false)
     */
    private $floors;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var Re\PropertyBundle\Entity\Category $video
     * 
     * @ORM\ManyToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Gallery", inversedBy="property")
     * @ORM\JoinColumn(name="video", referencedColumnName="id", nullable=true)
     * */
    private $video;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var integer
     *
     * @ORM\Column(name="beds", type="integer")
     */
    private $beds;

    /**
     * @var Re\PropertyBundle\Entity\PropertyType $type
     * 
     * @ORM\ManyToOne(targetEntity="PropertyType", inversedBy="property")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     * */
    private $type;

    /**
     * @var Re\PropertyBundle\Entity\PropertyOperation $operation
     * 
     * @ORM\ManyToOne(targetEntity="PropertyOperation", inversedBy="property")
     * @ORM\JoinColumn(name="operation_id", referencedColumnName="id")
     * */
    private $operation;

    /**
     * @var boolean
     *
     * @ORM\Column(name="in_top", type="boolean", nullable=true)
     */
    private $inTop;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean", options={"default" = 1})
     */
    private $enabled;

    /**
     * @var Application\Sonata\MediaBundle\Entity\Media $mainImage
     * 
     * @ORM\ManyToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media")
     * @ORM\JoinColumn(name="main_image", referencedColumnName="id")
     * */
    private $mainImage;
    
    /**
     * @ORM\OneToMany(targetEntity="PropertyAdditionalImage", mappedBy="property", orphanRemoval=true, cascade={"persist", "remove"})
     */
    protected $additionalImages;
    
    /**
     * @var string
     *
     * @ORM\Column(name="owner_name", type="string", length=150)
     */
    private $ownerName;
    
    /**
     * @var string
     *
     * @ORM\Column(name="owner_phone", type="string", length=150)
     */
    private $ownerPhone;
    
    /**
     * @var data
     *
     * @ORM\Column(name="control_date", type="date")
     */
    private $controlDate;
    
    /**
     * @var text
     *
     * @ORM\Column(name="comments", type="text", nullable=true)
     */
    private $comments;

    /**
     * @var Re\PropertyBundle\Entity\SelbikCategory $selbikCategory
     * 
     * @ORM\ManyToOne(targetEntity="SelbikCategory", inversedBy="property")
     * @ORM\JoinColumn(name="selbik_category_id", referencedColumnName="id")
     * */
    private $selbikCategory;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="in_selbik", type="boolean", nullable=true)
     */
    private $inSelbik;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="in_dom_ria", type="boolean", nullable=true)
     */
    private $inDomRia;
    
    /**
     *
     * @var string 
     */
    protected $address;

    
    /**
     * @var datetime
     *
     * @ORM\Column(name="last_update_time", type="datetime", nullable=true)
     */
    private $lastUpdateTime;
    
    /**
     *
     * @var float 
     */
    protected $latitude;

    /**
     *
     * @var float 
     */
    protected $longitude;
    
    /**
     *
     * @var string 
     */
    protected $descriptionPreview;
    
    public function __construct()
    {
        $this->additionalImages = new ArrayCollection();
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
     * Set categoryId
     *
     * @param integer $categoryId
     * @return Property
     */
    public function setCategoryId($categoryId)
    {
        $this->categoryId = $categoryId;

        return $this;
    }

    /**
     * Get categoryId
     *
     * @return integer 
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * Set type
     *
     * @param Re\PropertyBundle\Entity\PropertyType $type
     * @return Property
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return Re\PropertyBundle\Entity\PropertyType 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set beds
     *
     * @param integer $beds
     * @return Property
     */
    public function setBeds($beds)
    {
        $this->beds = $beds;

        return $this;
    }

    /**
     * Get beds
     *
     * @return integer 
     */
    public function getBeds()
    {
        return $this->beds;
    }

    /**
     * Get operation
     *
     * @return Re\PropertyBundle\Entity\PropertyOperation 
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * Set operation
     *
     * @param Re\PropertyBundle\Entity\PropertyOperation $operation
     * @return Property
     */
    public function setOperation($operation)
    {
        $this->operation = $operation;
        return $this;
    }

    /**
     * Set price
     *
     * @param integer $price
     * @return Property
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return integer 
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Property
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set inTop
     *
     * @param boolean $inTop
     * @return Property
     */
    public function setInTop($inTop)
    {
        $this->inTop = $inTop;

        return $this;
    }

    /**
     * Get inTop
     *
     * @return boolean 
     */
    public function getInTop()
    {
        return $this->inTop;
    }

    /**
     * Get mainImage
     *
     * @return Application\Sonata\MediaBundle\Entity\Media 
     */
    public function getMainImage()
    {
        return $this->mainImage;
    }

    /**
     * Set mainImage
     *
     * @param Application\Sonata\MediaBundle\Entity\Gallery $mainImage
     * @return Property
     */
    public function setMainImage($mainImage)
    {
        $this->mainImage = $mainImage;
    }
    
    /**
     * Get region
     *
     * @return Re\PropertyBundle\Entity\Region
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Get city
     *
     * @return Re\PropertyBundle\Entity\City
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Get district
     *
     * @return Re\PropertyBundle\Entity\District
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * Get street
     *
     * @return Re\PropertyBundle\Entity\Street
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set region
     *
     * @param Re\PropertyBundle\Entity\Region $region
     * @return Property
     */
    public function setRegion($region)
    {
        $this->region = $region;
    }

    /**
     * Set city
     *
     * @param Re\PropertyBundle\Entity\City $city
     * @return Property
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * Set district
     *
     * @param Re\PropertyBundle\Entity\District $district
     * @return Property
     */
    public function setDistrict($district)
    {
        $this->district = $district;
    }

    /**
     * Set street
     *
     * @param Re\PropertyBundle\Entity\Street $street
     * @return Property
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }

    /**
     * Get houseNumber
     *
     * @return string 
     */
    public function getHouseNumber()
    {
        return $this->houseNumber;
    }

    /**
     * Set houseNumber
     *
     * @param string $houseNumber
     * @return Property
     */
    public function setHouseNumber($houseNumber)
    {
        $this->houseNumber = $houseNumber;
    }

    /**
     * Get totalQuadrature
     *
     * @return float 
     */
    public function getTotalQuadrature()
    {
        return $this->totalQuadrature;
    }

    /**
     * Set totalQuadrature
     *
     * @param float $totalQuadrature
     * @return Property
     */
    public function setTotalQuadrature($totalQuadrature)
    {
        $this->totalQuadrature = $totalQuadrature;
    }

    /**
     * Get livingQuadrature
     *
     * @return float 
     */
    public function getLivingQuadrature()
    {
        return $this->livingQuadrature;
    }

    /**
     * Set livingQuadrature
     *
     * @param float $livingQuadrature
     * @return Property
     */
    public function setLivingQuadrature($livingQuadrature)
    {
        $this->livingQuadrature = $livingQuadrature;
    }

    /**
     * Get kitchenQuadrature
     *
     * @return float 
     */
    public function getKitchenQuadrature()
    {
        return $this->kitchenQuadrature;
    }

    /**
     * Set kitchenQuadrature
     *
     * @param float $kitchenQuadrature
     * @return Property
     */
    public function setKitchenQuadrature($kitchenQuadrature)
    {
        $this->kitchenQuadrature = $kitchenQuadrature;
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
     * Set floor
     *
     * @param integer $floor
     * @return Property
     */
    public function setFloor($floor)
    {
        $this->floor = $floor;
    }
    
    /**
     * Get floors
     *
     * @return integer 
     */
    public function getFloors()
    {
        return $this->floors;
    }

    /**
     * Set floors
     *
     * @param integer $floors
     * @return Property
     */
    public function setFloors($floors)
    {
        $this->floors = $floors;
    }

    /**
     * Get video
     *
     * @return Application\Sonata\MediaBundle\Entity\Gallery
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * Set video
     *
     * @param Application\Sonata\MediaBundle\Entity\Gallery $video
     * @return Property
     */
    public function setVideo($video)
    {
        $this->video = $video;
    }
    

    /**
     * Get enabled
     * 
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set enabled
     * 
     * @param boolean $enabled
     * @return Property
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * Get ownerName
     * 
     * @return string
     */
    public function getOwnerName()
    {
        return $this->ownerName;
    }

    /**
     * Get ownerPhone
     * 
     * @return string
     */
    public function getOwnerPhone()
    {
        return $this->ownerPhone;
    }

    /**
     * Get controlDate
     * 
     * @return date
     */
    public function getControlDate()
    {
        return $this->controlDate;
    }

    /**
     * Get comments
     * 
     * @return text
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set ownerName
     * 
     * @param string $ownerName
     * @return Property
     */
    public function setOwnerName($ownerName)
    {
        $this->ownerName = $ownerName;
    }

    /**
     * Set ownerPhone
     * 
     * @param string $ownerPhone
     * @return Property
     */
    public function setOwnerPhone($ownerPhone)
    {
        $this->ownerPhone = $ownerPhone;
    }

    /**
     * Set controlDate
     * 
     * @param date $controlDate
     * @return Property
     */
    public function setControlDate($controlDate)
    {
        $this->controlDate = $controlDate;
    }

    /**
     * Set comments
     * 
     * @param text $comments
     * @return Property
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }
    
    /**
     * Set selbikCategory
     *
     * @param integer $selbikCategory
     * @return Property
     */
    public function setSelbikCategory($selbikCategory)
    {
        $this->selbikCategory = $selbikCategory;

        return $this;
    }

    /**
     * Get selbikCategory
     *
     * @return integer 
     */
    public function getSelbikCategory()
    {
        return $this->selbikCategory;
    }
    
    /**
     * Set inSelbik
     *
     * @param boolean $inSelbik
     * @return Property
     */
    public function setInSelbik($inSelbik)
    {
        $this->inSelbik = $inSelbik;

        return $this;
    }

    /**
     * Get inSelbik
     *
     * @return boolean 
     */
    public function getInSelbik()
    {
        return $this->inSelbik;
    }
    

    /**
     * Get wallType
     *
     * @return integer 
     */
    public function getWallType()
    {
        return $this->wallType;
    }

    /**
     * Get marketSegment
     *
     * @return integer 
     */
    public function getMarketSegment()
    {
        return $this->marketSegment;
    }

    /**
     * Get officeType
     *
     * @return integer 
     */
    public function getOfficeType()
    {
        return $this->officeType;
    }

    /**
     * Get offerFrom
     *
     * @return integer 
     */
    public function getOfferFrom()
    {
        return $this->offerFrom;
    }

    /**
     * Get landType
     *
     * @return integer 
     */
    public function getLandType()
    {
        return $this->landType;
    }

    /**
     * Get distanceFromCity
     *
     * @return integer 
     */
    public function getDistanceFromCity()
    {
        return $this->distanceFromCity;
    }

    /**
     * Set wallType
     *
     * @param integer $wallType
     * @return Property
     */
    public function setWallType($wallType)
    {
        $this->wallType = $wallType;
        
        return $this;
    }

    /**
     * Set marketSegment
     *
     * @param integer $marketSegment
     * @return Property
     */
    public function setMarketSegment($marketSegment)
    {
        $this->marketSegment = $marketSegment;
        
        return $this;
    }

    /**
     * Set officeType
     *
     * @param integer $officeType
     * @return Property
     */
    public function setOfficeType($officeType)
    {
        $this->officeType = $officeType;
        
        return $this;
    }

    /**
     * Set offerFrom
     *
     * @param integer $offerFrom
     * @return Property
     */
    public function setOfferFrom($offerFrom)
    {
        $this->offerFrom = $offerFrom;
        
        return $this;
    }

    /**
     * Set landType
     *
     * @param integer $landType
     * @return Property
     */
    public function setLandType($landType)
    {
        $this->landType = $landType;
        
        return $this;
    }

    /**
     * Set distanceFromCity
     *
     * @param integer $distanceFromCity
     * @return Property
     */
    public function setDistanceFromCity($distanceFromCity)
    {
        $this->distanceFromCity = $distanceFromCity;
        
        return $this;
    }
    
    /**
     * Get highway
     *
     * @return string 
     */
    public function getHighway()
    {
        return $this->highway;
    }

    /**
     * Set highway
     *
     * @param string $highway
     * @return Property
     */
    public function setHighway($highway)
    {
        $this->highway = $highway;
        
        return $this;
    }
    
    /**
     * Set propertyObjectType
     *
     * @param Re\PropertyBundle\Entity\PropertyObjectType $propertyObjectType
     * @return Property
     */
    public function setPropertyObjectType($propertyObjectType)
    {
        $this->propertyObjectType = $propertyObjectType;
        
        return $this;
    }
    
    /**
     * Get propertyObjectType
     *
     * @return Re\PropertyBundle\Entity\PropertyObjectType
     */
    public function getPropertyObjectType()
    {
        return $this->propertyObjectType;
    }
    
    /**
     * Set sphere
     *
     * @param string $sphere
     * @return Property
     */
    public function setSphere($sphere)
    {
        $this->sphere = $sphere;
        
        return $this;
    }
    
    /**
     * Get sphere
     *
     * @return integer 
     */
    public function getSphere()
    {
        return $this->sphere;
    }
    
    /**
     * Set priceType
     *
     * @param string $priceType
     * @return Property
     */
    public function setPriceType($priceType)
    {
        $this->priceType = $priceType;
        
        return $this;
    }
    
    /**
     * Get priceType
     *
     * @return integer 
     */
    public function getPriceType()
    {
        return $this->priceType;
    }
    
    /**
     * Set inDomRia
     *
     * @param boolean $inDomRia
     * @return Property
     */
    public function setInDomRia($inDomRia)
    {
        $this->inDomRia = $inDomRia;

        return $this;
    }

    /**
     * Get inDomRia
     *
     * @return boolean 
     */
    public function getInDomRia()
    {
        return $this->inDomRia;
    }
    
    /**
     * Set $lastUpdateTime
     *
     * @param datetime $lastUpdateTime
     * @return Property
     */
    public function setLastUpdateTime($lastUpdateTime)
    {
        $this->lastUpdateTime = $lastUpdateTime;

        return $this;
    }

    /**
     * Get lastUpdateTime
     *
     * @return datetime 
     */
    public function getLastUpdateTime()
    {
        return $this->lastUpdateTime;
    }
    
    /**
     * Get address
     * 
     * @return string
     */
    public function getAddress()
    {
        if (is_null($this->address)) {
            $address = $this->getHouseNumber() . " " . $this->getStreet()->getName() . " " .
                    $this->getCity()->getName() . " " . $this->getRegion()->getName(); 
            $address = str_replace(' ', '+', $address);
            $this->setAddress($address);
            return $address;
        } else {
            return $this->address;
        }
    }

    /**
     * 
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * Get latitude
     * 
     * @return float
     */
    public function getLatitude()
    {
        if (is_null($this->getAddress())) {
            return false;
        } 
        if (is_null($this->latitude)) {
            $geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $this->getAddress() . '&sensor=false');
            //weak internet connection
            if (!$geocode) {
                return false;
            }
            $output = json_decode($geocode);
            if (empty($output->results)) {
                return false;
            }
            $latitude = $output->results[0]->geometry->location->lat;
            $this->setLatitude($latitude); 
            return $latitude;
        } else {
            return $this->latitude;
        }
    }

    /**
     * 
     * @param float $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * 
     * @return float
     */
    public function getLongitude()
    {
        if (is_null($this->getAddress())) {
            return false;
        }
        if (is_null($this->longitude)) {
            $geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $this->getAddress() . '&sensor=false');
            //weak internet connection
            if (!$geocode) {
                return false;
            }
            $output = json_decode($geocode);
            if (empty($output->results)) {
                return false;
            }
            $longitude = $output->results[0]->geometry->location->lng;
            $this->setLongitude($longitude);
            return $longitude;
        } else {
            return $this->longitude;
        }
    }

    /**
     * 
     * @param float $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }
    
    /**
     * 
     * @return string
     */
    public function getDescriptionPreview()
    {
        if (is_null($this->descriptionPreview)) {
            $descriptionLength = (mb_strlen($this->getDescription()) > self::PREVIEW_CHAR_COUNT)
                ? mb_strripos(mb_substr($this->getDescription(), 0, self::PREVIEW_CHAR_COUNT), ' ')
                : self::PREVIEW_CHAR_COUNT
            ;
            $descriptionPreview = mb_substr($this->getDescription(), 0, $descriptionLength);
            return (mb_strlen($this->getDescription()) > self::PREVIEW_CHAR_COUNT)
                ? $descriptionPreview . '...'
                : $descriptionPreview
            ;
            $this->setDescriptionPreview($descriptionPreview);
            return $descriptionPreview;
        } else {
            return $this->descriptionPreview;
        }
    }

    /**
     * 
     * @param float $descriptionPreview
     */
    public function setDescriptionPreview($descriptionPreview)
    {
        $this->descriptionPreview = $descriptionPreview;
    }

        
    /**
     * Get additionalImages
     *
     * @return Re\PropertyBundle\Entity\PropertyAdditionalImage[] 
     */
    public function getAdditionalImages()
    {
        return $this->additionalImages;
    }

    /**
     * Set additionalImages
     *
     * @param Re\PropertyBundle\Entity\PropertyAdditionalImage $additionalImages
     * @return Restaurant
     */
    public function setAdditionalImages($additionalImages)
    {
        $this->additionalImages = $additionalImages;
    }
    

    /**
     * Add additionalImages
     * 
     * @param Re\PropertyBundle\Entity\PropertyAdditionalImage $additionalImages
     */
    public function addAdditionalImages($additionalImages)
    {
        $this->additionalImages[] = $additionalImages;
    }

    /**
     * Remove additionalImages
     * 
     * @param Re\PropertyBundle\Entity\PropertyAdditionalImage $additionalImages
     */
    public function removeAdditionalImages($additionalImages)
    {
        $this->additionalImages->removeElement($additionalImages);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return strval($this->getId());
    }
}
