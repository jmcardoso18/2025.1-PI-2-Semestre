<?php
session_start();
session_unset();
session_destroy();
header('Location: /Projeto/MVSinfo/Projeto/php/usuario/login_view.php');
exit;
?>