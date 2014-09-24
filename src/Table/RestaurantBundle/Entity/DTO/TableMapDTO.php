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
    private $image;
    private $floor;
    private $hall;

    public function __construct(TableMap $tableMap, $container)
    {
        $this->id = $tableMap->getId();
        
        // Get Map photo
        $helper = $container->get('vich_uploader.templating.helper.uploader_helper');
        $originImage = $container->getParameter('site_host') . 
                        $container->getParameter('base_folder_url') . 
                        $helper->asset($tableMap, 'table_map');
        $bigImage = str_replace($tableMap->getFileName(), $tableMap->getBigFileName(), $originImage);
        
        if (@getimagesize($bigImage)) {
            $this->image = $bigImage;
        } else {
            $this->image = "";
        }
        
        $this->floor = $tableMap->getFloor();
        $this->hall = $tableMap->getHall();
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function getFloor()
    {
        return $this->floor;
    }

    public function getHall()
    {
        return $this->hall;
    }
    
    public function getImage()
    {
        return $this->image;
    }

}

?>
