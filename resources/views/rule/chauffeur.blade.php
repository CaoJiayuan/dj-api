<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
  <title>酒后代驾</title>
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
    <span>起步价 <img src="{{ asset('and.png') }}" alt=""> 里程价</span>
  </div>
  <div class="price" style="padding-bottom: 50px">
    <table>
      <tr class="price-title">
        <td><span>起步价</span></td>

        <td class="last-td"><span>里程价</span></td>
      </tr>
      @foreach($data as $item)
        <tr>
          <td><span>{{ $item['init_price'] / 100 }}元（含{{ $item['limit'] }}公里）</span></td>

          <td class="last-td"><span>每超出{{ $item['limit2'] }}公里加收{{ $item['distance_price']/100 }}元,不足{{ $item['limit2'] }}公里按{{ $item['limit2'] }}计算,以此类推</span></td>
        </tr>
        @break
      @endforeach


    </table>
  </div>

</div>


</body>
</html>