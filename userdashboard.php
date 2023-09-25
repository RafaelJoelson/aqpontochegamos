<?php
session_start();

if (!isset($_SESSION['userEmail'])) {
    // Redirecionar o usuário para a página de login se não estiver logado
    header("Location: login.php");
    exit();
}

// Recupere as informações do usuário a partir das variáveis de sessão vindas da página login

$CPF = $_SESSION['CPF'];
$nome = $_SESSION['nome'];
$email = $_SESSION['userEmail'];
$senhaAtual = $_SESSION['senha'];
$TGM = $_SESSION['TGM'];
if ($TGM == 1) {
    $MD_TGM = '<li><a href="./php/adm_userdashboard.php">MDTGM</a></li>';
} else {
    $MD_TGM = '';
    $_SESSION['TGM'] = 0;
}

// Carregamento da imagem do usuário

$userFileName = $CPF . '.*';

// Diretório onde as imagens estão armazenadas
$targetDir = './imgs/users/';

// Obter a lista de arquivos que correspondem ao padrão de nome
$arquivos = glob($targetDir . $userFileName);

// Verificar se foi encontrado algum arquivo
if (count($arquivos) > 0) {
    // Encontrou o arquivo de imagem correspondente ao CPF, usar esse arquivo para exibir a imagem
    $targetDir .= basename($arquivos[0]) . '?v=' . time();
} else {
    // Não foi encontrado um arquivo de imagem correspondente ao CPF, usar uma imagem padrão
    $targetDir .= 'userDefaultimg.png';
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AQ.Chegamos</title>
    <link rel="stylesheet" type="text/css" href="./css/all-styles.css" media="screen" />
	<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
</head>
<body>
	<main>
		<header>
			<nav>
				<div class="navFaixa">          
					<a href="index.php"><img src="./imgs/aq_logo.png" width=""></img></a>
				</div>
				<div class="login">
					<ul>
						<li id="userStatus"><?php echo "Bem-vindo(a), " . $nome; ?></li> 
						<li><input type="button" value="Sair" class="btn btn-outline-primary me-2" onclick="confirmLogout2()"></li>
					</ul>
				</div>
			</nav>
			<ul class="navmap">
				<li><a href="index.php">Home</a></li>
				<li>> Meu Perfil</li>
				<?php echo $MD_TGM; ?>
			</ul>
		</header>
		<div class="textCard">
			<div class="card-container">
				<div class="painelcard">
					<h2><?php echo "Excurseiro(a), " . $nome; ?></h2>
					<span class="userImgContainer">
						<img id="userImg" src="<?php echo $targetDir;?>" alt="Imagem do usuário">
					</span>
					<hr>
					<h2 id="userName"><?php echo "Nome: " . $nome; ?></h2>
					<hr>
					<h2 id="userEmail"><?php echo "E-mail: " . $email; ?></h2>
					<hr>
					<h3><a href="./php/editar_cadastro.php">Editar minhas informações</a></h3>
				</div>
				<div class="painelcard">
					<h2>Meu Painel</h2>
					
					<hr>
					<h3><a href="./php/tabela_excursoes.php">Gerenciar minhas Excursões</a></h3>
					<hr>
					<h3><a href="#" onclick="confirmLogout1()">Alterar Senha ou Pergunta Secreta</a></h3>
					<hr>
					<h3><a href="ajuda_e_suporte.php">Suporte</a></h3>
					<hr>
					<h3><a href="#" onclick="confirmLogout2()">Sair</a></h3>
					<hr>
					<p><a href="https://docs.google.com/forms/d/e/1FAIpQLScjELjJUAfSG1MoGaGb5y8YujVFxe1EXWc9ypMCIqLQzrPOlQ/viewform">Prezado, <?php echo $nome; ?> após utilizar o site avalie sua experiência Clicando Aqui. </a></p>
				</div>
			</div>     
		</div>
	</main>
	<script src="./js/main.js"></script>
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
			<li><a href="userdashboard.php">Painel do Usuário</a></li>
			<li><a href="fale.php">Fale Conosco</a></li>
			<li><a href="ajuda_e_suporte.php">Ajuda e Suporte</a></li>
			<li><a href="visualizar_termos_de_uso.php">Termos de Uso</a></li>		
		</ul>
	</div>
</footer>
</body>
</html>
