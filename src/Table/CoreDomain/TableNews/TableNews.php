<?php

namespace Table\CoreDomain\TableNews;

class TableNews
{
    private $id;

    private $dateTime;

    private $title;
    
    private $content;
    
    private $pictureURL;

    public function __construct($id, $dateTime, $title, $content, $pictureURL)
    {
        $this->id = $id;
        $this->dateTime = $dateTime;
        $this->title  = $title;
        $this->content = $content;
        $this->pictureURL = $pictureURL;
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

}