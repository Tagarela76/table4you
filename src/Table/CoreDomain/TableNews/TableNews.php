<?php

namespace Table\CoreDomain\TableNews;

class TableNews
{
    private $id;

    private $dateTime;

    private $title;
    
    private $content;
    
    private $pictureURL;

    private $restaurantId;

    public function __construct($id, $dateTime, $title, $content, $pictureURL, $restaurantId)
    {
        $this->id = $id;
        $this->dateTime = $dateTime;
        $this->title  = $title;
        $this->content = $content;
        $this->pictureURL = $pictureURL;
	$this->restaurantId = $restaurantId;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDateTime()
    {
        return $this->dateTime;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setDateTime($dateTime)
    {
        $this->dateTime = $dateTime;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }
    
    public function getPictureURL()
    {
        return $this->pictureURL;
    }

    public function setPictureURL($pictureURL)
    {
        $this->pictureURL = $pictureURL;
    }

    public function getRestaurantId()
    {
        return $this->restaurantId;
    }

    public function setRestaurantId($restaurantId)
    {
        $this->restaurantId = $restaurantId;
    }

}
