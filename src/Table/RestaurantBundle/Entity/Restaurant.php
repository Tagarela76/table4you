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
     * @ORM\Column(name="house", type="string", length=50, nullable=true)
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
     * @var string 
     */
    protected $clearAddress;
    
    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float", nullable=true)
     */
    protected $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float", nullable=true)
     */
    protected $longitude;
    
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
    
    /**
     * @ORM\OneToMany(targetEntity="TableMap", mappedBy="restaurant", orphanRemoval=true, cascade={"persist", "remove"})
     */
    protected $tableMaps;
    
    /**
     * @var Application\Sonata\UserBundle\Entity\User $editor
     * 
     * @ORM\ManyToOne(targetEntity="Application\Sonata\UserBundle\Entity\User", inversedBy="restaurants")
     * @ORM\JoinColumn(name="editor_id", referencedColumnName="id", onDelete="CASCADE")
     * */
    private $editor;
    
    /**
     * @var string
     *
     * @ORM\Column(name="linkInAdminDashboard", type="string", length=250, nullable=true)
     */
    private $linkInAdminDashboard;
    
    public function __construct()
    {
        $this->additionalServices = new ArrayCollection();
        $this->kitchens = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->restaurantSchedule = new ArrayCollection();
        $this->additionalPhotos = new ArrayCollection();
        $this->additionalMenuPhotos = new ArrayCollection();
        $this->news = new ArrayCollection();
        $this->tableMaps = new ArrayCollection();
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
            $address = $this->getCity()->getName() . ", " .
                    $this->getStreet() . ", " .
                    $this->getHouse();
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
     * Get clearAddress
     * 
     * @return string
     */
    public function getClearAddress()
    {
        if (is_null($this->clearAddress)) {
            $clearAddress = $this->getHouse() . " " . $this->getStreet() . " " .
                    $this->getCity()->getName(); 
            $this->setClearAddress($clearAddress);
            return $clearAddress;
        } else {
            return $this->clearAddress;
        }
    }

    /**
     * 
     * @param string $clearAddress
     */
    public function setClearAddress($clearAddress)
    {
        $this->clearAddress = $clearAddress;
    }
    
    /**
     * Calculate Latitude
     * 
     * @return float
     */
    public function calculateLatitude()
    {
        if (is_null($this->getAddress())) {
            return false;
        } 

        $params = array(
            'geocode' => $this->getAddress(),
            'format'  => 'json',
            'results' => 1
        );
        $response = json_decode(file_get_contents('http://geocode-maps.yandex.ru/1.x/?' . http_build_query($params, '', '&')));

        if ($response->response->GeoObjectCollection->metaDataProperty->GeocoderResponseMetaData->found > 0) {
            $output = $response->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos;
        } else {
            return false;
        }
        $output = explode(" ", $output);
        $latitude = $output[1];
        return $latitude; 
    }

    /**
     * Get Latitude
     *
     * @return float 
     */
    public function getLatitude()
    {
        if (is_null($this->latitude)){
            $latitude = $this->calculateLatitude();
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
     * Calculate longitude
     * 
     * @return float
     */
    public function calculateLongitude()
    {
        if (is_null($this->getAddress())) {
            return false;
        }

        $params = array(
            'geocode' => $this->getAddress(),
            'format'  => 'json', 
            'results' => 1
        );
        $response = json_decode(file_get_contents('http://geocode-maps.yandex.ru/1.x/?' . http_build_query($params, '', '&')));

        if ($response->response->GeoObjectCollection->metaDataProperty->GeocoderResponseMetaData->found > 0) {
            $output = $response->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos;
        } else {
            return false;
        }
        $output = explode(" ", $output);
        $longitude = $output[0];
        return $longitude;
    }

    /**
     * Get Longitude
     *
     * @return float 
     */
    public function getLongitude()
    {
        if (is_null($this->longitude)) {
            $longitude = $this->calculateLongitude();
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
     * Get tableMaps
     *
     * @return Table\RestaurantBundle\Entity\TableMap[] 
     */
    public function getTableMaps()
    {
        return $this->tableMaps;
    }

    /**
     * Set tableMaps
     *
     * @param Table\RestaurantBundle\Entity\TableMap[] $tableMaps
     * @return Restaurant
     */
    public function setTableMaps($tableMaps)
    {
        $this->tableMaps = $tableMaps;
    }
    
    /**
     * Set editor
     *
     * @param Application\Sonata\UserBundle\Entity\User
     * @return Restaurant
     */
    public function setEditor($editor)
    {
        $this->editor = $editor;

        return $this;
    }

    /**
     * Get editor
     *
     * @return Application\Sonata\UserBundle\Entity\User
     */
    public function getEditor()
    {
        return $this->editor;
    }
    
    /**
     * Set linkInAdminDashboard
     *
     * @param string $linkInAdminDashboard
     * @return Restaurant
     */
    public function setLinkInAdminDashboard($linkInAdminDashboard)
    {
        $this->linkInAdminDashboard = $linkInAdminDashboard;

        return $this;
    }

    /**
     * Get linkInAdminDashboard
     *
     * @return string 
     */
    public function getLinkInAdminDashboard()
    {
        return $this->linkInAdminDashboard;
    }
    
    /**
     * @return string
     */
    public function __toString()
    {
        return strval($this->getName());
    }

}