<?php
session_start();
include_once("connection.php");
$TGM = $_SESSION['TGM'];
if (!isset($_SESSION['userEmail'])) {
    // Redirecionar o usuário para a página de login se não estiver logado
    header("Location: ../login.php");
    exit();
} else if ($TGM < 1) {
    $MD_TGM = '<li><a href="./php/adm_userdashboard.php">MDTGM</a></li>';
    echo "<script>alert('Você não tem permissão para acessar esta página. Você será desconectado.'); window.location.href = 'logout.php';</script>";
}


// Recupere as informações do usuário a partir das variáveis de sessão

$CPF = $_SESSION['CPF'];
$nome = $_SESSION['nome'];
$email = $_SESSION['userEmail'];

// Carregamento da imagem do usuário

$userFileName = $CPF . '.*';

// Diretório onde as imagens estão armazenadas
$targetDir = '../imgs/users/';

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
    <title>ADM Meu Painel</title>
	<link rel="stylesheet" type="text/css" href="../css/admindex-styles.css" media="screen" />
	<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
</head>
<body>
	<main>
		<header>
			<nav>
				<div class="navFaixa">          
					      
				</div>
				<div class="login">
					<ul>
						<li id="userStatus"><?php echo "MODO ADMINISTRADOR Bem-vindo(a), " . $nome; ?></li> 
						<li><input type="button" value="Sair" class="btn btn-outline-primary me-2" onclick="window.location.href='logout.php'"></li>
					</ul>
				</div>
			</nav>
			<ul class="navmap">
				<li><a href="../userdashboard.php">SAIR PARA MODO NORMAL</a></li>
				<li>/ MODO ADMINISTRADOR PAINEL</li>
			</ul>
		</header>
		<div class="textCard">
			<div class="card-container">
				<div class="painelcard">
					<h2><?php echo "Bem-vindo(a), " . $nome; ?></h2>
					<span class="userImgContainer">
						<img id="userImg" src="<?php echo $targetDir;?>" alt="Imagem do usuário">
					</span>
					<hr>
					<h2 id="userName"><?php echo "Nome: " . $nome; ?></h2>
					<hr>
					<h2 id="userEmail"><?php echo "E-mail: " . $email; ?></h2>
					<hr>
				</div>
				<div class="painelcard">
					<h2> MODO ADMINISTRADOR</h2>
					<hr>
					<h3><a href="adm_relatorio_users.php" target="_blank">EMITIR RELATÓRIO USUÁRIOS</a></h3>
					<hr>
					<h3><a href="adm_relatorio_excursoes.php" target="_blank">EMITIR RELATÓRIO DE EXCURSÕES</a></h3>
					<hr>
					<h3><a href="adm_relatorio_chamados.php" target="_blank">EMITIR RELATÓRIO DE CHAMADOS</a></h3>
					<hr>
					<h3><a href="adm_userdashboard.php">VOLTAR</a></h3>
					<hr>
					<h3><a href="logout.php">Sair</a></h3>
				</div>
			</div>     
		</div>
	</main>
    <footer>
		<div class="eutemovoSA">
				<h3>AQ.Chegamos, eutemovoSA. São João del Rei - MG.  Brasil.</h3>
				<h4>Todos os Direitos reservados. Copyrights 2023.</h4>
		</div>
    </footer>
</body>
</html>
