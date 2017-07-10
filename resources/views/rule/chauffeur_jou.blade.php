<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" >
  <title>长途代驾</title>
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

  <div class="price" >
    <table class="re-tb">
      <tr class="price-title">
        <td><span>里程价</span></td>
        <td  class="last-td"><span>返程价</span></td>
      </tr>
      @foreach($data as $item)
        <tr>
          <td><span>{{ $item['limit'] }}公里以上每百公里 <label>{{ $item['more_price'] /100 }}</label> 元</span></td>
          <td  class="last-td"><span>返程{{ $item['limit'] }}公里以上每百公里<label >{{ $item['more_price_back'] /100 }}</label>元</span></td>
        </tr>
        <tr>
          <td><span>低于{{ $item['limit'] }}公里每百公里 <label>{{ $item['less_price'] /100 }}</label> 元</span></td>
          <td class="last-td"><span >低于{{ $item['limit'] }}公里每百公里<label >{{ $item['less_price_back'] /100 }}</label>元</span></td>
        </tr>
      @break
      @endforeach
    </table>
  </div>

</div>


</body>
</html>