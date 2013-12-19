<?php
namespace Application\Sonata\UserBundle\Security\Core\User;
 
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\Security\Core\User\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;

class FOSUBUserProvider extends BaseClass
{
    protected $container;
    
    public function __construct(UserManagerInterface $userManager, $container, array $properties)
    {
        $this->userManager = $userManager;
        $this->container  = $container;
        $this->properties  = $properties;
    }
 
    /**
     * {@inheritDoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        
    }
 
    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $userTokenId = $response->getUsername();// it is user id in social network
        $email = $response->getEmail();
        $userNameLastName = $response->getRealName(); // name + lastname
        $userNameLastName = explode(" ", $userNameLastName);
        $realName = $userNameLastName[0];
        $realLastName = $userNameLastName[1];
  //  var_dump($response); die();    
        $service = $response->getResourceOwner()->getName();
        // Lets create toke service _ id (for example facebook_213123424)
        $accessToken = $userTokenId . "_" . $service;
        
        // find by name
        $user = $this->userManager->findUserBy(array("username" => $accessToken));
        
        //when the user is registrating
        if (null === $user) {       
            // create new user here
            $user = $this->userManager->createUser();
            
            $user->setUsername($accessToken);
            $user->setFirstname($realName);
            $user->setLastname($realLastName);
            //email can be null
            if (!is_null($email)) {
                $user->setEmail($email);
            } else {
                $user->setEmail($accessToken);
            }
            
            $user->setPassword($userTokenId);
            $user->setEnabled(true);
            
            if (null === $user->getConfirmationToken()) {
                /** @var $tokenGenerator \FOS\UserBundle\Util\TokenGeneratorInterface */
                $tokenGenerator = $this->container->get('fos_user.util.token_generator');
                $user->setConfirmationToken($tokenGenerator->generateToken());
            }
            $this->userManager->updateUser($user);
            
            // We should send confirmation email
            $mailer = $this->container->get('fos_user.mailer');
            $mailer->sendConfirmationEmailMessage($user);
            return $user;
        }
 
        //if user exists - go with the HWIOAuth way
 
        return $user;
    }
 
}