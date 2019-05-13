<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index(){
      $pro = \App\Pro::where('status',1)->orderBy('pid','desc')->get();
      return view('index',compact('pro'));
    }
}