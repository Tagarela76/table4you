<?php

namespace Table\RestaurantBundle\Entity\DTO;

use Table\RestaurantBundle\Entity\Restaurant;

/**
 * DTO for Reserve Information
 *
 * @author masha
 */
class ReserveInfoDTO
{
    private $floors;
    private $mapPhoto;
    
    public function __construct(Restaurant $restaurant)
    {
        $this->floors = $restaurant->getFloors(); 
        $this->mapPhoto = $restaurant->getMapPhoto();
    }
    
    public function getFloors()
    {
        return $this->floors;
    }
        
    public function getMapPhoto()
    {
        return $this->mapPhoto;
    }

}

?>
