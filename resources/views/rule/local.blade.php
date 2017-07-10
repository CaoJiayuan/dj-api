<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
  <title>市内打车</title>

  <link href="{{ asset('css/main.css') }}" rel="stylesheet" type="text/css">
  <link href="{{ asset('css/normalize.css') }}" rel="stylesheet" type="text/css">

</head>
<body>
<div class="content">
  <div class="header">
    <hr>
    <span>计价规则</span>
    <hr>
  </div>
  <div class="name">
    <span>起步价 <img src="{{ asset('and.png') }}" alt=""> 里程价 <img src="{{ asset('and.png') }}" alt=""> 时长费</span>
  </div>
  <div class="price">
    <table>

      <tr class="price-title">
        <td><span>车类型</span></td>
        <td><span>起步价</span></td>
        <td><span>里程价</span></td>
        <td class="last-td"><span>时长费</span></td>
      </tr>
      @foreach($data as $item)
        <tr>
          <td><span>{{ $item['car']['name'] }}</span></td>
          <td><span>{{ $item['init_price'] / 100 }}元 <br> (含{{ $item['limit'] }}公里）</span></td>
          <td><span>{{ $item['distance_price'] /100 }}元/公里</span></td>
          <td class="last-td"><span>{{ $item['duration_price'] /100 }}元/分钟</span></td>
        </tr>
      @endforeach
    </table>
  </div>

</div>


</body>
</html>