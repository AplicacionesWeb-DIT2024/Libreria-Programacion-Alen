<?php
// Reenviar las solicitudes de Vercel a index.php normalmente

error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors', '0');
require __DIR__ . '/../public/index.php';