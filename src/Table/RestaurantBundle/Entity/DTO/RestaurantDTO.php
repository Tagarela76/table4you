<?php

namespace Table\RestaurantBundle\Entity\DTO;

use Table\RestaurantBundle\Entity\Restaurant;

/**
 * DTO for Restaurant
 *
 * @author masha
 */
class RestaurantDTO
{
    private $id;
    private $name;
    private $photo;
    private $categories;
    private $kitchens;
    private $address;
    private $longitude;
    private $latitude;
    private $additionalServices;
    private $schedule;

    public function __construct(Restaurant $restaurant, $container)
    {
        $this->id = $restaurant->getId();
        $this->name = $restaurant->getName();
        // get big and small
        $provider = $container->get('sonata.media.pool')
                       ->getProvider($restaurant->getPhoto()->getProviderName());

        $formatSmall = $provider->getFormatName($restaurant->getPhoto(), "small");
        $formatBig = $provider->getFormatName($restaurant->getPhoto(), "big");
        $smallImageURL = $container->getParameter('site_host') . $provider->generatePublicUrl($restaurant->getPhoto(), $formatSmall);
        $bigImageURL = $container->getParameter('site_host') . $provider->generatePublicUrl($restaurant->getPhoto(), $formatBig);
        
        $photo = array(
            "small" => $smallImageURL,
            "big" => $bigImageURL
        );
        
        $this->photo = $photo;
        
        // get categories id
        $this->categories = array();
        $categories = $restaurant->getCategories();
        foreach ($categories as $category) {
            $this->categories[] = $category->getId();
        }

        $kitchens = array();
        foreach($restaurant->getKitchens() as $kitchen) {
            $kitchens[] = $kitchen->getId();
        }
        
        $this->kitchens = $kitchens;
        
        $this->address = $restaurant->getCity() . ", " . 
                $restaurant->getStreet() . ", " . $restaurant->getHouse();
        
        $this->latitude = $restaurant->getLatitude();
        $this->longitude = $restaurant->getLongitude();
        
        $additionalServices = array();
        foreach($restaurant->getAdditionalServices() as $additionalService) {
            $additionalServices[] = $additionalService->getId();
        }
        $this->additionalServices = $additionalServices;
        
        // get Schedule
        $scheduleList = array();
        $restaurantSchedule = $restaurant->getRestaurantSchedule();
        foreach ($restaurantSchedule as $scheduleObj) {
            $schedule['dayFrom'] = $scheduleObj->getDayFromStr();
            $schedule['dayTo'] = $scheduleObj->getDayToStr();
            $schedule['timeFrom'] = $scheduleObj->getTimeFrom()->format('H:s');
            $schedule['timeTo'] = $scheduleObj->getTimeTo()->format('H:s');
            $scheduleList[] = $schedule;
        }

        $this->schedule = $scheduleList;
    }
    
    public function getId()
    {
        return $this->id;
    }
        
    public function getName()
    {
        return $this->name;
    }
    
    public function getPhoto()
    {
        return $this->photo;
    }
    
    public function getCategories()
    {
        return $this->categories;
    }

    public function getKitchens()
    {
        return $this->kitchens;
    }
    
    public function getAddress()
    {
        return $this->address;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function getAdditionalServices()
    {
        return $this->additionalServices;
    }
    
    public function getSchedule()
    {
        return $this->schedule;
    }

    public function setSchedule($schedule)
    {
        $this->schedule = $schedule;
    }


}

?>
