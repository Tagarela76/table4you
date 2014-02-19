<?php

namespace Table\RestaurantBundle\Controller;

use Table\MainBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FOS\UserBundle\Model\UserInterface;
use Table\RestaurantBundle\Entity\TableType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Application\Sonata\UserBundle\Entity\User;
use Table\RestaurantBundle\Entity\TableMap;
use Symfony\Component\HttpFoundation\Response;
use Table\RestaurantBundle\Entity\ActiveTable;

class TableDashboardController extends Controller
{

    /**
     * View TableType List
     * 
     * @Template()
     */
    public function viewTableTypeListAction()
    {
        // get Current user
        $user = $this->container->get('security.context')->getToken()->getUser();

        // Check if user auth in app
        if (!is_object($user) || !$user instanceof UserInterface) {
            // redirect on homepage
            return $this->redirect(
                            $this->generateUrl("table_main_homepage")
            );
        }
        $tableTypeList = $this->getTableTypeManager()->findAll();

        // assign base_url
        $baseUrl = $this->container->getParameter('base_folder_url');


        // we should now if user is super admin
        $isUserIsSuperAdmin = false;
        if (in_array(User::ROLE_SUPER_ADMIN, $user->getRoles())) {
            $isUserIsSuperAdmin = true;
        }
        // get restaurant list
        $restaurantList = $this->getRestaurantManager()->getEditorRestaurants($user->getId(), $isUserIsSuperAdmin);

        return array(
            'tableTypeList' => $tableTypeList,
            'baseUrl' => $baseUrl,
            'restaurantList' => $restaurantList
        );
    }

    /**
     * Create Map
     * 
     * @param int $restaurantId
     * 
     * @Template()
     */
    public function viewCreateMapAction($restaurantId)
    {
        // get Current user
        $user = $this->container->get('security.context')->getToken()->getUser();

        // Check if user auth in app
        if (!is_object($user) || !$user instanceof UserInterface) {
            // redirect on homepage
            return $this->redirect(
                            $this->generateUrl("table_main_homepage")
            );
        }

        // we should now if user is super admin
        $isUserIsSuperAdmin = false;
        if (in_array(User::ROLE_SUPER_ADMIN, $user->getRoles())) {
            $isUserIsSuperAdmin = true;
        }
        // get restaurant list
        $restaurantList = $this->getRestaurantManager()->getEditorRestaurants($user->getId(), $isUserIsSuperAdmin);
        if (!$restaurantId) {
            // set Restaurant id as first restaurant in list
            $restaurantId = $restaurantList[0]->getId();
        }
        // get Map list
        $tableMapList = $this->getTableMapManager()->findByRestaurant($restaurantId);
        
        // get map id
        $mapId = $this->getRequest()->query->get('mapId');
         
        if ($tableMapList) {
            if (is_null($mapId)) {
                // Get first
                $tableMapObj = $tableMapList[0];
                $mapId = $tableMapList[0]->getId();
            } else {
                $tableMapObj = $this->getTableMapManager()->findOneById($mapId);
            }
          
        } else {
            $tableMapObj = null;
        }
      
        // get Table Type list
        $tableTypeList = $this->getTableTypeManager()->findAll();
        // assign base_url
        $baseUrl = $this->container->getParameter('base_folder_url');

        return array(
            'restaurantList' => $restaurantList,
            'tableMapList' => $tableMapList,
            'tableTypeList' => $tableTypeList,
            'baseUrl' => $baseUrl,
            'restaurantId' => $restaurantId,
            'tableMapObj' => $tableMapObj,
            'mapId' => $mapId
        );
    }

