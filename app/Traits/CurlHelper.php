<?php
/**
 * CurlHelper.php
 * Date: 16/6/13
 * Time: 上午10:08
 */

namespace App\Traits;


trait CurlHelper
{

  public $curlResponse;
  public $jsonDecode = false;

  protected $options = [
    'header'  => CURLOPT_HTTPHEADER,
    'referer' => CURLOPT_REFERER,
  ];

  /**
   * @param $url
   * @param bool $post
   * @param array $data
   * @param array $options
   * @return array|string
   */
  public function curl($url, $post = false, array $data = [], array $options = [])
  {
    $startAt = microtime(true);
    set_time_limit(0);
    $ch = curl_init();

    $pre = '?';
    if (strpos($url, '?') !== false) {
      $pre = '&';
    }

    if ($data && !$post) {
      $queryString = http_build_query($data);
      $url .= $pre . $queryString;
    }

    curl_setopt($ch, CURLOPT_URL, $url);

    if ($data && $post) {
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }

    foreach ($this->options as $key => $option) {
      if (array_key_exists($key, $options)) {
        curl_setopt($ch, $option, $options[$key]);
      }
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    $result = curl_exec($ch);
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($result === false) {
      $error = curl_error($ch);
      curl_close($ch);

      return [
        'respond' => false,
        'error'   => $error,
      ];
    }
    curl_close($ch);

    $header = substr($result, 0, $headerSize);

    $header = $this->httpParseHeaders($header);
    $content = substr($result, $headerSize);

    $this->curlResponse = response($content, $code, $header);

    if ($this->jsonDecode) {
      $decoded = json_decode($content, true);
      if ($decoded) {
        $content = $decoded;
      }
    }

    $responseTime = microtime(true) - $startAt;

    return [
      'respond'       => true,
      'code'          => $code,
      'response_time' => round($responseTime, 3) . 'ms',
      'header'        => $header,
      'content'       => $content,
    ];
  }


  public function httpParseHeaders($headers)
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