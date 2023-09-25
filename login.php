<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Login</title>
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
				<ul class="navmap">
					<li><a href="index.php">Home</a></li>
					<li>> Login</li>
				</ul>
		</header>
		<div class="login-card">
			<form class="formlogin" method="POST" action="./php/verifica-login.php">
				<div class="logo-form-container">
				<img src="./imgs/busicon.png" width="150px"></img>
				</div>
				<label for="email">E-mail do usuário:</label>
				<input type="text" id="userEmail" name="userEmail" placeholder="Digite seu e-mail" required>
				<label for="senha">Senha:</label>
				<input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
				<input type="submit" value="Entrar">
				<input type="button" value="Cadastrar" onclick="window.location.href='./php/termos_de_uso.php'">
				<a href="searchenvaluation_password_or_secret_question.php" class="forgot-password">Esqueci minha senha</a>
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