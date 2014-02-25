<?php 

/*
 * Define you SweetCaptcha credentials.
 * Don't have any? Sign up at http://sweetcaptcha.com and get them by email
 */

define('SWEETCAPTCHA_APP_ID', 56291); // your application id (change me)
define('SWEETCAPTCHA_KEY', '0b74066b00bbca1c1df13edde375a09c'); // your application key (change me)
define('SWEETCAPTCHA_SECRET', '7a9befff7cea8b3e99b12ebe178fdd33'); // your application secret (change me)
define('SWEETCAPTCHA_PUBLIC_URL', 'sweetcaptcha.php'); // public http url to this file


/////==== Do not change below here ===/////

/**
 * Handles remote negotiation with Sweetcaptcha.com.
 *
 * @version 1.1.0
 * @updated November 14, 2013
 */

$sweetcaptcha = new Sweetcaptcha(
  SWEETCAPTCHA_APP_ID, 
  SWEETCAPTCHA_KEY, 
  SWEETCAPTCHA_SECRET, 
  SWEETCAPTCHA_PUBLIC_URL
);

if (isset($_POST['ajax']) and $method = $_POST['ajax']) {
  echo $sweetcaptcha->$method(isset($_POST['params']) ? $_POST['params'] : array());
}

class Sweetcaptcha {
  
  private $appid;
  private $key;
  private $secret;
  private $path;
  
  const API_URL = 'sweetcaptcha.com';
  const API_PORT = 80;
  
  function __construct($appid, $key, $secret, $path) {
    $this->appid = $appid;
    $this->key = $key;
    $this->secret = $secret;
    $this->path = $path;
  }
  
  private function api($method, $params) {
    
    $basic = array(
      'method'      => $method,
      'appid'       => $this->appid,
      'key'         => $this->key,
      'path'        => $this->path,
      'user_ip'     => $_SERVER['REMOTE_ADDR'],
      'platform'    => 'php'
    );
    
    return $this->call(array_merge(isset($params[0]) ? $params[0] : $params, $basic));
  }
  
  private function call($params) {
    $param_data = "";   
    foreach ($params as $param_name => $param_value) {
      $param_data .= urlencode($param_name) .'='. urlencode($param_value) .'&'; 
    }
    
    if (!($fs = fsockopen(self::API_URL, self::API_PORT, $errno, $errstr, 10))) {
      die ("Couldn't connect to server");
    }
    
    $req = "POST /api.php HTTP/1.0\r\n";
    $req .= "Host: ".self::API_URL."\r\n";
    $req .= "Content-Type: application/x-www-form-urlencoded\r\n";
    $req .= "Referer: " . $_SERVER['HTTP_HOST']. "\r\n";
    $req .= "Content-Length: " . strlen($param_data) . "\r\n\r\n";
    $req .= $param_data;    
  
    $response = '';
    fwrite($fs, $req);
    
    while (!feof($fs)) {
      $response .= fgets($fs, 1160);
    }
    
    fclose($fs);
    
    $response = explode("\r\n\r\n", $response, 2);
    
    return $response[1];  
  }
  
  public function __call($method, $params) {
    return $this->api($method, $params);
  }
}

?>
