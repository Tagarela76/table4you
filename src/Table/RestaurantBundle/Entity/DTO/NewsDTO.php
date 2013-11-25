<?php

namespace Table\RestaurantBundle\Entity\DTO;

use Table\RestaurantBundle\Entity\News;

/**
 * DTO for Restaurant
 *
 * @author masha
 */
class NewsDTO
{
    private $id;
    private $title;
    private $content;
    private $startDate;
    private $startTime;
    private $endDate;
    private $endTime;
    private $publishedDate;
    private $publishedTime;
    private $published;
    private $restrauntId;
    private $photo;

    public function __construct(News $news, $container)
    {
        $this->id = $news->getId();
        $this->title = $news->getTitle();
        $this->content = $news->getContent();
        $this->restrauntId = $news->getRestaurant()->getId();
        if (!is_null($news->getStartDate())) {
            $this->startDate = $news->getStartDate()->format('Y/m/d');
            $this->startTime = $news->getStartDate()->format('H:i:s');
        } 
            
        if (!is_null($news->getEndDate())) {
            $this->endDate = $news->getEndDate()->format('Y/m/d');
            $this->endTime = $news->getEndDate()->format('H:i:s'); 
        } 
        
        if (!is_null($news->getPublishedDate())) {
            $this->publishedDate = $news->getPublishedDate()->format('Y/m/d');
            $this->publishedTime = $news->getPublishedDate()->format('H:i:s');  
        } 

        $this->published = $news->getPublished();

        // get big and small
        $provider = $container->get('sonata.media.pool')
                       ->getProvider($news->getPhoto()->getProviderName());

        $formatSmall = $provider->getFormatName($news->getPhoto(), "small");
        $formatBig = $provider->getFormatName($news->getPhoto(), "big");
        $smallImageURL = $container->getParameter('site_host') . $provider->generatePublicUrl($news->getPhoto(), $formatSmall);
        $bigImageURL = $container->getParameter('site_host') . $provider->generatePublicUrl($news->getPhoto(), $formatBig);
        
        $photo = array(
            "small" => $smallImageURL,
            "big" => $bigImageURL
        );
        
        $this->photo = $photo;
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getStartDate()
    {
        return $this->startDate;
    }

    public function getStartTime()
    {
        return $this->startTime;
    }

    public function getEndDate()
    {
        return $this->endDate;
    }

    public function getEndTime()
    {
        return $this->endTime;
    }

    public function getPublishedDate()
    {
        return $this->publishedDate;
    }

    public function getPublishedTime()
    {
        return $this->publishedTime;
    }

    public function getPublished()
    {
        return $this->published;
    }

    public function getPhoto()
    {
        return $this->photo;
    }
}

?>
