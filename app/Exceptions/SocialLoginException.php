<?php

namespace App\Exceptions;

use Exception;

class SocialLoginException extends Exception
{
    public static function emailAlreadyRegistered(string $email, ?string $existingProvider = null): static
    {
        $method = $existingProvider ? ucfirst($existingProvider) : 'Email/Password';
        return new static("this email ($email) already used $method । login with ". $method);
    }
}