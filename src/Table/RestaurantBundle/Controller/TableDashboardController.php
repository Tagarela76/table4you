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
use Table\RestaurantBundle\Entity\ActiveTableOrder;
use Table\RestaurantBundle\Form\Type\ActiveTableOrderForm4AdminType;
use Application\Sonata\UserBundle\Form\Type\RestRegistrationFormType;
use Symfony\Component\HttpFoundation\Request;

class TableDashboardController extends Controller
{
    /**
     * View Active Table Order Filter
     * 
     * @Template()
     */
    public function viewActiveTableOrderFilterAction()
    {
        return array();
    }
    
    /**
     * Refreash Booked Tables list
     * 
     * @Template()
     */
    public function refreshBookedTableListAction()
    {
        // Get map id
        $mapId = $this->getRequest()->query->get('mapId');
        
        // init map obj
        $tableMap = $this->getTableMapManager()->findOneById($mapId);
        // Get Filter Date
        $filterDate = $this->getRequest()->query->get('filterDate');
        // Get Filter Time
        $filterTime = $this->getRequest()->query->get('filterTime');
        
        // transform to date time
        //$dateTime = new \DateTime($filterDate . " " . $filterTime, new \DateTimeZone(ActiveTableOrder::RESERVE_TIMEZONE));
        $dateTime = new \DateTime($filterDate . " " . $filterTime);
        
        // get Current user
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            // redirect on homepage
            return $this->redirect(
                $this->generateUrl("table_main_homepage")
            );
        }

        // Get Active Tables List
        $activeTableList = $this->getActiveTableManager()->findByTableMap($mapId);
       
        // get Booked Tables 
        $bookedTables = $this->getActiveTableOrderManager()->getBookedTablesByRestaurant($tableMap->getRestaurant()->getId(), $dateTime);  
        
        // assign base_url
        $baseUrl = $this->container->getParameter('base_folder_url');
        
