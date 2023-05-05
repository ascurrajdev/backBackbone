<?php

namespace App\Http\Controllers\Api;

use App\Facades\ZipCode;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ZipCodesController extends Controller
{
    public function show($zipCode){
        $result = ZipCode::getDataAndMapPrettyByZipCode($zipCode);
        if(empty($result)){
            abort(404);
        }
        return $result;
    }
}
