<?php

namespace App\Http;

class Kernel
{
    protected $middlewareAliases = [
        'auth' => \App\Http\Middleware\Authenticate::class,
    ];
}
