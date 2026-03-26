<?php

// ladění na lokálním serveru
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// načtení třídy routeru, která se postará o zpracování URL
require_once '../core/App.php';

// inicializace aplikace a spuštění procesu routování
$app = new App();