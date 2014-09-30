<?php

namespace Application\Sonata\UserBundle\Controller;

use FOS\UserBundle\Controller\ResettingController as BaseResettingController;
use \Symfony\Component\HttpFoundation\RedirectResponse;

class ResettingController extends BaseResettingController
{

    /**
     * Tell the user to check his email provider
     */
    public function checkEmailAction()
    {
        $session = $this->container->get('session');
        $email = $session->get(static::SESSION_EMAIL);
        $session->remove(static::SESSION_EMAIL);

        $regForm = $this->container->get('fos_user.registration.form');
        $kitchenList = $this->container->get('restaurant_kitchen_manager')->getKitchens();
        $cityList = $this->container->get('city_manager')->findAll();
        /// get all category list
        $categoryList = $this->container->get('restaurant_category_manager')->getCategories();
        return $this->container->get('templating')
                        ->renderResponse('SonataUserBundle:Resetting:request.html.' . $this->getEngine(), array(
                            'cityList' => $cityList,
                            'formReg' => $regForm->createView(),
                            'categoryList' => $categoryList,
                            'kitchenList' => $kitchenList,
                            'searchCity' => '$searchCity',
                            'newsList' => array(),
        ));
    }

    /**
     * 
     * Enter Resetting code by user
     */
    public function requestAction()
    {
        $userPhone = $this->container->get('request')->get('userPhone');
        $userId = $this->container->get('request')->get('userId');

        $regForm = $this->container->get('fos_user.registration.form');
        $kitchenList = $this->container->get('restaurant_kitchen_manager')->getKitchens();
        $cityList = $this->container->get('city_manager')->findAll();
        /// get all category list
        $categoryList = $this->container->get('restaurant_category_manager')->getCategories();
        return $this->container->get('templating')->renderResponse('SonataUserBundle:Resetting:checkResettingCode.html.' . $this->getEngine(), array(
                    'cityList' => $cityList,
                    'formReg' => $regForm->createView(),
                    'categoryList' => $categoryList,
                    'kitchenList' => $kitchenList,
                    'searchCity' => '$searchCity',
                    'newsList' => array(),
                    'userPhone' => $userPhone,
                    'userId' => $userId
        ));
    }

    /**
     * Request reset user password: submit form and send email
     */
    public function sendResettingCodeAction()
    {
        //get user phone
        $userData = $this->container->get('request')->request->get('userData');

        /** @var $user UserInterface */
        if(filter_var($userData, FILTER_VALIDATE_EMAIL)){
            $user = $this->container->get('fos_user.user_manager')->findUserByUsername($userData);
        }else{
            $user = $this->container->get('fos_user.user_manager')->findUserBy(array('phone' => $userData));
        }
        
        $regForm = $this->container->get('fos_user.registration.form');
        $kitchenList = $this->container->get('restaurant_kitchen_manager')->getKitchens();
        $cityList = $this->container->get('city_manager')->findAll();
        /// get all category list
        $categoryList = $this->container->get('restaurant_category_manager')->getCategories();
        
        //check if user exist
        if (null === $user) {
            return $this->container->get('templating')->renderResponse('FOSUserBundle:Resetting:request.html.' . $this->getEngine(), array(
                        'invalid_user' => $userData,
                        'cityList' => $cityList,
                        'formReg' => $regForm->createView(),
                        'categoryList' => $categoryList,
                        'kitchenList' => $kitchenList,
                        'searchCity' => '$searchCity',
                        'newsList' => array(),
            ));
        }
        $userPhone = $user->getPhone();
        
        //generate resseting code for user
        $resettingCode = $this->generateResettingCode();
        $user->setResettingCode($resettingCode);
        //set sms text
        $text = $this->container->get('templating')->render(
                'TableMainBundle:Mail:resettingSMS.html.twig', array(
            'resettingCode' => $resettingCode
        ));
        $this->container->get('sms_manager')->sendMessage($userPhone, $text);
        $user->setPasswordRequestedAt(new \DateTime());
        $this->container->get('fos_user.user_manager')->updateUser($user);

        return new RedirectResponse($this->container->get('router')->generate('fos_user_resetting_request', array('userPhone' => substr_replace($userPhone, '****', 6, 4), 'userId' => $user->getId())));
    }

