<?php
error_reporting(0);
//header('Content-Type: text/html; charset=UTF-8');
session_start();
if (is_file('./class.Main.php')) {
    require_once('./class.Main.php');
}
if (!empty($_GET['r'])) {
    $r = $_GET['r'];
} else {
    $r = '404';
}
include(Main::getRoutes($r));
?>
