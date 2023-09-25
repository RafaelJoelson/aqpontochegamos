<?php 
  session_start();
  include_once("connection.php");
  if (!isset($_SESSION['userEmail'])) {
    // Verifica se o usuário está logado e altera a nav.
    $nav = '
      <ul>
        <li><input type="button" value="Login" class="btn btn-outline-primary me-2" onclick="window.location.href=\'../login.php\'"></li>
        <li><input type="button" value="Cadastre-se" class="btn btn-primary" onclick="window.location.href=\'termos_de_uso.php\'"></li>
      </ul>';
  } else {
    $CPF = $_SESSION['CPF'];
    $nome = $_SESSION['nome'];
    $email = $_SESSION['userEmail'];
    $nav = '
      <ul>
        <li id="userStatus">Bem-vindo(a), ' . $nome . '</li> 
        <li><input type="button" value="Meu Painel" class="btn btn-primary" onclick="window.location.href=\'../userdashboard.php\'"></li>
        <li><input type="button" value="Sair" class="btn btn-outline-primary me-2" onclick="window.location.href=\'logout.php\'"></li>
      </ul>';
  }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AQ.Chegamos</title>
    <link rel="stylesheet" type="text/css" href="../css/all-styles.css" media="screen" />
	  <link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
</head>
<body>
	<main>
		<header>
			<nav>
				<div class="navFaixa">          
          <a href="../index.php"><img src="../imgs/aq_logo.png" width=""></img></a>
				</div>
				<div class="login">
					<?php echo $nav; ?>         
				</div>
			</nav>
		</header>
    <div class="index-container">
      <div class="cfex-container">
          <div class="cf_exc">
              <h2>Prontinho, Excurseiro(a)!</h2>
              <h4>Sua Excursão está disponível para visualizar na Página de Inicial!</h4>
              <img src="../imgs/confirma_exc.png" alt=""> 
          </div>
          <button class="cfexbtn" onclick="window.location.href='../index.php'">Página Inicial</button>
          <button class="cfexbtn" onclick="window.location.href='tabela_excursoes.php'">Voltar para Minhas Excursões</button>
      </div>
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
        <li><a href="../index.php">Home</a></li>
				<li><a href="../login.php">Login</a></li>
        <li><a href="../userdashboard.php">Painel do Usuário Requer Login</a></li>
				<li><a href="../fale.php">Fale Conosco</a></li>
				<li><a href="../ajuda_e_suporte.php">Ajuda e Suporte</a></li>
				<li><a href="../visualizar_termos_de_uso.php">Termos de Uso</a></li>	
			</ul>
		</div>
	</footer>
</body>
</html>
