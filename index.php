<?php

require_once "controladores/Plantilla.controlador.php";
require_once "controladores/Usuario.controlador.php";
require_once "controladores/Tipo.documento.controlador.php";


require_once "modelos/Usuario.modelo.php";
require_once "modelos/Tipo.documento.modelo.php";




$plantilla = new ControladorPlantilla();
$plantilla->ctrPlantilla();