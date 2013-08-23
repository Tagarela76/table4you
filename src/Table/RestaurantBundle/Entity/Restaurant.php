<?php

namespace Table\RestaurantBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Restaurant
 *
 * @ORM\Table(name="restaurant")
 * @ORM\Entity(repositoryClass="Table\RestaurantBundle\Entity\Repository\RestaurantRepository")
 */
class Restaurant
{
    const PER_PAGE_COUNT = 10;

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
     * @ORM\ManyToMany(targetEntity="Table\RestaurantBundle\Entity\RestaurantKitchen")
     * @ORM\JoinTable(name="restaurant2kitchen",
     *   joinColumns={@ORM\JoinColumn(name="restaurant_id", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="kitchen_id", referencedColumnName="id")}
     * )
     */
    private $kitchens;

    /**
     * @var Table\RestaurantBundle\Entity\RestaurantCategory $category
     * 
     * @ORM\ManyToOne(targetEntity="RestaurantCategory", inversedBy="restaurants")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * */
    private $category;

    /**
     * @var Application\Sonata\MediaBundle\Entity\Media $photo
     * 
     * @ORM\ManyToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media")
     * @ORM\JoinColumn(name="photo", referencedColumnName="id")
     *
     * @Assert\NotBlank
     * */
    private $photo;

    /**
     * @ORM\ManyToMany(targetEntity="Table\RestaurantBundle\Entity\RestaurantAdditionalService")
     * @ORM\JoinTable(name="restaurant2additional_service",
     *   joinColumns={@ORM\JoinColumn(name="restaurant_id", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="additional_service_id", referencedColumnName="id")}
     * )
     */
    protected $additionalServices;

    public function __construct()
    {
        $this->additionalServices = new ArrayCollection();
        $this->kitchens = new ArrayCollection();
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
     * Set category
     *
     * @param Table\RestaurantBundle\Entity\RestaurantCategory $category
     * @return Rest
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return Table\RestaurantBundle\Entity\RestaurantCategory
     */
    public function getCategory()
    {
        return $this->category;
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
     * @return Restaurant
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    /**
     * Get additionalServices
     *
     * @return Table\RestaurantBundle\Entity\RestaurantAdditionalService[] 
     */
    public function getAdditionalServices()
    {
        return $this->additionalServices;
    }

    /**
     * Add additionalServices
     * 
     * @param Table\RestaurantBundle\Entity\RestaurantAdditionalService $additionalServices
     */
    public function addAdditionalServices($additionalServices)
    {
        $this->additionalServices[] = $additionalServices;
    }

    /**
     * Set additionalServices
     *
     * @param Table\RestaurantBundle\Entity\RestaurantAdditionalService $additionalServices
     * @return Restaurant
     */
    public function setAdditionalServices($additionalServices)
    {
        $this->additionalServices = $additionalServices;
    }

    /**
     * Remove additionalServices
     * 
     * @param Table\RestaurantBundle\Entity\RestaurantAdditionalService $additionalServices
     */
    public function removeAdditionalServices($additionalServices)
    {
        $this->additionalServices->removeElement($additionalServices);
    }
    
    /**
     * Get kitchens
     *
     * @return Table\RestaurantBundle\Entity\RestaurantKitchen[] 
     */
    public function getKitchens()
    {
        return $this->kitchens;
    }

    /**
     * Add kitchens
     * 
     * @param Table\RestaurantBundle\Entity\RestaurantKitchen $kitchens
     */
    public function addKitchens($kitchens)
    {
        $this->kitchens[] = $kitchens;
    }

    /**
     * Set kitchens
     *
     * @param Table\RestaurantBundle\Entity\RestaurantKitchen $kitchens
     * @return Restaurant
     */
    public function setKitchens($kitchens)
    {
        $this->kitchens = $kitchens;
    }

    /**
     * Remove kitchens
     * 
     * @param Table\RestaurantBundle\Entity\RestaurantKitchen $kitchens
     */
    public function removeKitchens($kitchens)
    {
        $this->kitchens->removeElement($kitchens);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return strval($this->getName());
    }

}