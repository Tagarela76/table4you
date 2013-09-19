<?php

namespace Table\CoreDomain\Restaurant;

interface RestaurantRepository
{
    public function findAll();
    
    public function findOneById($id);
}