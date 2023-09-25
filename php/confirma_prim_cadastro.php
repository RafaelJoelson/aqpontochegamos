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
    // Redirecionar o usuário para a página de logout se estiver logado
    echo "<script>alert('Você não tem permissão para acessar esta página. Você será desconectado.'); window.location.href = 'logout.php';</script>";
    header("Location: logout.php");
    exit();
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
              <h2>Bem-vindo(a), Excurseiro(a)!</h2>
              <h4>Antes de prosseguir, algumas dicas rápidas para começar a organizar suas excursões!</h4>
              <h5>1. Você começa aqui para organizar suas excursões.</h5>
              <h5>2. Aqui você redefine suas credenciais de segurança.</h5>
              <h5>3. Quer mudar algo no seu perfil? É aqui.</h5>
              <img src="../imgs/perfil.png" alt="">
              <h5>1. Aqui você cria sua excursão. Anúncie, seja criativo e lucre!</h5>
              <h5>2. Busque suas excursões.</h5>
              <h5>3. Visualize ou Edite sua excursão.</h5>
              <img src="../imgs/tabela.png" alt="">
              <h4>E se precisar de mais alguma ajuda, buzine para nós! Confira a página de Ajuda e Suporte ou Fale conosco, será um prazer ajudar!</h4>
          </div>
          <button class="cfexbtn" onclick="window.location.href='../login.php'">Ok, ir para Login</button>
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
