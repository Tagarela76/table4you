<?php

namespace Table\CoreDomain\TableNews;

class TableNews
{
    private $id;

    private $dateTime;

    private $title;
    
    private $content;

    public function __construct($id, $dateTime, $title, $content)
    {
        $this->id = $id;
        $this->dateTime = $dateTime;
        $this->title  = $title;
        $this->content = $content;
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

}