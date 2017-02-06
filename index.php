<?php

include 'view.php';

$view = new View();
// start use
echo $view->render('tpl/main.php', array('search_text' => '', 'videos' =>  ''));
?>