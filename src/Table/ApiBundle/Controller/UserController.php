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
use Application\Sonata\UserBundle\Form\Type\RestRegistrationFormType;
use Application\Sonata\UserBundle\Entity\User as BaseUser;

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
        if (!$encoder) {
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
        if (!$user) {
            $user = $um->findUserByEmail($username);
        }
        if (!$user instanceof User) {
            return array(
                'success' => false,
                'errorStr' => $this->get('translator')->trans('validation.errors.user.User not found', array(), 'FOSUserBundle')
            );
        }
        if (!$this->checkUserPassword($user, $password)) {
            return array(
                'success' => false,
                'errorStr' => $this->get('translator')->trans("validation.errors.user.Wrong password", array(), 'FOSUserBundle')
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
        return array('success' => true);
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
                'success' => false,
                'errorStr' => $this->get('translator')->trans("validation.errors.user.You should auth at first", array(), 'FOSUserBundle')
            );
        }

        if (!is_object($user) || !$user instanceof User) {
            return array(
                'success' => false,
                'errorStr' => $this->get('translator')->trans("validation.errors.user.This user does not have access to this section", array(), 'FOSUserBundle')
            );
        } else {
            return array(
                'success' => true,
                'user' => $user
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
                'success' => false,
                'errorStr' => $this->get('translator')->trans("validation.errors.user.User not found", array(), 'FOSUserBundle')
            );
        }

        if ($user->isPasswordRequestNonExpired($this->container->getParameter('fos_user.resetting.token_ttl'))) {
            return array(
                'success' => false,
                'errorStr' => $this->get('translator')->trans("validation.errors.user.Password already requested", array(), 'FOSUserBundle')
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
        return array('success' => true);
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
    {
        $user = new User();
        $form = $this->createForm(new RestRegistrationFormType(), $user);

        $form->bind(array(
            "username" => $this->getRequest()->request->get('username'),
            "lastname" => $this->getRequest()->request->get('lastname'),
            "email" => $this->getRequest()->request->get('email'),
            "plainPassword" => array(
                "first" => $this->getRequest()->request->get('firstPassword'),
                "second" => $this->getRequest()->request->get('secondPassword')
            ),
            "phone" => $this->getRequest()->request->get('phone')
        ));
        /*   $form->bind(array(
          "username" => "test4restApi14",
          "lastname" => "By By",
          "email" => "test4restApi14@mail.ru",
          "plainPassword" => array(
          "first" => "12345",
          "second" => "12345"
          ),
          "phone" => "321"
          )); */
        if ($form->isValid()) {

            $user = $form->getData();
            $user->setEnabled(false);

            // send confirmation
            $token = sha1(uniqid(mt_rand(), true)); // Or whatever you prefer to generate a token

            $user->setConfirmationToken($token);

            $mailer = $this->container->get('fos_user.mailer');
            $mailer->sendConfirmationEmailMessage($user);

            // add user
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $response = array();
            $response['success'] = true;
            return $response;
        } else {
            $errors = array();
            foreach ($form->createView()->children as $key => $childrenErrors) {
                if (!empty($childrenErrors->vars['errors'])) {
                   $errors[] = $childrenErrors->vars['errors'][0];
                } elseif($key == "plainPassword" &&
                       !empty($childrenErrors->children['first']->vars['errors']) ) {
                    $errors[] = $childrenErrors->children['first']->vars['errors'][0];
                }
            }

            return array(
                'success' => false,
                'errorStr' => $errors
            );
        }

     //   return \FOS\RestBundle\View\View::create($form, \FOS\Rest\Util\Codes::HTTP_BAD_REQUEST);
    }
    
    
}
