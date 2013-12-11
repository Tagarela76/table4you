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
    
    public function __construct(Restaurant $restaurant, $container)
    {
        $this->floors = $restaurant->getFloors(); 
        // get big and small
        $provider = $container->get('sonata.media.pool')
                       ->getProvider($restaurant->getMapPhoto()->getProviderName());

        $formatSmall = $provider->getFormatName($restaurant->getMapPhoto(), "small");
        $formatBig = $provider->getFormatName($restaurant->getMapPhoto(), "big");
        $smallImageURL = $container->getParameter('site_host') . $provider->generatePublicUrl($restaurant->getMapPhoto(), $formatSmall);
        $bigImageURL = $container->getParameter('site_host') . $provider->generatePublicUrl($restaurant->getMapPhoto(), $formatBig);
        
        $photo = array(
            "small" => $smallImageURL,
            "big" => $bigImageURL
        );
        
        $this->mapPhoto = $photo;
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
