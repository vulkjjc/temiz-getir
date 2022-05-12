<?php

namespace App\DTO\Email;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\Request;

use App\Interface\DTO\RequestDTOInterface;
use App\Validator\User as UserAssert;

class EmailVerifyRequestDTO implements RequestDTOInterface
{
    #[UserAssert\UserId]
    private string $userId;

    #[Assert\Url(message: "URL is invalid.")]
    private string $requestUri;

    public function __construct(Request $request) 
    {
        $this->userId = $request->query->get("user-id");
        $this->requestUri = $request->getUri();
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

    public function getRequestUri(): string
    {
        return $this->requestUri;
    }
}
