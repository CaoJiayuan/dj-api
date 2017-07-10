<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" >


    <title>城际约车</title>
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
    <h4>顺路车</h4>
    <div class="price" >
        <table>
            <tr class="price-title">
                <td><span>车型</span></td>
                <td><span>起步价</span></td>
                <td  class="last-td"><span>里程价</span></td>
            </tr>
          @foreach($data as $item)
              @if($item['type']==TYPE_JOURNEY)
                <tr>
                  <td><span>---</span></td>
                  <td><span>{{ $item['init_price'] /100 }}元<br>（含10公里）</span></td>
                  <td class="last-td">
                      <span>{{ $item['less_price'] /100 }}元每公里<br> ({{$item['limit2']}}公里内）
                      </span><br>
                      <span>{{ $item['distance_price'] /100 }}元每公里<br> （超过{{$item['limit2']}}公里）
                      </span>
                  </td>
                </tr>
              @endif
          @endforeach
        </table>
    </div>
    <span class="warning">注意：顺路车不拼车按以上价格<i style="font-style: normal; color: red;font-size: 16px"> 2 </i>倍计算</span>
    <h4>专线车</h4>
    <div class="price" >
        <table>
            <tr class="price-title">
                <td><span>车型</span></td>
                <td><span>起步价</span></td>
                <td  class="last-td"><span>里程价</span></td>
            </tr>
            @foreach($data as $item)
                @if($item['type']==TYPE_JOURNEY_SPECIAL)
                    <tr>
                        <td>
                            @if($item['car_model_id']==0)
                                A级车
                            @elseif($item['car_model_id']==1)
                                B级车
                            @elseif($item['car_model_id']==2)
                                C级车
                            @else
                                商务车
                            @endif
                        </td>
                        <td><span>{{ $item['init_price'] /100 }}元<br>（含10公里）</span></td>
                        <td class="last-td">
                          <span>{{ $item['less_price'] /100 }}元每公里<br> ({{$item['limit2']}}公里内）
                          </span><br>
                          <span>{{ $item['distance_price'] /100 }}元每公里<br> （超过{{$item['limit2']}}公里）
                          </span>
                        </td>
                    </tr>
                @endif
            @endforeach
        </table>
    </div>
    <span class="warning">注意：专线车不拼车按以上价格<i style="font-style: normal; color: red;font-size: 16px"> 4 </i>倍计算</span>
    <h4>包车</h4>
    <div class="price" >
        <table>
            <tr class="price-title">
                <td><span>车型</span></td>
                <td><span>起步价</span></td>
                <td  class="last-td"><span>里程价</span></td>
            </tr>
            @foreach($data as $item)
                @if($item['type']==TYPE_JOURNEY_ONLY)
                    <tr>
                        <td>
                            @if($item['car_model_id']==0)
                                A级车
                            @elseif($item['car_model_id']==1)
                                B级车
                            @elseif($item['car_model_id']==2)
                                C级车
                            @else
                                商务车
                            @endif
                        </td>
                        <td><span>{{ $item['init_price'] /100 }}元<br>（含10公里）</span></td>
                        <td class="last-td">
                          <span>{{ $item['less_price'] /100 }}元每公里<br> （{{$item['limit2']}}公里内）
                          </span><br>
                          <span>{{ $item['distance_price'] /100 }}元每公里<br> （超过{{$item['limit2']}}公里）
                          </span>
                        </td>
                    </tr>
                @endif
            @endforeach
        </table>
    </div>
</div>

</body>
</html>