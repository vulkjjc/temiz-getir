<?php

namespace App\Service;

use Doctrine\Persistence\ManagerRegistry;

class AuthService
{
    private ManagerRegistry $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }
}
