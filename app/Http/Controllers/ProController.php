<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pro;
use App\Att;
use DB;
use Auth;
use Validator;
use Gregwar\Captcha\CaptchaBuilder;
use App\Rules\SendMobile;
class ProController extends Controller
{
    /**
     * [获取借款页面]
     * @method jie
     * @return [type] [description]
     */
    public function jie(){
      return view('woyaojiekuan');
    }
    /**
     * [jiePost 借款逻辑]
     * @method jiePost
     * @return [type]  [description]
     */
    public function jiePost(){
      $this->validate(request(),[
          'age'=>'in:15,40,80',
          'money'=>'required|integer|digits_between:5,7',
          'mobile'=>'required|regex:/1[3578]\d{9}/',
          'imgcode'=>['required',new SendMobile],       
      ]
    );
      // $validator = Validator::make(request()->all(),[
      //     'age'=>'in:15,40,80',
      //     'money'=>'required|integer|digits_between:5,7',
      //     'mobile'=>'required|regex:/1[3578]\d{9}/',
      //     // 'imgcode'=>['required',new SendMobile],
      // ]
      // [
      //   'in'=>'请输入是三个value值之间之一',
      //   'money.required'=>'金钱必须填写',
      //   'mobile.required'=>'手机号必须填写！'
      // ]
    // );
      // if($validator->fails()){
      //   return back()->withErrors($validator)->withInput();
      // }
      $user = Auth::user();
      //开启事务
      DB::beginTransaction();
      try {
        $pro = Pro::create([
              'uid'=>$user->uid,
              'name'=>$user->name,
              'money'=>request('money')*100,
              'mobile'=>request('mobile'),
              'pubtime'=>time()
            ]);

      } catch(\Exception $e)
      {
          //遇到异常,抛出异常并回滚
          DB::rollback();
          throw $e;
      }
      try {
        $att = Att::create([
              'pid'=>$pro->pid,
              'uid'=>$user->uid,
              'age'=>request('age')
            ]);

      } catch(\Exception $e)
      {
          //遇到异常,抛出异常并回滚
          DB::rollback();
          throw $e;
      }
      //提交事务
      DB::commit();
      return "申请成功";
    }
    public function touzi($pid)
    {
      $pro =Pro::find($pid);
      return view('lijitouzi',compact('pro'));
    }
    public function touziPost(Request $req,$pid){
        $user = $req->user();
        $bid = new \App\Bid();
        $pro = \App\Pro::where('pid',$pid)->first();

        $md5 = $req->v_oid.$req->v_pstatus.$req->v_amount.$req->v_moneytype.'%()#QOKFDLS:1*&U';
        $md5 = md5($md5);
        if(strtoupper($md5) !== $req->v_md5str){
          return '参数不合法';
        }
        $bid->uid = $user->uid;
        $bid->pid = $pid;
        $bid->title = $pro->title;
        $bid->money = $req->v_amount * 100;
        $bid->pubtime = time();
        $bid->save();

        $pro->recive += $bid->money;
        $pro->save();
        if($pro->recive == $pro->money){
          $this->touziDone($pid);
        }
        return '购买成功';
    }
    public function touziDone($pid)
    {
      //1.修改项目状态
      $pro =\App\Pro::find($pid);
      $pro->status = 2;
      $pro->save();
      $amount = $pro->money / $pro->hrange + ($pro->money * $pro->rate)/1200;
      $data = ['uid'=>$pro->uid,'pid'=>$pid,'title'=>$pro->title];
      $data['amount'] = $amount;
      for($i=1;$i<=$pro->hrange;$i++){
        $paydate = date('Ymd',strtotime("+ $i months"));
        $data['paydate'] = $paydate;
        DB::table('hks')->insert($data);
      }
      //3.为投资人生成预收益账单
      $bid = \App\Bid::where('pid',$pid)->get();
      $row = ['pid'=>$pid,'title'=>$pro->title];
      $enddate = date("Ymd",strtotime("+ $pro->hrange months"));
      $row['enddate'] = $enddate;
      foreach($bid as $b){
        $row['uid'] = $b->uid;
        $row['amount'] = ($b->money * $pro->rate)/36500;
        DB::table('tasks')->insert($row);
      }
    }
    public function myzd(){
      $hks = DB::table('hks')->where('uid',Auth::Id())->paginate(5);
      return view('myzd',compact('hks'));
    }
    public function mytz(){
      $bid = \App\Bid::where('bids.uid',Auth::Id())->whereIn('status',[1,2])->join('projects','bids.pid','=','projects.pid')->get(['bids.*','projects.status']);
      return view('mytz',compact('bid'));
    }
    public function mysy(){
      $grows = DB::table('grows')->where('uid',Auth::Id())->get();
      return view('mysy',compact('grows'));
    }
    public function pay(){
      $pid = request('pid');
      $data = [];
      $data['v_amount'] = request('money');
      $data['v_moneytype'] = 'CNY';
      $data['v_oid'] = date('YmdHis').mt_rand(1000,9999);
      $data['v_mid'] = '20272562';
      $data['v_url'] = "http://p2p.com/touzi/".$pid;
      $data['key'] = '%()#QOKFDLS:1*&U';
      $data['v_md5info'] = strtoupper( md5(implode($data)) );
      return view('pay',$data);
    }

    public function captcha(){
      //创建验证码
      $builder = new CaptchaBuilder;
      $builder->build();
      session(['yzm'=> strtoupper($builder->getPhrase())]);
      //在模板显示
      header('Content-type: image/jpeg');
      $builder->output();
    }

}