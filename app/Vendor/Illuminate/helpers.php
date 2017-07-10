<?php
/**
 * Helpers.php
 * Date: 16/6/8
 * Time: 下午2:49
 */
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Collection;
use League\Fractal\TransformerAbstract;


if (!function_exists('array_forget')) {
  function array_forget($array, $keys)
  {
    foreach ((array)$keys as $key) {
      unset($array[$key]);
    }

    return $array;
  }
}


if (!function_exists('array_get_values')) {
  function array_get_values($keys, $ar, $_ = null)
  {
    if (!$ar) {
      return $ar;
    }
    $merge = [];
    $array = func_get_args();
    $k = $array[0];
    unset($array[0]);
    foreach ($array as $item) {
      if ($item instanceof Arrayable) {
        $item = $item->toArray();
      }

      $merge = array_merge($merge, (array)$item);
    }

    $result = [];
    foreach ($k as $name => $key) {

      $needle = $key;
      if (!is_numeric($name)) {
        $needle = $name;
      }

      $r = array_get($merge, $needle, null);
      $arr = explode('.', $key);
      $ke = end($arr);
      $result[$ke] = $r;
    }

    return $result;
  }
}

if (!function_exists('http_parse_header')) {
  function http_parse_header($headers)
  {
    $headersArray = explode(PHP_EOL, $headers);

    $result = [];
    foreach ((array)$headersArray as $h) {
      $partials = explode(':', $h);

      if (count($partials) > 1) {
        $result[trim($partials[0])] = trim($partials[1]);
      }
    }

    return $result;
  }
}

if (!function_exists('helper_curl')) {
  function helper_curl($url, $post = false, array $data = [], array $options = [])
  {
    $opts = [
      'header ' => CURLOPT_HTTPHEADER,
    ];

    set_time_limit(0);
    $ch = curl_init();

    if ($data && !$post) {
      $queryString = http_build_query($data);
      $url .= '?' . $queryString;
    }


    curl_setopt($ch, CURLOPT_URL, $url);

    if ($data && $post) {
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }

    foreach ($opts as $key => $option) {
      if (array_key_exists($key, $options)) {
        curl_setopt($ch, $option, $options[$key]);
      }
    }

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);


    $result = curl_exec($ch);

    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if ($result === false) {
      return curl_error($ch);
    }
    $header = substr($result, 0, $headerSize);

    $header = http_parse_header($header);
    $content = substr($result, $headerSize);

    return ['header' => $header, 'code' => $code, 'content' => $content];
  }
}

if (!function_exists('arr_get')) {
  function arr_get($arr, $key, $default = null)
  {
    if (isset($arr[$key])) {
      return $arr[$key];
    }

    return $default;
  }
}

if (!function_exists('get_formatted')) {
  /**
   * @param $model
   * @param TransformerAbstract $transformer
   * @return array
   */
  function get_formatted($model, $transformer)
  {
    if (!$model) {
      return $model;
    }
    if (!is_object($transformer)) {
      $transformer = app($transformer);
    }

    if (!$transformer instanceof TransformerAbstract) {
      throw new UnexpectedValueException('Except instance of '
        . TransformerAbstract::class . ', instance of ' . get_class($transformer) . ' giving.');
    }


    if ($model instanceof Collection) {
      $result = [];
      foreach ($model as $item) {
        $result[] = $transformer->transform($item);
      }

      return $result;
    }

    return $transformer->transform($model);
  }
}

if (!function_exists('input_all')) {
  function input_all()
  {
    return Request::all();
  }
}

if (!function_exists('input_get')) {
  function input_get($key, $default = null)
  {
    return Request::get($key, $default);
  }
}

if (!function_exists('array_for_page')) {
  function array_for_page($input, $page = 1, $size = 20)
  {
    return array_slice($input, ($page - 1) * $size, $size);
  }
}

if (!function_exists('round_fix')) {
    function round_fix ($value, $p, $model = PHP_ROUND_HALF_UP)
    {
      $before = round($value, $p, $model);

      $d = explode('.', $before, 2);

      $re = $before;
      if (isset($d[1])) {
        $l = strlen($d[1]);
        if ($j = $p - $l ) {
          for ($i = $l; $i <= $j; $i++) {
            $re .= '0';
          }
        }
      } else {
        $re .= '.';
        for ($i = 0; $i < $p; $i++) {
          $re .= '0';
        }
      }
      
      return $re;
    }
}