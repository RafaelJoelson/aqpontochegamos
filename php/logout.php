<?php 
   session_start();//Inicia Sessão
   session_destroy();//Destroí ela.
   header('Location: ../login.php');
   exit();
?>