    /**
     * Add new Table Types
     * 
     */
    public function updateTableTypeListAction()
    {
        // get Current user
        $user = $this->container->get('security.context')->getToken()->getUser();
        // Check if user auth in app
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException("Access denied");
        }
        // collect data
        $peopleCountArray = $this->getRequest()->request->get('peopleCount');
        $fileArray = $this->getRequest()->files->get('file');
        foreach ($peopleCountArray as $key => $peopleCount) {
            $tableType = new TableType();
            $tableType->setPeopleCount($peopleCount);
            $tableType->setFile($fileArray[$key]);

            $em = $this->getDoctrine()->getManager();
            $em->persist($tableType);
            $em->flush();
        }
        return $this->redirect(
                        $this->generateUrl("table_viewTableTypeList")
        );
    }

    /**
     * Update Table Type
     */
    public function editTableTypeAction()
    {
        // get Current user
        $user = $this->container->get('security.context')->getToken()->getUser();
        // Check if user auth in app
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException("Access denied");
        }
        // collect data
        $peopleCount = $this->getRequest()->request->get('peopleCount');
        $file = $this->getRequest()->files->get('file');
        $tableTypeId = $this->getRequest()->request->get('tableTypeId');

        // init table Type
        $tableType = $this->getTableTypeManager()->findOneById($tableTypeId);
        $tableType->setPeopleCount($peopleCount);
        // Update file if isset
        if (!is_null($file)) {
            $tableType->setFile($file);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($tableType);
        $em->flush();

        return $this->redirect(
                        $this->generateUrl("table_viewTableTypeList")
        );
    }

    /**
     * Delete Table Type
     */
    public function deleteTableTypeAction()
    {
        // get Current user
        $user = $this->container->get('security.context')->getToken()->getUser();
        // Check if user auth in app
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException("Access denied");
        }
        // get Table Type ID
        $tableTypeId = $this->getRequest()->request->get('tableTypeId');

        // init table Type
        $tableType = $this->getTableTypeManager()->findOneById($tableTypeId);

        // delete Table Type
        $em = $this->getDoctrine()->getManager();
        $em->remove($tableType);
        $em->flush();

        // refresh table Type list
        $tableTypeList = $this->getTableTypeManager()->findAll();

        // assign base_url
        $baseUrl = $this->container->getParameter('base_folder_url');
        return $this->render('TableRestaurantBundle:TableDashboard:tableTypeList.html.twig', array(
                    'tableTypeList' => $tableTypeList,
                    'baseUrl' => $baseUrl
        ));
    }

    /**
     * Add new Table Maps
     * 
     */
    public function updateTableMapAction()
    {
        // get Current user
        $user = $this->container->get('security.context')->getToken()->getUser();
        // Check if user auth in app
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException("Access denied");
        }
        // collect data
        $restaurantId = $this->getRequest()->request->get('restaurantId');
        $restaurant = $this->getRestaurantManager()->findOneById($restaurantId);
        $floorArray = $this->getRequest()->request->get('mapFloor');
        $fileArray = $this->getRequest()->files->get('mapFile');
        $mapHallArray = $this->getRequest()->request->get('mapHall');
        foreach ($floorArray as $key => $floor) {
            $tableMap = new TableMap();
            $tableMap->setRestaurant($restaurant);
            $tableMap->setFloor($floor);
            $tableMap->setFile($fileArray[$key]);

            if ($mapHallArray[$key] != "") {
                $tableMap->setHall($mapHallArray[$key]);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($tableMap);
            $em->flush();
        }
        return $this->redirect(
                        $this->generateUrl("table_viewCreateMap")
        );
    }
    
    /**
     * Delete Table Map
     */
    public function deleteTableMapAction()
    {
        // get Current user
        $user = $this->container->get('security.context')->getToken()->getUser();
        // Check if user auth in app
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException("Access denied");
        }
        // collect Data
        $tableMapId = $this->getRequest()->request->get('tableMapId');
        
        // init table Map
        $tableMap = $this->getTableMapManager()->findOneById($tableMapId);

        // delete Table Map
        $em = $this->getDoctrine()->getManager();
        $em->remove($tableMap);
        $em->flush();
        return new Response("success");
    }
    
    /**
     * Update Table Map
     */
    public function editTableMapAction()
    {
        // get Current user
        $user = $this->container->get('security.context')->getToken()->getUser();
        // Check if user auth in app
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException("Access denied");
        }
        // collect data
        $tableMapId = $this->getRequest()->request->get('tableMapId');
        $floor = $this->getRequest()->request->get('mapFloor');
        $file = $this->getRequest()->files->get('mapFile');
        $mapHall = $this->getRequest()->request->get('mapHall');

        // init table Map
        $tableMap = $this->getTableMapManager()->findOneById($tableMapId);
        $tableMap->setFloor($floor);
        if (!is_null($mapHall)) {
            $tableMap->setHall($mapHall);
        }
        // Update file if isset
        if (!is_null($file)) {
            $tableMap->setFile($file);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($tableMap);
        $em->flush();

        return $this->redirect(
                        $this->generateUrl("table_viewCreateMap")
        );
    }
    
    /**
     * Add Active Table
     */
    public function addActiveTableAction()
    {
        // get Current user
        $user = $this->container->get('security.context')->getToken()->getUser();
        // Check if user auth in app
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException("Access denied");
        }
        // collect data
        $tableNumber = $this->getRequest()->request->get('tableNumber');
        $tableTop = $this->getRequest()->request->get('tableTop');
        $tableLeft = $this->getRequest()->request->get('tableLeft');
        $mapId = $this->getRequest()->request->get('mapId');
        $tableTypeId = $this->getRequest()->request->get('tableType');
        
        // create new Active Table
        $tableMap = $this->getTableMapManager()->findOneById($mapId);
        $tableType = $this->getTableTypeManager()->findOneById($tableTypeId);
        $activeTable = new ActiveTable();
        $activeTable->setLeftPosition($tableLeft);
        $activeTable->setTableMap($tableMap);
        $activeTable->setTableNumber($tableNumber);
        $activeTable->setTableType($tableType);
        $activeTable->setTopPosition($tableTop);
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($activeTable);
        $em->flush();

        return $this->redirect(
                        $this->generateUrl("table_viewCreateMap")
        );
    }
    
    /**
     * 
     * Get Active Tables
     * 
     * @return string
     */
    public function loadActiveTablesAction()
    {
        // get Active Tabled list
        $mapId = $this->getRequest()->query->get('mapId');
        $activeTableList = $this->getActiveTableManager()->findByTableMap($mapId);
        $baseUrl = $this->container->getParameter('base_folder_url');

        $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
        $activeTables = array();
        foreach ($activeTableList as $activeTable) {
            if (!is_null($activeTable)) {
                $activeTableObj = array();
                $path = $helper->asset($activeTable->getTableType(), 'file');
                $activeTableObj['id'] = $activeTable->getId();
                $activeTableObj['src'] = $baseUrl . $path;
                $activeTableObj['left'] = $activeTable->getLeftPosition();
                $activeTableObj['top'] = $activeTable->getTopPosition();
                $activeTableObj['tableTypeId'] = $activeTable->getTableType()->getId();
                $activeTables[] = $activeTableObj;
            }
        }
        return $activeTables;
    }
    
    /**
     * view Table Order List
     * 
     * @param int $restaurantId
     * 
     * @Template()
     */
    public function viewTableOrderListAction($restaurantId)
    {
        // get Current user
        $user = $this->container->get('security.context')->getToken()->getUser();

        // Check if user auth in app
        if (!is_object($user) || !$user instanceof UserInterface) {
            // redirect on homepage
            return $this->redirect(
                            $this->generateUrl("table_main_homepage")
            );
        }

        // we should now if user is super admin
        $isUserIsSuperAdmin = false;
        if (in_array(User::ROLE_SUPER_ADMIN, $user->getRoles())) {
            $isUserIsSuperAdmin = true;
        }
        // get restaurant list
        $restaurantList = $this->getRestaurantManager()->getEditorRestaurants($user->getId(), $isUserIsSuperAdmin);
        if (!$restaurantId) {
            // set Restaurant id as first restaurant in list
            $restaurantId = $restaurantList[0]->getId();
        }
        // get Map list
        $tableMapList = $this->getTableMapManager()->findByRestaurant($restaurantId);
        
        // get map id
        $mapId = $this->getRequest()->query->get('mapId');
         
        if ($tableMapList) {
            if (is_null($mapId)) {
                // Get first
                $tableMapObj = $tableMapList[0];
                $mapId = $tableMapList[0]->getId();
            } else {
                $tableMapObj = $this->getTableMapManager()->findOneById($mapId);
            }
          
        } else {
            $tableMapObj = null;
        }

        // assign base_url
        $baseUrl = $this->container->getParameter('base_folder_url');

        return array(
            'restaurantList' => $restaurantList,
            'tableMapList' => $tableMapList,
            'baseUrl' => $baseUrl,
            'restaurantId' => $restaurantId,
            'tableMapObj' => $tableMapObj,
            'mapId' => $mapId
        );
    }
    
    /**
     * view Active Table Order List
     * 
     * 
     * @Template()
     */
    public function viewActiveTableOrderListAction()
    {
        // get table id
        $tableId = $this->getRequest()->query->get('tableId');
        // init table
        $activeTable = $this->getActiveTableManager()->findOneById($tableId);
        //get Order list
        $tableOrderList = $this->getActiveTableOrderManager()->findByActiveTable($tableId);
        return array(
            'activeTable' => $activeTable,
            'tableOrderList' => $tableOrderList
        );
    }

}
