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
    

    public function __construct(ActiveTable $activeTable, $container)
    {
        $this->id = $activeTable->getId();
        
        // Get table type photo
        $helper = $container->get('vich_uploader.templating.helper.uploader_helper');
        $tableTypePicture = $container->getParameter('site_host') . 
                        $container->getParameter('base_folder_url') . 
                        $helper->asset($activeTable->getTableType(), 'file');

        $this->tableTypePicture = $tableTypePicture;
        
        $this->tableNumber = $activeTable->getTableNumber();
        $this->topPosition = $activeTable->getTopPosition();
        $this->leftPosition = $activeTable->getLeftPosition();
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

}

?>
