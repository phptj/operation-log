<?php

namespace TangJun\SysAuditLog\Facades;

use Illuminate\Support\Facades\Facade;

class SysAuditLogFacade extends Facade {


    protected static function getFacadeAccessor(){
        return 'SysAuditLog';
    }
}