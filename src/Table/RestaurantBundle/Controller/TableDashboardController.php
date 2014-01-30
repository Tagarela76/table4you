<?php

namespace Table\RestaurantBundle\Controller;

use Table\MainBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FOS\UserBundle\Model\UserInterface;
use Table\RestaurantBundle\Entity\TableType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class TableDashboardController extends Controller
{

        /**
     * View Restaurant
     * 
     * @Template()
     */
    public function viewEditorDashboardAction()
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
            $this->generateUrl("table_editor_dashboard")
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
            $this->generateUrl("table_editor_dashboard")
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
}
