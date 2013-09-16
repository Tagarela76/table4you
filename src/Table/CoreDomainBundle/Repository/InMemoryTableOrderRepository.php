<?php

namespace Table\CoreDomainBundle\Repository;

use Table\CoreDomain\TableOrder\TableOrder;
use Table\CoreDomain\TableOrder\TableOrderRepository;

class InMemoryTableOrderRepository implements TableOrderRepository
{
    private $orders;

    public function __construct()
    {
        $this->orders[] = new TableOrder(
            "1", '23/09/2013', '18-00', 'Кафе-бар', 'Днепропетровск, Артема, 13'
        );
        $this->orders[] = new TableOrder(
            "1", '15/10/2013', '20-00', 'ресторан "Щелкунчик"', 'Одесса, Тенистая, 15'
        );
    }

    public function findAll()
    {
        return $this->orders;
    }
}