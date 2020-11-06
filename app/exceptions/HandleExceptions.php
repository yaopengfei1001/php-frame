<?php

namespace App\exceptions;

use core\HandleExceptions as BaseHandleExceptions;

class HandleExceptions extends BaseHandleExceptions
{
    protected $ignore = [
        ErrorMessageException::class,
    ];
}