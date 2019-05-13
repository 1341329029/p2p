<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="/css/app.css">
  </head>
  <body class="container">
      <h1>订单平台</h1>
      <form method=post action="http://127.0.0.1/myphp/pay/index.php">
      <p><input type=hidden name=v_mid value="{{$v_mid}}"></p>
      <p>订单编号<input type=text name=v_oid value="{{$v_oid}}"></p>
      <p>订单总金额<input type=text name=v_amount value="{{$v_amount}}"></p>
      <p><input type=hidden name=v_moneytype value="{{$v_moneytype}}"></p>
      <p><input type=hidden name=v_url value="{{$v_url}}"><p>
      <p><input type=hidden name=v_md5info value="{{$v_md5info}}"><p>
      <input type="submit" name="name" class="btn btn-primary" value="立即支付">
  </body>
</html>
