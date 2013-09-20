<?php

namespace Table\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Table\MainBundle\Controller\Controller;

use Application\Sonata\UserBundle\Entity\User;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class UserController extends Controller
{
    const SESSION_EMAIL = 'fos_user_send_resetting_email/email';
    
    /**
     * Get the truncated email displayed when requesting the resetting.
     *
     * The default implementation only keeps the part following @ in the address.
     *
     * @param \FOS\UserBundle\Model\UserInterface $user
     *
     * @return string
     */
    protected function getObfuscatedEmail(User $user)
    {
        $email = $user->getEmail();
        if (false !== $pos = strpos($email, '@')) {
            $email = '...' . substr($email, $pos);
        }

        return $email;
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
    
    /**
     * Login User
     * 
     * @param \Application\Sonata\UserBundle\Entity\User $user
     */
    protected function loginUser(User $user)
    {
        $security = $this->get('security.context');
        $providerKey = $this->container->getParameter('fos_user.firewall_name');
        $roles = $user->getRoles();
        $token = new UsernamePasswordToken($user, null, $providerKey, $roles);
        $security->setToken($token);
    }
    
    /**
     * Logout current user using random token
     */
    protected function logoutUser()
    {
        $security = $this->get('security.context');
        $token = new AnonymousToken(null, new User());
        $security->setToken($token);
        $this->get('session')->invalidate();
    }
    
    /**
     * Check user password
     * 
     * @param \Application\Sonata\UserBundle\Entity\User $user
     * @param string $password
     * @return boolean
     */
    protected function checkUserPassword(User $user, $password)
    {
        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($user);
        if(!$encoder){
            return false;
        }
        return $encoder->isPasswordValid($user->getPassword(), $password, $user->getSalt());
    }
    
    /**
     * Login controller. We should sent username/password in POST
     * 
     * @Rest\View
     */
    public function loginAction()
    {
        $request = $this->getRequest();
        $username = $request->get('username');
        $password = $request->get('password');

        $um = $this->getUserManager();
        $user = $um->findUserByUsername($username);        
        if(!$user){
            $user = $um->findUserByEmail($username);
        } 
        if(!$user instanceof User){
            return array(
                'success'=>false, 
                'errorStr'=>"User not found"
            );
        }
        if(!$this->checkUserPassword($user, $password)){
            return array(
                'success'=>false, 
                'errorStr'=>"Wrong password"
            );
        } 
        $this->loginUser($user);
        // get wsse token
        $token = $this->createBaseWsse($user, $password);
        return array('success' => true,
            'user' => $user, 
            'token' => $token
        );
    }
    
    /**
     * Logout 
     * 
     * @Rest\View
     */
    public function logoutAction()
    {
        $this->logoutUser();
        return array('success'=>true);
    }
    
    /**
     * Get user information
     * 
     * @Rest\View
     * 
     * @return \Application\Sonata\UserBundle\Entity\User $user
     */
    public function getUserInfoAction()
    {
        $user = $this->get('security.context')->getToken()->getUser(); 
        // user can be anon.
        if ($user == "anon.") {
            return array(
                'success'=>false, 
                'errorStr'=>"You should auth at first"
            );
        }
       
        if (!is_object($user) || !$user instanceof User) {
            return array(
                'success'=>false,
                'errorStr'=>"This user does not have access to this section"
            );
        } else {
            return array(
                'success'=>true,
                'user'=>$user
            );
        }
    }
    
    /**
     * Resset password and sent it to emmail
     * 
     * @Rest\View
     */
    public function forgotPasswordAction()
    {
        $email = $this->container->get('request')->request->get('email');

        /** @var $user UserInterface */
        $user = $this->container->get('fos_user.user_manager')->findUserByUsernameOrEmail($email);

        if (null === $user) {
            return array(
                'success'=>false,
                'errorStr'=>"User not found"
            );
        }

        if ($user->isPasswordRequestNonExpired($this->container->getParameter('fos_user.resetting.token_ttl'))) {
            return array(
                'success'=>false,
                'errorStr'=>"Password already requested"
            );
        }

        if (null === $user->getConfirmationToken()) {
            /** @var $tokenGenerator \FOS\UserBundle\Util\TokenGeneratorInterface */
            $tokenGenerator = $this->container->get('fos_user.util.token_generator');
            $user->setConfirmationToken($tokenGenerator->generateToken());
        }

        $this->container->get('session')->set(static::SESSION_EMAIL, $this->getObfuscatedEmail($user));
        $this->container->get('fos_user.mailer')->sendResettingEmailMessage($user);
        $user->setPasswordRequestedAt(new \DateTime());
        $this->container->get('fos_user.user_manager')->updateUser($user);
        return array('success'=>true);
    }
    
    /**
     * Create token
     * 
     * @param \Application\Sonata\UserBundle\Entity\User $user
     * 
     * @param string $palinPassword
     * 
     * @return string
     */
    public function createBaseWsse(User $user, $palinPassword) 
    {   
      $username = $user->getUsername();
      $password = $palinPassword;
      
      $created = date('c');
      $nonce = substr(md5(uniqid('nonce_', true)), 0, 16);
      $nonceHigh = base64_encode($nonce);

      $factory = $this->get('security.encoder_factory');
      $encoder = $factory->getEncoder($user);
      $password = $encoder->encodePassword($password, $user->getSalt());
      $passwordDigest = base64_encode(sha1($nonce . $created . $password, true));
      
      $token = "UsernameToken Username=\"{$username}\", " . 
              "PasswordDigest=\"{$passwordDigest}\", Nonce=\"{$nonceHigh}\","
              . " Created=\"{$created}\"";
                
      
              
      return $token;
    }
    
    /**
     * User registration
     * 
     * @Rest\View
     */
    public function registerAction()
    {  /*     
        $form = $this->container->get('fos_user.rest_registration.form');
        $formHandler = $this->container->get('fos_user.registration.form.handler');
        $confirmationEnabled = $this->container->getParameter('fos_user.registration.confirmation.enabled');

        $process = $formHandler->process($confirmationEnabled);// var_dump($process); die();
        if ($process) { 
            $user = $form->getData();

            $response = new Response();
            $response->setStatusCode(201);
            $response->headers->set('Location',
                $this->generateUrl(
                    'table_main_homepage', true // absolute
                )
            );
            
            return $response;
        }
        
        return \FOS\RestBundle\View\View::create($form, 400);*/
    }
}
