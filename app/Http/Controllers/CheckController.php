<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pro;
use DB;
class CheckController extends Controller
{
    public function prolist()
    {
    	$pro = Pro::orderBy('pid','desc')->get();
    	return view('prolist',compact('pro'));
    }
    public function check($pid)
    {
    	$mon =Pro::where('pid',$pid)->pluck('money');
    	return view('shenhe',compact('mon'));
    }
	public function checkPost($pid){
      $pro = \App\Pro::find($pid);
      $att = \App\Att::where('pid',$pid)->first();

      DB::beginTransaction();
      try {
          $pro->title = request('title');
          $pro->rate = request('rate');
          $pro->hrange = request('hrange');
          $pro->status = request('status');
          $pro->save();

      } catch(\Exception $e)
      {
          //遇到异常,抛出异常并回滚
          DB::rollback();
          throw $e;
      }
      try {
        $att->title = request('title');
        $att->realname = request('realname');
        $att->gender = request('gender');
        $att->udesc = request('udesc');
        $att->save();
      } catch(\Exception $e)
      {
          //遇到异常,抛出异常并回滚
          DB::rollback();
          throw $e;
      }
      //提交事务
      DB::commit();
      return redirect('prolist');
    }
}

