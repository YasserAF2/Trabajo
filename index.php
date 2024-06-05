<?php

include_once 'vendor/autoload.php';
require_once 'model/Db.php';
require_once 'config/config.php';
require_once 'model/Empleado.php';
require_once 'model/Trace.php';
require_once 'model/Peticiones.php';
require_once 'controller/controlador.php';

if (!isset($_GET["action"])) $_GET["action"] = constant("DEFAULT_ACTION");

$controlador = new controlador();

$dataToView = array();
$dataToView  = $controlador->{$_GET["action"]}();

require_once 'view/template/header.php';
require_once 'view/' . $controlador->view . '.php';
require_once 'view/template/footer.php';
