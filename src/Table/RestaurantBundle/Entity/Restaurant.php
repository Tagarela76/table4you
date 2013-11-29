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
     * @var Table\RestaurantBundle\Entity\City $city
     * 
     * @ORM\ManyToOne(targetEntity="Table\RestaurantBundle\Entity\City")
     * @ORM\JoinColumn(name="city", referencedColumnName="id")
     *
     * */
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
     * @ORM\ManyToMany(targetEntity="Table\RestaurantBundle\Entity\RestaurantKitchen")
     * @ORM\JoinTable(name="restaurant2kitchen",
     *   joinColumns={@ORM\JoinColumn(name="restaurant_id", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="kitchen_id", referencedColumnName="id")}
     * )
     */
    private $kitchens;

    /**
     * @ORM\ManyToMany(targetEntity="Table\RestaurantBundle\Entity\RestaurantCategory")
     * @ORM\JoinTable(name="restaurant2category",
     *   joinColumns={@ORM\JoinColumn(name="restaurant_id", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")}
     * )
     */
    private $categories;

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
    
    /**
     *
     * @var string 
     */
    protected $address;
    
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
     * @var Application\Sonata\MediaBundle\Entity\Media $mapPhoto
     * 
     * @ORM\ManyToOne(targetEntity="Application\Sonata\MediaBundle\Entity\Media")
     * @ORM\JoinColumn(name="map_photo", referencedColumnName="id")
     *
     * @Assert\NotBlank
     * */
    private $mapPhoto;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="floors", type="integer")
     */
    private $floors;
    
    /**
     * @var float
     *
     * @ORM\Column(name="rating", type="float", options={"default" = 0}, nullable=true)
     */
    private $rating;
    
    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", nullable=true)
     */
    private $email;
    
    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", nullable=true)
     */
    private $phone;
    
    /**
     * @ORM\OneToMany(targetEntity="RestaurantSchedule", mappedBy="restaurant", orphanRemoval=true, cascade={"persist", "remove"})
     */
    protected $restaurantSchedule;
    
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", nullable=true, length=8000 )
     */
    private $description;
    
    /**
     * @ORM\OneToMany(targetEntity="RestaurantAdditionalPhoto", mappedBy="restaurant", orphanRemoval=true, cascade={"persist", "remove"})
     */
    protected $additionalPhotos;

    /**
     * @ORM\OneToMany(targetEntity="RestaurantMenuPhoto", mappedBy="restaurant", orphanRemoval=true, cascade={"persist", "remove"})
     */
    protected $additionalMenuPhotos;
    
    /**
     * @ORM\OneToMany(targetEntity="News", mappedBy="restaurant", orphanRemoval=true, cascade={"persist", "remove"})
     */
    protected $news;
    
    public function __construct()
    {
        $this->additionalServices = new ArrayCollection();
        $this->kitchens = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->restaurantSchedule = new ArrayCollection();
        $this->additionalPhotos = new ArrayCollection();
        $this->additionalMenuPhotos = new ArrayCollection();
        $this->news = new ArrayCollection();
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
     * Set id
     *
     * @param integer $id
     * @return Restaurant
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * @param Table\RestaurantBundle\Entity\City $city
     * @return Restaurant
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get City
     *
     * @return Table\RestaurantBundle\Entity\City
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
     * Add categories
     * 
     * @param Table\RestaurantBundle\Entity\RestaurantCategory $categories
     */
    public function addCategories($categories)
    {
        $this->categories[] = $categories;
    }


    /**
     * Remove categories
     * 
     * @param Table\RestaurantBundle\Entity\RestaurantCategory $categories
     */
    public function removecategories($categories)
    {
        $this->categories->removeElement($categories);
    }
    
    /**
     * Set categories
     *
     * @param Table\RestaurantBundle\Entity\RestaurantCategory[] $categories
     * @return Restaurant
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * Get categories
     *
     * @return Table\RestaurantBundle\Entity\RestaurantCategory[]
     */
    public function getCategories()
    {
        return $this->categories;
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
     * Get address
     * 
     * @return string
     */
    public function getAddress()
    {
        if (is_null($this->address)) {
            $address = $this->getHouse() . " " . $this->getStreet() . " " .
                    $this->getCity()->getName(); 
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
     * Get mapPhoto
     *
     * @return Application\Sonata\MediaBundle\Entity\Media 
     */
    public function getMapPhoto()
    {
        return $this->mapPhoto;
    }

    /**
     * Set mapPhoto
     *
     * @param Application\Sonata\MediaBundle\Entity\Media $mapPhoto
     * @return Restaurant
     */
    public function setMapPhoto($mapPhoto)
    {
        $this->mapPhoto = $mapPhoto;
    }
    
    /**
     * Set floors
     *
     * @param integer $floors
     * @return Restaurant
     */
    public function setFloors($floors)
    {
        $this->floors = $floors;
    
        return $this;
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
     * Set rating
     *
     * @param integer $rating
     * @return Restaurant
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    
        return $this;
    }

    /**
     * Get rating
     *
     * @return float 
     */
    public function getRating()
    {
        return $this->rating;
    }
    
    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }
    
    /**
     * Set email
     *
     * @param string $email
     * @return Restaurant
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }
    
    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }
    
    /**
     * Set phone
     *
     * @param string $phone
     * @return Restaurant
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }
    
    /**
     * Get restaurantSchedule
     *
     * @return Table\RestaurantBundle\Entity\RestaurantSchedule[] 
     */
    public function getRestaurantSchedule()
    {
        return $this->restaurantSchedule;
    }

    /**
     * Set restaurantSchedule
     *
     * @param Table\RestaurantBundle\Entity\RestaurantSchedule $restaurantSchedule
     * @return Restaurant
     */
    public function setRestaurantSchedule($restaurantSchedule)
    {
        $this->restaurantSchedule = $restaurantSchedule;
    }
    

    /**
     * Add restaurantSchedule
     * 
     * @param Table\RestaurantBundle\Entity\RestaurantSchedule $restaurantSchedule
     */
    public function addRestaurantSchedule($restaurantSchedule)
    {
        $this->restaurantSchedule[] = $restaurantSchedule;
    }

    /**
     * Remove additionalIrestaurantSchedulemages
     * 
     * @param Table\RestaurantBundle\Entity\RestaurantSchedule $restaurantSchedule
     */
    public function removeRestaurantSchedule($restaurantSchedule)
    {
        $this->restaurantSchedule->removeElement($restaurantSchedule);
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
     * Set description
     *
     * @param string $description
     * @return Restaurant
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }
    
    /**
     * Get additionalPhotos
     *
     * @return Table\RestaurantBundle\Entity\RestaurantAdditionalPhoto[] 
     */
    public function getAdditionalPhotos()
    {
        return $this->additionalPhotos;
    }

    /**
     * Set additionalPhotos
     *
     * @param Table\RestaurantBundle\Entity\RestaurantAdditionalPhoto $additionalPhotos
     * @return Restaurant
     */
    public function setAdditionalPhotos($additionalPhotos)
    {
        $this->additionalPhotos = $additionalPhotos;
    }
    

    /**
     * Add additionalPhotos
     * 
     * @param Table\RestaurantBundle\Entity\RestaurantAdditionalPhoto $additionalPhotos
     */
    public function addAdditionalPhotos($additionalPhotos)
    {
        $this->additionalPhotos[] = $additionalPhotos;
    }

    /**
     * Remove additionalPhotos
     * 
     * @param Table\RestaurantBundle\Entity\RestaurantAdditionalPhoto $additionalPhotos
     */
    public function removeAdditionalPhotos($additionalPhotos)
    {
        $this->additionalPhotos->removeElement($additionalPhotos);
    }
    
    /**
     * Get additionalMenuPhotos
     *
     * @return Table\RestaurantBundle\Entity\RestaurantMenuPhoto[] 
     */
    public function getAdditionalMenuPhotos()
    {
        return $this->additionalMenuPhotos;
    }

    /**
     * Set additionalMenuPhotos
     *
     * @param Table\RestaurantBundle\Entity\RestaurantMenuPhoto $additionalMenuPhotos
     * @return Restaurant
     */
    public function setAdditionalMenuPhotos($additionalMenuPhotos)
    {
        $this->additionalMenuPhotos = $additionalMenuPhotos;
    }
    

    /**
     * Add additionalMenuPhotos
     * 
     * @param Table\RestaurantBundle\Entity\RestaurantMenuPhoto $additionalMenuPhotos
     */
    public function addAdditionalMenuPhotos($additionalMenuPhotos)
    {
        $this->additionalMenuPhotos[] = $additionalMenuPhotos;
    }

    /**
     * Remove additionalMenuPhotos
     * 
     * @param Table\RestaurantBundle\Entity\RestaurantMenuPhoto $additionalMenuPhotos
     */
    public function removeAdditionalMenuPhotos($additionalMenuPhotos)
    {
        $this->additionalMenuPhotos->removeElement($additionalMenuPhotos);
    }
    
    /**
     * Get news
     *
     * @return Table\RestaurantBundle\Entity\News[] 
     */
    public function getNews()
    {
        return $this->news;
    }

    /**
     * Set news
     *
     * @param Table\RestaurantBundle\Entity\News $news
     * @return Restaurant
     */
    public function setNews($news)
    {
        $this->news = $news;
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        return strval($this->getName());
    }

}