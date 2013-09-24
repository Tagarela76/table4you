<?php

namespace Table\CoreDomainBundle\Repository;

use Table\CoreDomain\TableReserve\TableReserve;
use Table\CoreDomain\TableReserve\TableReserveRepository;

class InMemoryTableReserveRepository implements TableReserveRepository
{
    private $reservs;

    public function __construct()
    {
        $this->reservs['1'] = new TableReserve(
            "1", 'http://bla/bla', '3'
        );
        $this->reservs['2'] = new TableReserve(
            "2", 'http://bla/bla', '2'
        );
    }

    public function find($id)
    {
        return $this->reservs[$id];
    }
}