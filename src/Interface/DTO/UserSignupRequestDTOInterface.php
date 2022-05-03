<?php

namespace App\Interface\DTO;

interface UserSignupRequestDTOInterface
{
    public function getName();
    public function getEmail();
    public function getPassword();
    public function getPasswordRepeat();
}
