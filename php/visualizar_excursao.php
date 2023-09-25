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
  // Verifica se o ID da excursão foi fornecido na URL
  if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Recupere as informações do usuário a partir das variáveis de sessão

    $query = "SELECT * FROM excursao WHERE ID = '$id'";
    $result = mysqli_query($conexao, $query) or die("Não foi possível realizar a consulta");
              
    $rows = mysqli_fetch_array($result);

    $title = $rows['titulo'];
    $descricao = $rows['descricao'];
    $origem = $rows['cidadeOrigem'];
    $destino = $rows['cidadeDestino'];
    $dataPartida = $rows['dataIda'];
    $horaPartida = $rows['horaPartida'];
    $dataChegada = $rows['dataChegada'];
    $horaChegada = $rows['horaChegada'];
    $dataRetorno = $rows['dataVolta'];
    $horaRetorno = $rows['horaVolta'];
    $telefone = $rows['telefone'];
    $preco = $rows['preco'];
    $vagas = $rows['vagas'];
        
    // Formata a saída do telefone para o padrão (DD) NNNNN-NNNN
    $formattedTelefone = preg_replace('/^(\d{2})(\d{5})(\d{4})$/', '($1) $2-$3', $telefone);
    // Diretório onde as imagens estão armazenadas
    $targetDir = '../imgs/userbusPics/';
        
    // Carrega as imagens salvas
    $targetPath1 = $id . '_1.*';
    $targetPath2 = $id . '_2.*';
    $targetPath3 = $id . '_3.*';
        
    // Obter a lista de arquivos que correspondem ao padrão de nome
    $arquivos[0] = glob($targetDir . $targetPath1);
    $arquivos[1] = glob($targetDir . $targetPath2);
    $arquivos[2] = glob($targetDir . $targetPath3);
        
    // Verificar se foi encontrado algum arquivo
    if (count($arquivos[0]) > 0) {
      // Encontrou o arquivo de imagem correspondente ao ID, usar esse arquivo para exibir a imagem
      $targetPath1 = $arquivos[0][0] . '?v=' . time();
    } else {
      // Não foi encontrado um arquivo de imagem correspondente ao ID, usar uma imagem padrão
      $targetPath1 = $targetDir . 'default-bus.png?v=' . time();
    }

    if (count($arquivos[1]) > 0) {
      $targetPath2 = $arquivos[1][0] . '?v=' . time();
    } else {
      $targetPath2 = $targetDir . 'default-bus.png?v=' . time();
    }

    if (count($arquivos[2]) > 0) {
      $targetPath3 = $arquivos[2][0] . '?v=' . time();
    } else {
      $targetPath3 = $targetDir . 'default-bus.png?v=' . time();
    }
  } else {
    // ID da excursão inválido ou não pode ser carregado.
    header("Location: ./php/erro.php?code=11");
    exit;
  }
?> 

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excursões</title>
    <link rel="stylesheet" type="text/css" href="../css/all-styles.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="../css/slides-style.css" media="screen" />
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
			<ul class="navmap">
				<li><a href="../index.php">Home</a></li>
				<li><?php echo "> Excursão: ".$title?></li>
			</ul>
		</header>
		<div class="textCard">
			<div class="exc-container">
				<div class="exccard">
            <h2><?php echo $title."- ID: ".$id ?></h2>
            <p class="desc-container-p">
              <?php echo $descricao ?>
            </p>
				  <h4 id="cardbusOrigem"><?php echo "Origem: ".$origem?></h5>
				  <h4 id="cardbusDestino"><?php echo "Destino: ".$destino?></h5>
				  <h5><?php echo "Partida: ".$dataPartida." - Horário: ".$horaPartida?></h5>
				  <h5><?php echo "Chegada: ".$dataChegada." - Horário: ".$horaChegada?></h5>
				  <h5><?php echo "Chegada: ".$dataRetorno." - Horário: ".$horaRetorno?></h5>
				  <h5><?php echo "Valor da Passagem: R$ ".$preco?></h5>
				  <h5><?php echo "Vagas disponíveis: ".$vagas?></h5>
				  <h4><?php echo "Contato: ".$formattedTelefone?></h4>
				</div>
        <div class="minislide-container">
          <div class="slider-container">
                    <div class="slide active-slide" style="background-image: url(<?php echo $targetPath1;?>);">
                      <div class="slide-content">
                      <h2></h2>
                      <p><?php echo $title ?></p>
                      </div>
                    </div>      
                    <div class="slide" style="background-image: url('<?php echo $targetPath2;?>');">
                      <div class="slide-content">
                      <h2></h2>
                      <p>Ônibus da Viagem</p>
                      </div>
                    </div>          
                    <div class="slide" style="background-image: url('<?php echo $targetPath3;?>');">
                      <div class="slide-content">
                      <h2></h2>
                      <p>Interior do Ônibus</p>
                      </div>
                    </div>    
                    <button class="prev-slide">&#10094;</button>
                    <button class="next-slide">&#10095;</button>
          </div>
        </div>
      </div>
		</div>
	</main>
  <script src="../js/minislide.js"></script>
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