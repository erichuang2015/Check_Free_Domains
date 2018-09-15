<?php

class Model_Check_Free_Domains extends Model {
	
	private $list1;
	private $list2;
	private $defis;
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
	
	function __construct($l1, $l2, $d) {
		$this->defis = $d;
		$this->list1 = $l1;
		$this->list2 = $l2;
		$this->prepare_names();
	}
	
	public function get_data() {	
		$freenames = array();
		$server = 'whois.internic.net';
		foreach ($this->names as $name) {
    	if ($conn = fsockopen ($server, 43)) {
        fputs($conn, $name."\r\n");
        $output = fgets($conn,128)[0];
        fclose($conn);
    	} else
        die('Ошибка: Не могу подключиться к '.$server.'!');  // не для реальной работы
    	if ($output == 'N')		// От "No matches"
				$freenames[] = $name;
		}		
		return array(			
			'list1' => $this->list1,
			'list2' => $this->list2,
			'defis' => $this->defis,
			'names_counter' => count($this->names),
			'freenames' => $freenames);
	}

}
