<?php

namespace Table\MainBundle\Controller;

use Table\MainBundle\Controller\Controller;
use Table\RestaurantBundle\Entity\Restaurant;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Table\RestaurantBundle\Entity\News;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{

    /**
     * 
     * Render main page
     * @Template()
     * 
     * @param type $page
     * @return array[]
     */
    public function indexAction($page)
    {
        // get Current user
        $user = $this->container->get('security.context')->getToken()->getUser();

        // Check if user auth in app
        $anonim = false;
        if (!is_object($user) || !$user instanceof UserInterface) {
            $anonim = true;
        }
        // check if user can change rating
        $restaurantsWhoHadHasAlreadyRating = array();
        $isRatingDisabled = false;
        if (!$anonim) {
            $userRating = $this->getRatingStatManager()->getUserRestaurantRating($user->getId());
            // only 3 state
            if (count($userRating) > 2) {
                $isRatingDisabled = true;
            }
            // Also we should get restaurants array , who has already have rating today
            foreach ($userRating as $rating) {
                $restaurantsWhoHadHasAlreadyRating[] = $rating->getRestaurant()->getId();
            }
        }
        if ($anonim) {
            $isRatingDisabled = true;
        }

        /* THIS INFORMATION SHOULD BE IN EACH  CONTROLLER BECAUSE WE USE IT IN HEADER */
        // get city list
        $cityList = $this->getCityManager()->findAll();
        /// get all category list
        $categoryList = $this->getRestaurantCategoryManager()->getCategories();
        // get all kitchen list
        $kitchenList = $this->getRestaurantKitchenManager()->getKitchens();

        // get current city
        $searchCity = $this->getRequest()->query->get('searchCity');
        // if null set default -> krasnodar
        if (is_null($searchCity)) {
            $searchCity = 1;
        }
        /*         * ** */

        /* THIS INFORMATION SHOULD BE IN EACH  CONTROLLER BECAUSE WE USE IT IN RIGHT SIDEBAR */
        $newsList = $this->getNewsManager()->findByCity($searchCity);
        //var_dump($newsList->getQuery()); die();     
        // get restaurant list
        $filter = $this->getRequest()->request->get('filter');  // fir restaurant filter
        if ($filter) {
            $restaurantList = $this->getRestaurantManager()->searchRestaurants($this->getRequest());
        } else {
            $restaurantList = $this->getRestaurantManager()->findByCity($searchCity);
        }

        $phoneFormatError = false;
        // validate Phone
        if (!$anonim) {
            if (!preg_match("/^\+7\d{10}$/", $user->getPhone())) {
                $phoneFormatError = true;
            }
        }
        
        $confirmed = $this->getRequest()->query->get('confirmed');
        // if filter render only restaurant list
        if ($filter) {
            return $this->render(
                'TableRestaurantBundle:Default:restaurantList.html.twig', array(
                    'restaurantsList' => $this->getPaginator()->paginate(
                            $restaurantList, $page, Restaurant::PER_PAGE_COUNT
                    ),
                    'anonim' => $anonim,
                    'restaurantsWhoHadHasAlreadyRating' => $restaurantsWhoHadHasAlreadyRating,
                    'isRatingDisabled' => $isRatingDisabled,
                    'cityList' => $cityList,
                    'categoryList' => $categoryList,
                    'kitchenList' => $kitchenList,
                    'searchCity' => $searchCity,
                    'phoneFormatError' => $phoneFormatError,
                    'confirmed' => $confirmed,
                    'filter' => true,
                    'restaurants' => $restaurantList->getQuery()->getResult()
                )
            );
        } else {
            // registration form 
            $regForm = $this->container->get('fos_user.registration.form');
            return array(
                'restaurantsList' => $this->getPaginator()->paginate(
                        $restaurantList, $page, Restaurant::PER_PAGE_COUNT
                ),
                'anonim' => $anonim,
                'restaurantsWhoHadHasAlreadyRating' => $restaurantsWhoHadHasAlreadyRating,
                'isRatingDisabled' => $isRatingDisabled,
                'cityList' => $cityList,
                'categoryList' => $categoryList,
                'kitchenList' => $kitchenList,
                'searchCity' => $searchCity,
                'newsList' => $newsList->getQuery()->getResult(),
                'formReg' => $regForm->createView(),
                'phoneFormatError' => $phoneFormatError,
                'confirmed' => $confirmed,
                'filter' => false,
                'restaurants' => $restaurantList->getQuery()->getResult()
            );
        }
    }

    /**
     * 
     * Render auth page
     * 
     * @Template()
     * 
     * @return array[]
     */
    public function viewAuthPageAction()
    {
        /* THIS INFORMATION SHOULD BE IN EACH  CONTROLLER BECAUSE WE USE IT IN HEADER */
        // get city list
        $cityList = $this->getCityManager()->findAll();
        /// get all category list
        $categoryList = $this->getRestaurantCategoryManager()->getCategories();
        // get all kitchen list
        $kitchenList = $this->getRestaurantKitchenManager()->getKitchens();

        // get current city
        $searchCity = $this->getRequest()->query->get('searchCity');
        // if null set default -> krasnodar
        if (is_null($searchCity)) {
            $searchCity = 1;
        }
        /*         * ** */
        /* THIS INFORMATION SHOULD BE IN EACH  CONTROLLER BECAUSE WE USE IT IN RIGHT SIDEBAR */
        $newsList = $this->getNewsManager()->findByCity($searchCity);
        // registration form (header)
        $regForm = $this->container->get('fos_user.registration.form');
        return array(
            'cityList' => $cityList,
            'categoryList' => $categoryList,
            'kitchenList' => $kitchenList,
            'searchCity' => $searchCity,
            'newsList' => $newsList->getQuery()->getResult(),
            'formReg' => $regForm->createView()
        );
    }

    /**
     *  
     * Validate user email
     * 
     * @param string $path
     * 
     * @Template()
     */
    public function validateUserEmailAction($path)
    {
        // Do not check if user on page profile now
        if ($path == "fos_user_profile_edit") {
            return new Response('true');
        }
        // get Current user
        $user = $this->container->get('security.context')->getToken()->getUser();
        // only for auth users
        if (is_object($user) && $user instanceof UserInterface) {
            if ($user->getEmail() == $user->getUsername() . "@gmail.com" ||
                    $user->getEmail() == $user->getUsername() . "@table4you.com") {
                return new Response('false');
            }
        }

        return new Response('true');
    }

}
