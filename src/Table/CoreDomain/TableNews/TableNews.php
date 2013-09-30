<?php

namespace Table\CoreDomain\TableNews;

class TableNews
{

    private $id;
    private $startDateTime;
    private $endDateTime;
    private $title;
    private $content;
    private $pictureURL;
    private $restaurantId;
    private $city;

    public function __construct($id, $startDateTime, $endDateTime, $title, $content, $pictureURL, $restaurantId, $city)
    {
        $this->id = $id;
        $this->startDateTime = $startDateTime;
        $this->endDateTime = $endDateTime;
        $this->title = $title;
        $this->content = $content;
        $this->pictureURL = $pictureURL;
        $this->restaurantId = $restaurantId;
        $this->city = $city;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getStartDateTime()
    {
        return $this->startDateTime;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setStartDateTime($startDateTime)
    {
        $this->startDateTime = $startDateTime;
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
    
    public function getEndDateTime()
    {
        return $this->endDateTime;
    }

    public function setEndDateTime($endDateTime)
    {
        $this->endDateTime = $endDateTime;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCity($city)
    {
        $this->city = $city;
    }


}
