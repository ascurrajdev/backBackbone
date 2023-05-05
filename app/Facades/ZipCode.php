<?php
namespace App\Facades;

use App\Contracts\ZipCodeContract;
use Illuminate\Support\Facades\Facade;

class ZipCode extends Facade{
    protected static function getFacadeAccessor(){
        return ZipCodeContract::class;
    }
}