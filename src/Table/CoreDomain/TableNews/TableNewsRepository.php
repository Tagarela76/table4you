<?php

namespace Table\CoreDomain\TableNews;

interface TableNewsRepository
{
    public function findAll();
    public function findOneById($id);
    public function findByRestaurantId($restaurnatId);
}