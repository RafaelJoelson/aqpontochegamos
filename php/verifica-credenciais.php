<?php
include_once("connection.php");
$email = $_GET['email'];
$CPF = $_GET['CPF'];
$cadastro = "SELECT * FROM usuario WHERE email='$email' OR CPF='$CPF'";
$resultado = mysqli_query($conexao, $cadastro) or die("Não foi possível efetuar a consulta!");
$rows = mysqli_num_rows($resultado);

if ($rows == 0) {
    header("Location: erro.php?code=07");
    exit;
} else {
    $row = mysqli_fetch_assoc($resultado);
    $senha = $row['senha'];
    
    session_start();
    $_SESSION["userEmail"] = $email;
    $_SESSION["nome"] = $row["nome"];
    $_SESSION["CPF"] = $row["CPF"];

    header("Location: change_password_or_secret_question.php");
    exit;
}

mysqli_free_result($resultado);
mysqli_close($conexao);
?>
