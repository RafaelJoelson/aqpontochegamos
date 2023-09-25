<?php

$hostname="200.131.251.11";
$user="grupo2";
$password="Tec2023@10";
$dbname= "Tec2023aqponto";
$port = "3341";

//criar a conexão
$conexao = mysqli_connect($hostname,$user,$password,$dbname,$port);

if(!$conexao){
    header("Location: erro.php?code=04");
}

?>