    /**
     * 
     * check is resetting code is correct
     * 
     * @return type
     */
    public function checkResettingCodeAction()
    {
        $userPhone = $this->container->get('request')->request->get('userPhone');
        $resettingCode = $this->container->get('request')->request->get('resettingCode');
        $userId = $this->container->get('request')->request->get('id');
        
        //get params for header
        $regForm = $this->container->get('fos_user.registration.form');
        $kitchenList = $this->container->get('restaurant_kitchen_manager')->getKitchens();
        $cityList = $this->container->get('city_manager')->findAll();
        $categoryList = $this->container->get('restaurant_category_manager')->getCategories();
        $user = $this->container->get('fos_user.user_manager')->findUserBy(array('id' => $userId));

        if (is_null($user) || is_null($userId)) {
            return $this->container->get('templating')->renderResponse('FOSUserBundle:Resetting:checkResettingCode.html.' . $this->getEngine(), array(
                        'invalid_phone' => $userPhone,
                        'cityList' => $cityList,
                        'formReg' => $regForm->createView(),
                        'categoryList' => $categoryList,
                        'kitchenList' => $kitchenList,
                        'searchCity' => '$searchCity',
                        'newsList' => array(),
                        'userPhone' => $userPhone,
                        'userId' => $userId
            ));
        }
        //check is code is correct
        if($user->getResettingCode() != $resettingCode){
          return $this->container->get('templating')->renderResponse('FOSUserBundle:Resetting:checkResettingCode.html.' . $this->getEngine(), array(
                        'invalid_code' => 1,
                        'cityList' => $cityList,
                        'formReg' => $regForm->createView(),
                        'categoryList' => $categoryList,
                        'kitchenList' => $kitchenList,
                        'searchCity' => '$searchCity',
                        'newsList' => array(),
                        'userPhone' => $userPhone,
                        'userId' => $userId
            )); 
        }
        
        
        return $this->container->get('templating')->renderResponse('SonataUserBundle:Resetting:changePassword.html.' . $this->getEngine(), array(
                    'cityList' => $cityList,
                    'formReg' => $regForm->createView(),
                    'categoryList' => $categoryList,
                    'kitchenList' => $kitchenList,
                    'searchCity' => '$searchCity',
                    'newsList' => array(),
                    'userPhone' => $userPhone,
                    'userId' => $userId
        ));

    }

    public function resetUserPasswordAction()
    {
        $userId = $this->container->get('request')->request->get('userId');
        $password = $this->container->get('request')->request->get('password');
        $confirmPassword = $this->container->get('request')->request->get('confirmPassword');
        
         //get params for header
        $regForm = $this->container->get('fos_user.registration.form');
        $kitchenList = $this->container->get('restaurant_kitchen_manager')->getKitchens();
        $cityList = $this->container->get('city_manager')->findAll();
        $categoryList = $this->container->get('restaurant_category_manager')->getCategories();
        
        if($password != $confirmPassword){
            return $this->container->get('templating')->renderResponse('SonataUserBundle:Resetting:changePassword.html.' . $this->getEngine(), array(
                    'cityList' => $cityList,
                    'formReg' => $regForm->createView(),
                    'categoryList' => $categoryList,
                    'kitchenList' => $kitchenList,
                    'searchCity' => '$searchCity',
                    'newsList' => array(),
                    'not_match_passwords'=> 1,
                    'userId' => $userId
        ));
        }
        $user = $this->container->get('fos_user.user_manager')->findUserBy(array('id' => $userId));
        $username = $user->getUserName();
        $manipulator = $this->container->get('fos_user.util.user_manipulator');
        $manipulator->changePassword($username, $password);
        $response = new RedirectResponse($this->getRedirectionUrl($user));
        $this->authenticateUser($user, $response);
        
        return $response;
    }
    
    public function generateResettingCode()
    {
        $code = rand(100000, 999999);
        return $code;
    }

}