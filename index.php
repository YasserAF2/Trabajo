<?php

require_once 'model/Db.php';
require_once 'config/config.php';
require_once 'model/Empleado.php';
require_once 'model/Carnet.php';
require_once 'model/Cuartillo.php';
require_once 'model/Horario.php';
require_once 'model/Turno.php';
require_once 'controller/trace.php';

if (!isset($_GET["action"])) $_GET["action"] = constant("DEFAULT_ACTION");

$controlador = new trace();

$dataToView = array();
$dataToView  = $controlador->{$_GET["action"]}();

require_once 'view/template/header.php';
require_once 'view/' . $controlador->view . '.php';
require_once 'view/template/footer.php';
