<?php

namespace Table\RestaurantBundle\Entity\DTO;

use Table\RestaurantBundle\Entity\ActiveTable;

/**
 * DTO for ActiveTable
 *
 * @author masha
 */
class ActiveTableDTO
{
    private $id;
    private $tableTypePicture;
    private $tableNumber;
    private $topPosition;
    private $leftPosition;
    private $angle;

    public function __construct(ActiveTable $activeTable, $container)
    {
        $this->id = $activeTable->getId();
        
        // Get table type photo
        $helper = $container->get('vich_uploader.templating.helper.uploader_helper');
        $originTableTypePicture = $container->getParameter('site_host') . 
                        $container->getParameter('base_folder_url') . 
                        $helper->asset($activeTable->getTableType(), 'file');
        $bigTableTypePicture = str_replace($activeTable->getTableType()->getFileName(), $activeTable->getTableType()->getBigFileName(), $originTableTypePicture);
        $tableTypePicture = array(
            "origin" => $originTableTypePicture,
            "big" => $bigTableTypePicture
        );        
        $this->tableTypePicture = $tableTypePicture;
        
        $this->tableNumber = $activeTable->getTableNumber();
        $this->topPosition = $activeTable->getTopPosition();
        $this->leftPosition = $activeTable->getLeftPosition();
        $this->angle = $activeTable->getAngle();
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function getTableTypePicture()
    {
        return $this->tableTypePicture;
    }

    public function getTableNumber()
    {
        return $this->tableNumber;
    }

    public function getTopPosition()
    {
        return $this->topPosition;
    }

    public function getLeftPosition()
    {
        return $this->leftPosition;
    }

    public function getAngle()
    {
        return $this->angle;
    }

}

?>
