<?php
$email = $_POST['userEmail'];
$senha = $_POST['senha'];
include_once("connection.php");
session_start();
session_abort();
session_destroy();
$cadastro = "SELECT * FROM usuario WHERE email=?";//email que o usuário informou no login.
$stmt = mysqli_prepare($conexao, $cadastro);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$rows = mysqli_num_rows($result);
$row = mysqli_fetch_array($result);

if ($rows == 0) {
    header("Location: erro.php?code=07");//se o email não existir no banco de dados. O usuário não possuí cadastro
} else {
    if ($senha != $row['senha']) {
        header("Location: erro.php?code=08");
    } else {
        session_start();//Se o email existir, o usuário loga no site.
        $_SESSION["userEmail"] = $email;
        $_SESSION["senha"] = $senha;
        $_SESSION["nome"] = $row["nome"];
        $_SESSION["CPF"] = $row["CPF"];
        $_SESSION["TGM"] = $row["TGM"];
        header("Location: ../userdashboard.php");
    }
}
mysqli_free_result($result);
mysqli_close($conexao);

?>
