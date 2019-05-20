<?php
spl_autoload_register(function($class_name){
//    require_once($_SERVER['DOCUMENT_ROOT'] . '/pdf_tracking/class/' . $class_name . '.php');
        require_once($class_name . '.php');

});
?>