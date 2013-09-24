<?php

namespace Table\RestaurantBundle\Entity\DTO;

use Table\RestaurantBundle\Entity\Restaurant;

/**
 * DTO for Restaurant
 *
 * @author masha
 */
class GeoSearchDTO
{
    private $id;
    private $name;
    private $longitude;
    private $latitude;
    
    public function __construct(Restaurant $restaurant)
    {
        $this->id = $restaurant->getId();
        $this->name = $restaurant->getName(); 
        $this->latitude = $restaurant->getLatitude();
        $this->longitude = $restaurant->getLongitude();
    }
    
    public function getId()
    {
        return $this->id;
    }
        
    public function getName()
    {
        return $this->name;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

}

?>
