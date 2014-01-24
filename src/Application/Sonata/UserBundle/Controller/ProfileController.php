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
use FOS\UserBundle\Controller\ProfileController as BaseSecurityController;
use Table\RestaurantBundle\Entity\News;

/**
 * This class is inspired from the FOS Profile Controller, except :
 *   - only twig is supported
 *   - separation of the user authentication form with the profile form
 *
 */
class ProfileController extends BaseSecurityController
{

    /**
     * View Profile
     * 
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

        /* THIS INFORMATION SHOULD BE IN EACH  CONTROLLER BECAUSE WE USE IT IN HEADER */
        // get city list
        $cityList = $this->container->get('city_manager')->findAll();
        /// get all category list
        $categoryList = $this->container->get('restaurant_category_manager')->findAll();
        // get all kitchen list
        $kitchenList = $this->container->get('restaurant_kitchen_manager')->findAll();

        // get current city
        $searchCity = $this->container->get('request')->query->get('searchCity');
        // if null set default -> krasnodar
        if (is_null($searchCity)) {
            $searchCity = 1;
        }
        /*         * ** */

        /* THIS INFORMATION SHOULD BE IN EACH  CONTROLLER BECAUSE WE USE IT IN RIGHT SIDEBAR */
        $newsList = $this->container->get('news_manager')->getNews();

        // BreadCrumbs
        $breadcrumbs = $this->container->get('white_october_breadcrumbs');
        $breadcrumbs->addItem(
                $this->container->get('translator')->trans('main.breadcrumbs.label.home'), $this->container->get("router")->generate("table_main_homepage")
        );
        // current
        $breadcrumbs->addItem(
                $this->container->get('translator')->trans('main.breadcrumbs.label.profile')
        );

        return $this->container->get('templating')->renderResponse(
                'ApplicationSonataUserBundle:Profile:show.html.twig', array(
                    'user' => $user,
                    'cityList' => $cityList,
                    'categoryList' => $categoryList,
                    'kitchenList' => $kitchenList,
                    'searchCity' => $searchCity,
                    'breadcrumbs' => $breadcrumbs,
                    'newsList' => $newsList->getQuery()->getResult()
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
            // $this->setFlash('fos_user_success', 'profile.flash.updated');

            return new RedirectResponse($this->container->get('router')->generate('fos_user_profile_show'));
        }
        foreach ($form->getErrors() as $formError) {
            if ($formError->getMessageTemplate() == "fos_user.password.password_not_fit_format") {
                $form->get('newPassword')->addError(new FormError($formError->getMessage()));
            }
        }

        /* THIS INFORMATION SHOULD BE IN EACH  CONTROLLER BECAUSE WE USE IT IN HEADER */
        // get city list
        $cityList = $this->container->get('city_manager')->findAll();
        /// get all category list
        $categoryList = $this->container->get('restaurant_category_manager')->findAll();
        // get all kitchen list
        $kitchenList = $this->container->get('restaurant_kitchen_manager')->findAll();
        // get current city
        $searchCity = $this->container->get('request')->query->get('searchCity');
        // if null set default -> krasnodar
        if (is_null($searchCity)) {
            $searchCity = 1;
        }
        /*         * ** */

        /* THIS INFORMATION SHOULD BE IN EACH  CONTROLLER BECAUSE WE USE IT IN RIGHT SIDEBAR */
        $newsList = $this->container->get('news_manager')->getNews();

        // BreadCrumbs
        $breadcrumbs = $this->container->get('white_october_breadcrumbs');
        $breadcrumbs->addItem(
                $this->container->get('translator')->trans('main.breadcrumbs.label.home'), $this->container->get("router")->generate("table_main_homepage")
        );

        $breadcrumbs->addItem(
                $this->container->get('translator')->trans('main.breadcrumbs.label.profile'), $this->container->get("router")->generate("fos_user_profile_show")
        );

        // current
        $breadcrumbs->addItem(
                $this->container->get('translator')->trans('main.breadcrumbs.label.editProfile')
        );

        // Check if user should fill email
        $isInvalidEmail = false;
        if ($user->getEmail() == $user->getUsername() . "@gmail.com" || 
                $user->getEmail() == $user->getUsername() . "@table4you.com") {
            $isInvalidEmail = true;
        }
        return $this->container->get('templating')->renderResponse(
                'ApplicationSonataUserBundle:Profile:edit_profile.html.twig', array(
            'form' => $form->createView(),
            'cityList' => $cityList,
            'categoryList' => $categoryList,
            'kitchenList' => $kitchenList,
            'searchCity' => $searchCity,
            'breadcrumbs' => $breadcrumbs,
            'newsList' => $newsList->getQuery()->getResult(),
            'isInvalidEmail' => $isInvalidEmail
        ));
    }

}
