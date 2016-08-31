<?php

namespace Ufox;

use Illuminate\Support\Facades\Facade;

class AuditoriaFacades extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Auditoria';
    }
}