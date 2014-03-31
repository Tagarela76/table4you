<?php

namespace Table\RestaurantBundle\Entity\DTO;

use Table\RestaurantBundle\Entity\TableMap;

/**
 * DTO for TableMap
 *
 * @author masha
 */
class TableMapDTO
{
    private $id;
    private $imageURL;
    private $floor;
    private $hall;

    public function __construct(TableMap $tableMap, $container)
    {
        $this->id = $tableMap->getId();
        
        // Get Map photo
        $helper = $container->get('vich_uploader.templating.helper.uploader_helper');
        $imageURL = $container->getParameter('site_host') . 
                        $container->getParameter('base_folder_url') . 
                        $helper->asset($tableMap, 'file');

        $this->imageURL = $imageURL;
        
        $this->floor = $tableMap->getFloor();
        $this->hall = $tableMap->getHall();
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function getImageURL()
    {
        return $this->imageURL;
    }

    public function getFloor()
    {
        return $this->floor;
    }

    public function getHall()
    {
        return $this->hall;
    }

}

?>
