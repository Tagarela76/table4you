<?php

namespace Table\CoreDomain\Restaurant;

class Restaurant
{
    private $id;

    private $name;

    private $city;
    
    private $street;
    
    private $house;
    
    private $workHoursFrom;

    private $workHoursTo;
    
    private $kitchens=array();
    
    private $category;
    
    private $photo;
    
    private $additionalServices=array();
    
    private $address;
    
    private $latitude;
    
    private $longitude;
    
    private $menusPhotos=array();

    public function __construct($id, $name, $city, $street, $house, 
            $workHoursFrom, $workHoursTo, $kitchens, $category, $photo, 
            $additionalServices, $address, $latitude, $longitude, $menusPhotos)
    {
        $this->id = $id;
        $this->name = $name;
        $this->city  = $city;
        $this->street = $street;
        $this->house  = $house;
        $this->workHoursFrom = $workHoursFrom;
        $this->workHoursTo = $workHoursTo;
        $this->kitchens  = $kitchens;
        $this->category = $category;
        $this->photo  = $photo;
        $this->additionalServices = $additionalServices;
        $this->address  = $address;
        $this->latitude = $latitude;
        $this->longitude  = $longitude;
        $this->menusPhotos  = $menusPhotos;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function getStreet()
    {
        return $this->street;
    }

    public function getHouse()
    {
        return $this->house;
    }

    public function getWorkHoursFrom()
    {
        return $this->workHoursFrom;
    }

    public function getWorkHoursTo()
    {
        return $this->workHoursTo;
    }

    public function getKitchens()
    {
        return $this->kitchens;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function getAdditionalServices()
    {
        return $this->additionalServices;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function getMenusPhotos()
    {
        return $this->menusPhotos;
    }

}