<?php

namespace Application\Sonata\UserBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\RegistrationController as BaseController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Form\FormError;

class RegistrationController extends BaseController
{
    public function registerAction()
    {
        $form = $this->container->get('fos_user.registration.form');
        $formHandler = $this->container->get('fos_user.registration.form.handler');
        $confirmationEnabled = $this->container->getParameter('fos_user.registration.confirmation.enabled');

        $process = $formHandler->process($confirmationEnabled);
        if ($process) { 
            $user = $form->getData();

            $authUser = false;
            if ($confirmationEnabled) {
                if (null === $user) {
                    throw new NotFoundHttpException(sprintf('The user with email "%s" does not exist', $user->getEmail()));
                }
                return $response = $this->container->get('templating')->renderResponse('FOSUserBundle:Registration:checkEmail.html.'.$this->getEngine(), array(
                    'user' => $user,
                ));

            } else {
                $authUser = true;
                if (!is_object($user) || !$user instanceof UserInterface) {
                    throw new AccessDeniedException('This user does not have access to this section.');
                }

                return $response = $this->container->get('templating')->renderResponse('FOSUserBundle:Registration:confirmed.html.'.$this->getEngine(), array(
                    'user' => $user,
                ));
            }

            $this->setFlash('fos_user_success', 'registration.flash.user_created');

            if ($authUser) {
                $this->authenticateUser($user, $response);
            }

            return $response;
        }
        $emailErrors = $form->createView()->children['email']->vars['errors'];
        
        // We process only email Errors
        if (!empty($emailErrors)) {
            $emailError = $emailErrors[0];
            // only for template fos_user.email.already_used
            if ($emailError->getMessageTemplate() == "fos_user.email.already_used") {
                // Get resseting URL
                $resettingUrl = $this->container->get('router')->generate('fos_user_resetting_check_email');
                //Add resset url to form
                $translated = $this->container->get('translator')->trans('fos_user.email.restore_password', array(), 'validators');
                $emailAlreadyUsedErrorText = "<a href='{$resettingUrl}'>{$translated}</a>";
                $form->get('email')->addError(new FormError($emailAlreadyUsedErrorText));
            } 
        }

        return $this->container->get('templating')->renderResponse('ApplicationSonataUserBundle:Registration:register.html.'.$this->getEngine(), array(
            'form' => $form->createView(),
        ));
            
    }
}