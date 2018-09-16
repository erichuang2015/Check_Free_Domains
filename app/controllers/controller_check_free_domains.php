<?php

class Controller_Check_Free_Domains extends Controller {

  // Строку превращаем в массив, предварительно переведя в нижний регистр
  // Слова состоят из лат.букв, цифр и дефисов, остальное трактуем как разделители
  // Убираем дубликаты, на пропуск ключей не заморачиваемся
  private function words_to_array($s = "") {
    $matches = array();
    preg_match_all('!([a-z0-9-]+)!', strtolower($s), $matches);
    return array_unique($matches[0]);
  }

  function __construct() {
    $this->model = new Model_Check_Free_Domains(
      $this->words_to_array($_POST['list1']),
      $this->words_to_array($_POST['list2']),
      array_key_exists('box1', $_POST) && $_POST['box1'] == 'on',
      $_POST['method']);
    $this->view = new View();
  }

  function action_index() {
    $data = $this->model->get_data();		
    $this->view->generate('check_free_domains_view.php', 'template_view.php', $data);
  }
}

?>

