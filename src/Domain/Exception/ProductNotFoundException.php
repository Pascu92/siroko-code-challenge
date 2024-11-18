<?php

namespace App\Domain\Exception;

use Exception;

class ProductNotFoundException extends Exception
{
    protected $message = 'Product not found';
}
