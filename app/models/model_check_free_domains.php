<?php

class Model_Check_Free_Domains extends Model {

  private $list1;
  private $list2;
  private $defis;
  private $method;
  private $names;

  private function prepare_names() {
    $this->names = array();
    foreach ($this->list1 as $word1) {
      $this->names[] = $word1.'.com';  // проверим и одинарные слова из 1го списка
      foreach ($this->list2 as $word2) {
        $this->names[] = $word1.$word2.'.com';
        $this->names[] = $word2.$word1.'.com';    // зеркалим
        if ($this->defis) {   // то же, но с дефисом между слов
          $this->names[] = $word1.'-'.$word2.'.com';
          $this->names[] = $word2.'-'.$word1.'.com';
        }
      }
    }
    foreach ($this->list2 as $word) $this->names[] = $word.'.com';  // проверим и одинарные слова из 2го списка
    $this->names = array_unique($this->names);  // на случай наличия одинаковых слов в обоих списках
    sort($this->names);
  }

  function __construct($l1, $l2, $d, $m) {
    $this->defis = $d;
    $this->list1 = $l1;
    $this->list2 = $l2;
    $this->method = $m;
    $this->prepare_names();
  }
  
  private function is_available_by_whois($name) {
    $server = 'whois.internic.net';
    if ($conn = fsockopen ($server, 43)) {
      fputs($conn, $name."\r\n");
      $output = fgets($conn,128)[0];
      fclose($conn);
    } else
      die('Ошибка: Не могу подключиться к '.$server.'!');  // не для реальной работы
    return $output == 'N';		// От "No matches"
  }
  
  /* работает очень долго
  private function is_available_by_whois2($name) {
    $api = 'http://api.whois.vu/?q='.$name.'&clean';
    $json = file_get_contents($api);
    $obj = json_decode($json);
    return $obj->available == 'yes';
  }
  */
    
  private function is_available_by_dns($name) {
    return gethostbyname($name) == $name;
  }
  
  private function availables_by_api($domains) {
    $url = "https://api.godaddy.com/v1/domains/available?checkType=FAST";

    /* ключ для ote-api.godaddy.com
    $header = array(
      'Authorization: sso-key 3mM44UYhpyYPJD_TVLsuJXadgBhEE7AkdqDVe:TVLv3QdTTQrqHNaJHN9txU',
      'Content-Type: application/json'
    );
    */
    
    $header = array(
      'Authorization: sso-key dLiaSJnBQRMs_HdBiKa7HLcYG9zRdgqLGX:HdDMGqtnTxkeocN3yeKi7',
      'Content-Type: application/json'
    );


    //open connection
    $ch = curl_init();
    $timeout=60;

    //set the url and other options for curl
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);  
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST'); // Values: GET, POST, PUT, DELETE, PATCH, UPDATE 
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($domains));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

    //execute call and return response data.
    $result = curl_exec($ch);

    //close curl connection
    curl_close($ch);

    // decode the json response
    $dr = json_decode($result, true);
    
    if (!$dr || !array_key_exists('domains', $dr))
      die('Ошибка: проблемы с откликом API!');  // не для реальной работы
    
    $freenames = array();

    foreach ($dr['domains'] as $domain)
      if ($domain['available']) $freenames[] = $domain['domain'];
    
    sort($freenames);
    
    return $freenames;    
  }

  public function get_data() {	
    $freenames = array();
    $names_counter = count($this->names);
    if ($this->method == 'api')
      for ($i = 0; $i < $names_counter; $i+=500) // каждый запрос - до 500 доменов
        $freenames = array_merge($freenames, $this->availables_by_api(array_slice($this->names, $i, 500)));
    else
      foreach ($this->names as $name)
        if ($this->{'is_available_by_'.$this->method}($name))
          $freenames[] = $name;
    return array(			
      'list1' => $this->list1,
      'list2' => $this->list2,
      'defis' => $this->defis,
      'method' => $this->method,
      'names_counter' => $names_counter,
      'freenames' => $freenames);
  }

}
