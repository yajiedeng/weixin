<?php

namespace App\Http\Controllers\Dadao;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ZhimaController extends Controller
{
    public function zhima()
    {
        return view('dadao.zhima');
    }
}
