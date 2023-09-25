<?php
session_start();
include_once("./php/connection.php");
// Verificar se o usuário está logado

$_SESSION=array();// Limpar todas as variáveis de sessão
if (isset($_SESSION['logged_in'])) {
    // Usuário logado, realizar logout
    session_destroy(); // Encerrar a sessão
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['userEmail'];
    $CPF = $_POST['CPF'];
    $CPF = str_replace(['.', '-'], '', $CPF);

    // Verificar se o email ou CPF existem na tabela usuario
    $verificaEmailQuery = "SELECT * FROM usuario WHERE email = '$email'";
    $verificaCPFQuery = "SELECT * FROM usuario WHERE CPF = '$CPF'";
    $resultVerificaEmail = mysqli_query($conexao, $verificaEmailQuery) or die("Não foi possível efetuar a consulta de email!");
    $resultVerificaCPF = mysqli_query($conexao, $verificaCPFQuery) or die("Não foi possível efetuar a consulta de CPF!");

    if ($resultVerificaEmail && mysqli_num_rows($resultVerificaEmail) > 0) {
        // Email encontrado!
        header("Location: ./php/verifica-credenciais.php?email=" . urlencode($email));
        exit;
    } else if ($resultVerificaCPF && mysqli_num_rows($resultVerificaCPF) > 0) {
        // CPF encontrado!
        header("Location: ./php/verifica-credenciais.php?CPF=" . urlencode($CPF));
        exit;
    } else {
        // Usuário não encontrado ou não existe!
        header("Location: ./php/erro.php?code=07");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<title>Redefinição de senha</title>
	<link rel="stylesheet" type="text/css" href="./css/login-style.css" media="screen" />
	<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
</head>
<body>
	<main>
		<header>
			<nav>
				<div class="navFaixa">          
					<a href="index.php"><img src="./imgs/aq_logo.png" width=""></img></a>   
				</div>
			</nav>
		</header>
		<div class="login-card">
			<form class="formlogin" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<h2>Verificação de Segurança</h2>
				
				<label for="userEmail">Informe seu e-mail de usuário:</label>
				<input type="text" id="userEmail" name="userEmail" placeholder="Digite seu e-mail" onclick="selecionarCampo('userEmail')">

				<label for="CPF">Ou seu CPF:</label>
				<input type="text" id="CPF" name="CPF" placeholder="Somente números" maxlength="14" oninput="formatarCPF(this)" onclick="selecionarCampo('CPF')">
				<script>
					function formatarCPF(input) {
						var value = input.value;
						value = value.replace(/\D/g, ''); // Remove todos os caracteres não numéricos
						value = value.replace(/(\d{3})(\d)/, '$1.$2'); // Adiciona ponto após os primeiros 3 dígitos
						value = value.replace(/(\d{3})(\d)/, '$1.$2'); // Adiciona ponto após os próximos 3 dígitos
						value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2'); // Adiciona hífen antes dos últimos 2 dígitos
						input.value = value;
					}
				</script>
				<input type="submit" value="Buscar">
				<input type="button" value="Voltar" onclick="history.back();">
				<a href="ajuda_e_suporte.php" class="forgot-password">Ajuda ou Suporte</a>	
			</form>
		</div>
	</main>
	<footer>
	<div class="eutemovoSA">
        <h3>AQ.Chegamos, eutemovoSA. São João del Rei - MG.  Brasil.</h3>
          <h4>Todos os Direitos reservados. Copyrights 2023.</h4>
      </div>
		<div class="div-p-footer">
			<p>Mapa do Site</p>
			<ul>
				<li><a href="index.php">Home</a></li>
				<li><a href="login.php">Login</a></li>
				<li><a href="userdashboard.php">Painel do Usuário Requer Login</a></li>
				<li><a href="fale.php">Fale Conosco</a></li>
				<li><a href="ajuda_e_suporte.php">Ajuda e Suporte</a></li>
				<li><a href="visualizar_termos_de_uso.php">Termos de Uso</a></li>		
			</ul>
		</div>
	</footer>
</body>
</html>
