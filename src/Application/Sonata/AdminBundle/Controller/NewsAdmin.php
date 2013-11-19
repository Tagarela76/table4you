<?php

namespace Application\Sonata\AdminBundle\Controller;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class NewsAdmin extends Admin
{

    /**
     * @param \Table\RestaurantBundle\Entity\News $news
     *
     * @return void
     */
    public function preUpdate($news)
    {
        // I should get original data
        $original = (object) $this->getModelManager()->getEntityManager($this->getClass())->getUnitOfWork()->getOriginalEntityData($news);

        if ($news->getPublished() && !$original->published) {
            // set published Date if published
            $news->setPublishedDate(new \DateTime);
        }  
    }
    
    /**
     * @param \Table\RestaurantBundle\Entity\News $news
     *
     * @return void
     */
    public function prePersist($news)
    {

        if ($news->getPublished()) {
            // set published Date if published
            $news->setPublishedDate(new \DateTime);
        }  
        
        // set create Date
        $news->setCreateDate(new \DateTime);
    }
    
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
                ->add('title', null, array(
                    'label' => 'restaurant.news.title',
                    'required' => true
                ))
                ->add('content','textarea', array(
                    'label' => 'restaurant.news.content',
                    'required' => true
                ))
                ->add('restaurant', 'sonata_type_model', array(
                    'label' => 'restaurant.news.restaurant',
                    'required' => true
                ))
                ->add('startDate','date', array(
                    'label' => 'restaurant.news.date.start',
                    'widget' => 'choice',
                    'required' => false
                ))
                ->add('endDate','date', array(
                    'label' => 'restaurant.news.date.end',
                    'widget' => 'choice',
                    'required' => false
                ))
                ->add('published','checkbox', array(
                    'label' => 'restaurant.news.published',
                    'required' => false
                ))
                ->add('photo', 'sonata_type_model_list', array(
                    'required' => true), array(
                        'link_parameters' => array(
                            'context' => 'image'
                            ))
                    )
                
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
                ->add('title', null, array(
                    'label' => 'restaurant.news.title'
                ))
                ->add('restaurant', null, array(
                    'label' => 'restaurant.news.restaurant'
                ))
                ->add('startDate',null, array(
                    'label' => 'restaurant.news.date.start'
                ))
                ->add('endDate',null, array(
                    'label' => 'restaurant.news.date.end'
                ))
                ->add('published',null, array(
                    'label' => 'restaurant.news.published'
                ))
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
                ->addIdentifier('id')
                ->add('title', null, array(
                    'label' => 'restaurant.news.title'
                ))
                ->add('restaurant', null, array(
                    'label' => 'restaurant.news.restaurant'
                ))
                ->add('startDate',null, array(
                    'label' => 'restaurant.news.date.start'
                ))
                ->add('endDate',null, array(
                    'label' => 'restaurant.news.date.end'
                ))
                ->add('published',null, array(
                    'label' => 'restaurant.news.published'
                ))
        ;
    }

    public function getPersistentParameters()
    {
        if (!$this->getRequest()) {
            return array();
        }

        return array(
            'provider' => $this->getRequest()->get('provider'),
            'context' => $this->getRequest()->get('context'),
        );
    }

}
