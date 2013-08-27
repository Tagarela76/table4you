<?php

namespace Application\Sonata\UserBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\Form\FormError;
use Sonata\UserBundle\Controller\ProfileController as BaseSecurityController;
/**
 * This class is inspired from the FOS Profile Controller, except :
 *   - only twig is supported
 *   - separation of the user authentication form with the profile form
 *
 */
class ProfileController extends BaseSecurityController
{

    /**
     * @return Response
     *
     * @throws AccessDeniedException
     */
    public function showAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            
            // redirect on homepage
            return $this->redirect(
                $this->generateUrl("table_main_homepage")
            );
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        return $this->render('ApplicationSonataUserBundle:Profile:show.html.twig', array(
            'user' => $user
        ));
    }

    /**
     * @return Response
     *
     * @throws AccessDeniedException
     */
    public function editProfileAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $form = $this->container->get('sonata.user.profile.form');

        $formHandler = $this->container->get('sonata.user.profile.form.handler');

        $process = $formHandler->process($user);
        if ($process) {
            $this->setFlash('fos_user_success', 'profile.flash.updated');

            return new RedirectResponse($this->generateUrl('sonata_user_profile_show'));
        } 
        foreach ($form->getErrors() as $formError) {
           if ($formError->getMessageTemplate() == "fos_user.password.password_not_fit_format") {
               $form->get('newPassword')->addError(new FormError($formError->getMessage()));
           } 
        }
 
        return $this->render('ApplicationSonataUserBundle:Profile:edit_profile.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
