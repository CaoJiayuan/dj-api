<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" >
    <title>{{ $type['name'] }}</title>
  <link href="{{ asset('css/main.css') }}" rel="stylesheet" type="text/css">
  <link href="{{ asset('css/normalize.css') }}" rel="stylesheet" type="text/css">
</head>
<body style="padding-bottom: 50px">
<div class="content">
    <div class="header">
        <hr>
        <span>计价规则</span>
        <hr>
    </div>
    <div class="name">
        <span>起步价 <img src="{{ asset('and.png') }}" alt=""> 里程价</span>
    </div>
    <h4>{{ $type['name'] }}</h4>
    <div class="price" style="padding-bottom: 50px">
        <table>
            <tr class="price-title">
                <td><span>货车尺寸</span></td>
                <td><span>起步价</span></td>
                <td  class="last-td"><span>里程价</span></td>
            </tr>

          @foreach($rule as $item)
            <?php
              $size = $item['size'];
              $s = ($size['length'] /100) . 'x' . ($size['width'] /100) . 'x' . ($size['height'] /100);
              $s = str_replace('x0','',$s);
            ?>
            <tr>
              <td><span>{{ $s }}</span></td>
              <td><span>{{ $item['init_price'] / 100 }}元</span><br>(含10公里)</td>
              <td class="last-td"><span>{{ $item['distance_price'] / 100 }}元/公里</span></td>
            </tr>
          @endforeach
        </table>
    </div>
    <span class="warning">注意：是否有入城证，超重加价部分由双方协商解决</span>

</div>


</body>
</html>