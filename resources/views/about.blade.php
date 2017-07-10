<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- 优先使用 IE 最新版本和 Chrome -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <!-- 为移动设备添加 viewport -->
    <meta name="viewport" content="width=device-width,initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
    <title>关于我们</title>
    <style rel="stylesheet" type="text/css">

        *{
            margin: 0px;
            padding: 0px;
        }
        .center{
            padding: 10px 20px;
            text-align: center;
        }
        img{
            width: 180px;
        }
        p{
            text-align: left;
            margin:20px 0px;
        }
        .tell{
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="center">
    <img src="{{ asset('logo.png') }}" alt="点将台">
    <p>
               “点将台城际约车APP平台”主要解决司机与乘客之间找车、用车、打车方便双方的联动问题，并运用充值赠送积分的方式降低乘客出行费用，同时提高司机营运收益，以及促使物价回归的综合性民生问题。
    </p>
<p class="tell">
  <?php $config = config('site') ?>
    <p>电话：{{ $config['phone'] }}</p>

</p>
</div>
</body>
</html>