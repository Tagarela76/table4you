<?php

namespace Table\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Restaurant
 *
 * @ORM\Table(name="restaurant")
 * @ORM\Entity(repositoryClass="Table\RestaurantBundle\Entity\Repository\RestaurantRepository")
 */
class Restaurant
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=150)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=150)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=150)
     */
    private $street;

    /**
     * @var string
     *
     * @ORM\Column(name="house", type="string", length=50)
     */
    private $house;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="work_hours_from", type="time")
     */
    private $workHoursFrom;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="work_hours_to", type="time")
     */
    private $workHoursTo;

    /**
     * @var Table\RestaurantBundle\Entity\RestaurantKitchen $kitchenId
     * 
     * @ORM\ManyToOne(targetEntity="RestaurantKitchen", inversedBy="restaurant")
     * @ORM\JoinColumn(name="kitchen_id", referencedColumnName="id")
     * */
    private $kitchenId;

    /**
     * @var Table\RestaurantBundle\Entity\RestaurantCategory $categoryId
     * 
     * @ORM\ManyToOne(targetEntity="RestaurantCategory", inversedBy="restaurant")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * */
    private $categoryId;

    /**
     * @var Application\Sonata\MediaBundle\Entity\Media $photo
     * 
     * @ORM\ManyToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media")
     * @ORM\JoinColumn(name="photo", referencedColumnName="id")
     * */
    private $photo;

    /**
     * @ORM\ManyToMany(targetEntity="Table\RestaurantBundle\Entity\RestaurantAdditionalServices")
     * @ORM\JoinTable(name="restaurant2additional_services",
     *   joinColumns={@ORM\JoinColumn(name="restaurant_id", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="additional_services_id", referencedColumnName="id")}
     * )
     */
    protected $additionalServices;

    public function __construct() {
        $this->additionalServices = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Restaurant
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Restaurant
     */
    public function setCity($city)
    {
        $this->city = $city;
    
        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set street
     *
     * @param string $street
     * @return Restaurant
     */
    public function setStreet($street)
    {
        $this->street = $street;
    
        return $this;
    }

    /**
     * Get street
     *
     * @return string 
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set house
     *
     * @param string $house
     * @return Restaurant
     */
    public function setHouse($house)
    {
        $this->house = $house;
    
        return $this;
    }

    /**
     * Get house
     *
     * @return string 
     */
    public function getHouse()
    {
        return $this->house;
    }

    /**
     * Set workHoursFrom
     *
     * @param \DateTime $workHoursFrom
     * @return Restaurant
     */
    public function setWorkHoursFrom($workHoursFrom)
    {
        $this->workHoursFrom = $workHoursFrom;
    
        return $this;
    }

    /**
     * Get workHoursFrom
     *
     * @return \DateTime 
     */
    public function getWorkHoursFrom()
    {
        return $this->workHoursFrom;
    }
    
    /**
     * Set workHoursTo
     *
     * @param \DateTime $workHoursTo
     * @return Restaurant
     */
    public function setWorkHoursTo($workHoursTo)
    {
        $this->workHoursTo = $workHoursTo;
    
        return $this;
    }

    /**
     * Get workHoursTo
     *
     * @return \DateTime 
     */
    public function getWorkHoursTo()
    {
        return $this->workHoursTo;
    }

    /**
     * Set categoryId
     *
     * @param Table\RestaurantBundle\Entity\RestaurantCategory $categoryId
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
     * @return Table\RestaurantBundle\Entity\RestaurantCategory
     */
    public function getCategoryId()
    {
        return $this->categoryId;
    }

    /**
     * Set kitchenId
     *
     * @param Table\RestaurantBundle\Entity\RestaurantKitchen $kitchenId
     * @return Property
     */
    public function setKitchenId($kitchenId)
    {
        $this->kitchenId = $kitchenId;

        return $this;
    }

    /**
     * Get kitchenId
     *
     * @return Table\RestaurantBundle\Entity\RestaurantKitchen
     */
    public function getKitchenId()
    {
        return $this->kitchenId;
    }

    /**
     * Get photo
     *
     * @return Application\Sonata\MediaBundle\Entity\Media 
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set photo
     *
     * @param Application\Sonata\MediaBundle\Entity\Media $photo
     * @return Property
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }
    
    /**
     * Get additionalServices
     *
     * @return Table\RestaurantBundle\Entity\RestaurantAdditionalServices[] 
     */
    public function getAdditionalServices()
    {
        return $this->additionalServices;
    }

    /**
     * Add additionalServices
     * 
     * @param Table\RestaurantBundle\Entity\RestaurantAdditionalServices $additionalServices
     */
    public function addAdditionalServices($additionalServices)
    {
        $this->additionalServices[] = $additionalServices;
    }

    /**
     * Set additionalServices
     *
     * @param Table\RestaurantBundle\Entity\RestaurantAdditionalServices $additionalServices
     * @return Restaurant
     */
    public function setAdditionalServices($additionalServices)
    {
        $this->additionalServices = $additionalServices;
    }

    /**
     * Remove additionalServices
     * 
     * @param Table\RestaurantBundle\Entity\RestaurantAdditionalServices $additionalServices
     */
    public function removeAdditionalServices($additionalServices) {
        $this->additionalServices->removeElement($additionalServices);
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        return strval($this->getName());
    }
}