        return $this->render('TableRestaurantBundle:TableDashboard:viewRefreshedBookedTableList.html.twig', array(
            'baseUrl' => $baseUrl,
            'activeTableList' => $activeTableList,
            'bookedTables' => $bookedTables
        ));
    }

    /**
     * Get user manager
     * 
     * @return UserManager
     */
    protected function getUserManager()
    {
        return $this->get('fos_user.user_manager');
    }
    
    protected function getUserMailer() 
    {
        return $this->container->get('fos_user.mailer');
    }
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
        // we should now if user is super admin
        $isUserIsSuperAdmin = false;
        if (in_array(User::ROLE_SUPER_ADMIN, $user->getRoles())) {
            $isUserIsSuperAdmin = true;
        }
        // Only admin has access to this area, so...
        if (!$isUserIsSuperAdmin) {
            // redirect on homepage
            return $this->redirect(
                $this->generateUrl("table_main_homepage")
            );
        }
        
        $tableTypeList = $this->getTableTypeManager()->findAll();

        // assign base_url
        $baseUrl = $this->container->getParameter('base_folder_url');

        // get restaurant list
        $restaurantList = $this->getRestaurantManager()->getEditorRestaurants($user->getId(), $isUserIsSuperAdmin);
        if (empty($restaurantList)) {
            // redirect on homepage
            return $this->redirect(
                $this->generateUrl("table_main_homepage")
            );
        }
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
        if (empty($restaurantList)) {
            // redirect on homepage
            return $this->redirect(
                $this->generateUrl("table_main_homepage")
            );
        }
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

        // Get Active Tables List
        $activeTableList = $this->getActiveTableManager()->findByTableMap($mapId);
        
        return array(
            'restaurantList' => $restaurantList,
            'tableMapList' => $tableMapList,
            'tableTypeList' => $tableTypeList,
            'baseUrl' => $baseUrl,
            'restaurantId' => $restaurantId,
            'tableMapObj' => $tableMapObj,
            'mapId' => $mapId,
            'activeTableList' => $activeTableList,
            'isUserIsSuperAdmin' => $isUserIsSuperAdmin
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
            
            // resize image
            $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
            $imagePath = getcwd() . $helper->asset($tableType, 'table_type');
            
            // check if file exist
            if (file_exists($imagePath) && getimagesize($imagePath)) {
                // resize (BIG versio)
                // Get bigImagePath
                $bigImagePath = str_replace($tableType->getFileName(), $tableType->getBigFileName(), $imagePath);
                
                $resizedBigImage = new \abeautifulsite\SimpleImage($imagePath);
                $resizedBigImage->resize(TableType::IMAGE_WIDTH_BIG, TableType::IMAGE_HEIGHT_BIG)->save($bigImagePath);
                
                $resizedImage = new \abeautifulsite\SimpleImage($imagePath);
                $resizedImage->resize(TableType::IMAGE_WIDTH, TableType::IMAGE_HEIGHT)->save($imagePath);
            } else {
                // delete entity
                $em->remove($tableType);
                $em->flush();
            }
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
        if (!is_null($file)) {
            // resize image
            $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
            $imagePath = getcwd() . $helper->asset($tableType, 'table_type');
            
            // check if file exist
            if (file_exists($imagePath) && getimagesize($imagePath)) {
                // resize (BIG versio)
                // Get bigImagePath
                $bigImagePath = str_replace($tableType->getFileName(), $tableType->getBigFileName(), $imagePath);
                
                $resizedBigImage = new \abeautifulsite\SimpleImage($imagePath);
                $resizedBigImage->resize(TableType::IMAGE_WIDTH_BIG, TableType::IMAGE_HEIGHT_BIG)->save($bigImagePath);
                
                $resizedImage = new \abeautifulsite\SimpleImage($imagePath);
                $resizedImage->resize(TableType::IMAGE_WIDTH, TableType::IMAGE_HEIGHT)->save($imagePath);
            } else {
                // delete entity
               // $em->remove($tableType);
               // $em->flush();
            }
        }

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
            // resize image
            $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
            $imagePath = getcwd() . $helper->asset($tableMap, 'table_map');
            
            // check if file exist
            if (file_exists($imagePath) && getimagesize($imagePath)) {
                // resize (BIG versio)
                // Get bigImagePath
                $bigImagePath = str_replace($tableMap->getFileName(), $tableMap->getBigFileName(), $imagePath);
                
                $resizedBigImage = new \abeautifulsite\SimpleImage($imagePath);
                $resizedBigImage->resize(TableMap::IMAGE_WIDTH_BIG, TableMap::IMAGE_HEIGHT_BIG)->save($bigImagePath);
                
                $resizedImage = new \abeautifulsite\SimpleImage($imagePath);
                $resizedImage->resize(TableMap::IMAGE_WIDTH, TableMap::IMAGE_HEIGHT)->save($imagePath);
            } else {
                // delete entity
                $em->remove($tableMap);
                $em->flush();
            }
        }
        return $this->redirect(
            $this->generateUrl(
                "table_viewCreateMap", array(
                    "restaurantId" => $restaurant->getId()
                ))
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
        
        if (!is_null($file)) {
            // resize image
            $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
            $imagePath = getcwd() . $helper->asset($tableMap, 'table_map');
            
            // check if file exist
            if (file_exists($imagePath) && getimagesize($imagePath)) {
                // resize (BIG versio)
                // Get bigImagePath
                $bigImagePath = str_replace($tableMap->getFileName(), $tableMap->getBigFileName(), $imagePath);
                
                $resizedBigImage = new \abeautifulsite\SimpleImage($imagePath);
                $resizedBigImage->resize(TableMap::IMAGE_WIDTH_BIG, TableMap::IMAGE_HEIGHT_BIG)->save($bigImagePath);
                
                $resizedImage = new \abeautifulsite\SimpleImage($imagePath);
                $resizedImage->resize(TableMap::IMAGE_WIDTH, TableMap::IMAGE_HEIGHT)->save($imagePath);
            } else {
                // delete entity
               // $em->remove($tableMap);
               // $em->flush();
            }
        }

        return $this->redirect(
            $this->generateUrl(
                "table_viewCreateMap", array(
                    "restaurantId" => $tableMap->getRestaurant()->getId()
                )) . "?mapId={$tableMapId}"
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
            $this->generateUrl(
                "table_viewCreateMap", array(
                    "restaurantId" => $tableMap->getRestaurant()->getId()
                )) . "?mapId={$mapId}"
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
                $path = $helper->asset($activeTable->getTableType(), 'table_type');
                $activeTableObj['id'] = $activeTable->getId();
                $activeTableObj['src'] = $baseUrl . $path;
                $activeTableObj['left'] = $activeTable->getLeftPosition();
                $activeTableObj['top'] = $activeTable->getTopPosition();
                $activeTableObj['tableTypeId'] = $activeTable->getTableType()->getId();
                $activeTableObj['tableNumber'] = $activeTable->getTableNumber();
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
        if (empty($restaurantList)) {
            // redirect on homepage
            return $this->redirect(
                $this->generateUrl("table_main_homepage")
            );
        }
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
        
        // Get Active Tables List
        $activeTableList = $this->getActiveTableManager()->findByTableMap($mapId);
        //  Booked Tables (empty array for first init)
        $bookedTables = array(); 

        return array(
            'restaurantList' => $restaurantList,
            'tableMapList' => $tableMapList,
            'baseUrl' => $baseUrl,
            'restaurantId' => $restaurantId,
            'tableMapObj' => $tableMapObj,
            'mapId' => $mapId,
            'activeTable' => null,
            'tableOrderList' => null,
            'activeTableList' => $activeTableList,
            'bookedTables' => $bookedTables
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
        // Can we reserve it?
        $acceptReserve = $this->getRequest()->query->get('acceptReserve');
        // init table
        $activeTable = $this->getActiveTableManager()->findOneById($tableId);
        //get Order list
        $tableOrderList = $this->getActiveTableOrderManager()->getActiveTableOrderHistory($tableId);
    //var_dump($tableOrderList[0]->getRestaurant()); die();    
        //init form for table reserve
        $activeTableOrder = new ActiveTableOrder();
        $form = $this->createForm(new ActiveTableOrderForm4AdminType(), $activeTableOrder);
        
        return array(
            'activeTable' => $activeTable,
            'tableOrderList' => $tableOrderList,
            'form' => $form,
            'acceptReserve' => $acceptReserve
        );
    }
    
    /**
     * Delete Table Oreder
     */
    public function deleteActiveTableOrderAction()
    {
        // get Current user
        $user = $this->container->get('security.context')->getToken()->getUser();
        // Check if user auth in app
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException("Access denied");
        }
        // get Table Type ID
        $tableOrderId = $this->getRequest()->request->get('tableOrderId');

        // init table Order
        $tableOrder = $this->getActiveTableOrderManager()->findOneById($tableOrderId);
        // Reject Order
        $tableOrder->setStatus(ActiveTableOrder::ORDER_REJECT_STATUS_CODE);
        $em = $this->getDoctrine()->getManager();
        $em->persist($tableOrder);
        $em->flush();
        
        // Send reject message to customer
        $response = $this->getActiveTableOrderManager()->sendRejectTableOrderNotification4customer($tableOrder);
        
        // get Table 
        $activeTable = $tableOrder->getActiveTable();

        //get Order list
        $tableOrderList = $this->getActiveTableOrderManager()->getActiveTableOrderHistory($activeTable->getId());
        
        //init form for table reserve
        $activeTableOrder = new ActiveTableOrder();
        $form = $this->createForm(new ActiveTableOrderForm4AdminType(), $activeTableOrder);
   
        return $this->render('TableRestaurantBundle:TableDashboard:viewActiveTableOrderList.html.twig', array(
                'tableOrderList' => $tableOrderList,
                'activeTable' => $activeTable,
                'acceptReserve' => $this->getRequest()->request->get('acceptReserve'),
                'form' => $form->createView()
        ));
    }

    /**
     * Reserve active table
     * 
     * @param int $activeTableId
     * 
     * @param Request $request
     * 
     * @Template()
     */
    public function reserveActiveTableOrderAction($activeTableId, Request $request)
    {
         // get Admin user
        $adminUser = $this->container->get('security.context')->getToken()->getUser();
        
        $activeTableOrder = new ActiveTableOrder();
        $form = $this->createForm(new ActiveTableOrderForm4AdminType(), $activeTableOrder);
        $activeTable = $this->getActiveTableManager()->findOneById($activeTableId);

        if ($request->isMethod('POST')) {
            $form->bind($request);
            // get table order date
            $activeTableOrder = $form->getData();
            // Register New User (collect data to userform)
            $user = new User();
            $userForm = $this->createForm(new RestRegistrationFormType(), $user);

            // generste password(5 numbers)
            $userPassword = rand(11111, 99999);
            // Email cannot be empty, but we can do it hidden for client and fill it as
            //phone_tabledev@gmail.com
            if (is_null($activeTableOrder->getUserEmail()) || 
                    $activeTableOrder->getUserEmail() == "") {
                // cut + from phone
                $email = substr($activeTableOrder->getUserPhone(), 1) . "tabledev@gmail.com";
            } else {
                $email = $activeTableOrder->getUserEmail();
            }
            $userForm->bind(array(
                "firstname" => $activeTableOrder->getUserName(), // It is firstname in server
                "lastname" => $activeTableOrder->getUserLastName(),
                "email" => $email,
                "username" => $email, // the same as email
                "plainPassword" => array(
                    "first" => $userPassword,
                    "second" => $userPassword
                ),
                "phone" => $activeTableOrder->getUserPhone()
            ));
            
            // Check if user can do table order
            // devide reserve time on parts
            $reserveHour = $activeTableOrder->getReserveTime()->format('H');
            $reserveMin = $activeTableOrder->getReserveTime()->format('i');
            
            // get reserve date and time
            //$reserveDateTime = new \DateTime($activeTableOrder->getReserveDate(), new \DateTimeZone(ActiveTableOrder::RESERVE_TIMEZONE));
            $reserveDateTime = new \DateTime($activeTableOrder->getReserveDate());
            $reserveDateTime->setTime($reserveHour, $reserveMin);
   
            if ($form->isValid()) {
                // Check if admin can reserve table
                if (!$this->getActiveTableOrderManager()->isUserCanReserveTable($adminUser->getId(), $reserveDateTime)) {
                    // render Warning Notification, user cannot order other tables!!!
                    return $this->render('TableRestaurantBundle:TableDashboard:invalid.table.order.time.html.twig', array(
                                'user' => $adminUser
                    ));
                }
                // Check if we can reserve current table
                // get Booked Tables 
                $bookedActiveTables = $this->getActiveTableOrderManager()->getBookedTablesByRestaurant($activeTable->getTableMap()->getRestaurant()->getId(), $reserveDateTime);

                if (in_array($activeTableOrder->getActiveTable(), $bookedActiveTables)) {
                    return $this->render('TableRestaurantBundle:TableDashboard:table.has.allready.booked.html.twig');
                }
                //check is user already register by phone
                $userPhone = $activeTableOrder->getUserPhone();
                $registerUser = $this->getUserManager()->findUserBy(array('phone' => $userPhone));

                if (!is_null($registerUser)) {
                    $user = $registerUser;
                } else {
                    $user = $userForm->getData();
                    $user->setEnabled(true);

                    // send confirmation
                    $token = sha1(uniqid(mt_rand(), true)); // Or whatever you prefer to generate a token

                    $user->setConfirmationToken($token);

                    $mailer = $this->getUserMailer();
                    $mailer->sendConfirmationEmailMessage($user);
                    // Send sms message to new User (credentials)
                    $this->getActiveTableOrderManager()->sendConfirmationSmsMessage($user);

                    // add user
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($user);
                    $em->flush();

                    // Let's add Order and connected it to a new user
                    // Get New User
                    $user = $this->getUserManager()->findUserBy(array("username" => $email));
                }

                // add Order
                // format reserve date
                //$activeTableOrder->setReserveDate(new \DateTime($activeTableOrder->getReserveDate(), new \DateTimeZone(ActiveTableOrder::RESERVE_TIMEZONE)));
                $activeTableOrder->setReserveDate(new \DateTime($activeTableOrder->getReserveDate()));
                // set User Data
                $activeTableOrder->setUser($user);
                // set Table Data
                $activeTableOrder->setActiveTable($activeTable);
                // Set Status (Accept)
                $activeTableOrder->setStatus(ActiveTableOrder::ORDER_ACCEPT_STATUS_CODE);

                $em = $this->getDoctrine()->getManager();
                $em->persist($activeTableOrder);
                $em->flush();
                // Send confirm messages
                //To customer
                $this->getActiveTableOrderManager()->sendAcceptTableOrderNotification4customer($activeTableOrder);
                // To admin
                $this->getActiveTableOrderManager()->sendAcceptTableOrderNotification4admin($activeTableOrder);

                return $this->render('TableRestaurantBundle:TableDashboard:table.order.success.html.twig');
            }
        }
        
        // Merge User errors to active order errors
        $helperManager = $this->getHelperManager();
        if (!$userForm->isValid()) {
            foreach ($userForm->createView()->children as $key => $childrenErrors) {
                if (!empty($childrenErrors->vars['errors'])) {
                   $errors[$helperManager->getActiveOrderTableKeyForUserData($key)] = $childrenErrors->vars['errors'][0];
                } 
            }
            foreach ($errors as $key =>$error) {
                $form->get($key)->addError($error);
            }
        }

        return array(
            'form' => $form,
            'activeTable' => $activeTable
        );
    }
    
    /**
     * 
     * Get Active Table
     * 
     * @param int $activeTableId
     * 
     * @Template()
     */
    public function loadActiveTableAction($activeTableId)
    {
        $activeTable = $this->getActiveTableManager()->findOneById($activeTableId);
        
        return array(
            'activeTable' => $activeTable
        );
    }
    
    /**
     * View TableType List Container
     * 
     * @Template()
     */
    public function viewTableTypeContainerAction()
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

        return array(
            'tableTypeList' => $tableTypeList,
            'baseUrl' => $baseUrl
        );
    }
    
    /**
     * Delete Active Table 
     */
    public function deleteActiveTableAction()
    {
        // get Current user
        $user = $this->container->get('security.context')->getToken()->getUser();
        // Check if user auth in app
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException("Access denied");
        }
        // get Active Table ID
        $activeTableId = $this->getRequest()->request->get('activeTableId');

        // init Active table 
        $activeTable = $this->getActiveTableManager()->findOneById($activeTableId);

        // delete Active Table
        $em = $this->getDoctrine()->getManager();
        $em->remove($activeTable);
        $em->flush();

        return new Response("success");
    }
    
    /**
     * Update Active table
     * 
     */
    public function updateActiveTableAction()
    {
        // get Current user
        $user = $this->container->get('security.context')->getToken()->getUser();
        // Check if user auth in app
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException("Access denied");
        }
        // collect data
        $activeTableId = $this->getRequest()->request->get('activeTableId');
        $leftPosition = $this->getRequest()->request->get('leftPosition');
        $topPosition = $this->getRequest()->request->get('topPosition');
        $angle = $this->getRequest()->request->get('tableAngle');
        
        // init Active table 
        $activeTable = $this->getActiveTableManager()->findOneById($activeTableId);
        $activeTable->setLeftPosition($leftPosition);
        $activeTable->setTopPosition($topPosition);
        $activeTable->setAngle($angle);

        $em = $this->getDoctrine()->getManager();
        $em->persist($activeTable);
        $em->flush();
        
        return new Response("success");
    }
    
    /**
     * Get ActiveTable List in jsone format
     * 
     */
    public function getActiveTableListAction()
    {
        // get Current user
        $anonim = false;
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            $anonim = true;
        }
        
        $tableList = $this->getActiveTableManager()->findAll();
        // create new object
        $activeTableList = array();
        $activeTable = array();
        foreach ($tableList as $table) {
            $activeTable['id'] = $table->getId();
            $activeTable['angle'] = $table->getAngle();

            $activeTableList[] = $activeTable;
        }
        return $activeTableList;
    }
}
