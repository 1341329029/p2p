<?php

namespace App\Http\Middleware;

use Closure;
use Nette\Mail\Message;
use Nette\Mail\SmtpMailer;
class EmailMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $rs = $next($request);
        $mail = new Message;
        $mail->setFrom('John <yh18243357976@163.com>')
            ->addTo($request->user()->email)
            // ->addTo("1341329029@qq.com")
            ->setSubject('Test Middleware')
            ->setBody("Hello, Your order has been accepted.");
        $mailer = new SmtpMailer([
                  'host' => 'smtp.163.com',
                  'username' => 'yh18243357976@163.com',
                  'password' => 'yh18243357976'
                  // 'secure' => 'ssl'
                  // 'context' =>  [
                  //     'ssl' => [
                  //         'capath' => '/path/to/my/trusted/ca/folder',
                  //      ],
                  // ],
          ]);
        $mailer->send($mail);
        return $rs;
    }
}
