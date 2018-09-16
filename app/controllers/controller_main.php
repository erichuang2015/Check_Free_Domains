<?php

class Controller_Main extends Controller {

  function action_index() {
    $this->view->generate('check_free_domains_view.php', 'template_view.php');
  }
}

?>