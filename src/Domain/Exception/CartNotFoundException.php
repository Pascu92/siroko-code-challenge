<?php

namespace App\Domain\Exception;

use Exception;

class CartNotFoundException extends Exception
{
    protected $message = 'Cart not found';
}
