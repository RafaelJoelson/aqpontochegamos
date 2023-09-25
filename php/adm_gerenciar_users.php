<?php 
  include_once("connection.php");
  session_start();
  $TGM = $_SESSION['TGM'];
  if (!isset($_SESSION['userEmail'])) {
      // Redirecionar o usuário para a página de login se não estiver logado
      header("Location: ../login.php");
      exit();
  } else if ($TGM < 1 ) {
      $MD_TGM = '<li><a href="./php/adm_userdashboard.php">MDTGM</a></li>';
      echo "<script>alert('Você não tem permissão para acessar esta página. Você será desconectado.'); window.location.href = 'logout.php';</script>";
  }
  

    $ADM_CPF = $_SESSION['CPF'];
    $ADMnome = $_SESSION['nome'];
    $ADMemail = $_SESSION['userEmail'];
	
    $nav = '
      <ul>
        <li id="userStatus">MODO ADMINISTRADOR Bem-vindo(a), ' . $ADMnome . '</li> 
        <li><input type="button" value="Meu Painel" class="btn btn-primary" onclick="window.location.href=\'adm_userdashboard.php\'"></li>
        <li><input type="button" value="Sair" class="btn btn-outline-primary me-2" onclick="window.location.href=\'logout.php\'"></li>
      </ul>';
        
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ADM GERENCIAR USUÁRIOS</title>
  <link rel="stylesheet" type="text/css" href="../css/admstyles.css" media="screen" />
  <link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
  <script>
	function validarFormulario() {
			var origem = document.getElementById("origem").value;
			var destino = document.getElementById("destino").value;
			
			if (origem.trim() === "" && destino.trim() === "") {
				alert("Você precisa escolher a CPF, o EMAIL ou ambos.");
				return false;
			}
			
			window.dispatchEvent(new Event('load'));
			return true;
		}
	</script>
</head>
<body>
	<main>
		<header>
			<nav>
			  <div class="navFaixa">              
			  </div>
			  <div class="login">
				<?php echo $nav; ?>         
			  </div>
			</nav>
			<div class="buscador_container">            
			  <form class="form_buscador" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" onsubmit="return validarFormulario()">
				<label for="CPF">CPF:</label>
				<input type="text" id="CPF" name="CPF" placeholder="Digite o CPF do usuário...">
				<label for="email">Email:</label>
				<input type="text" id="email" name="email" placeholder="Digite o E-mail do usuário...">
				<input type="submit" value="Buscar">
			  </form>
              <button class="carregar" onclick="window.location.href='adm_gerenciar_users.php'">Carregar Todos</button>
			</div>
		</header>
		<ul class="navmap">
			  <li><a href="adm_gerenciar_excursoes.php">GERENCIAR EXCURSÕES</a></li>
			  <li>/ GERENCIAR USUÁRIOS </li>
		</ul>
		<div class="cardstabela">
		<?php

// Verifica se há CPF ou E-mail fornecidos na busca

if (isset($_GET['CPF']) && ($_GET['CPF']) != NULL || isset($_GET['email']) && ($_GET['email']) != NULL) {
    
	$CPF = $_GET['CPF'];
    $email = $_GET['email'];

    // Consulta SQL para buscar os usuários com base no CPF ou E-mail fornecidos
    $query = "SELECT * FROM usuario WHERE CPF = '$CPF' AND TGM = 0 OR email = '$email' AND TGM = 0 ORDER BY CPF ";

    $result = mysqli_query($conexao, $query) or die("Não foi possível realizar a consulta");

    while ($rows = mysqli_fetch_array($result)) {
		
        $CPF = $rows['CPF'];
        $nome = $rows['nome'];
        $email = $rows['email'];
        $perguntaSecreta = $rows['perguntaSecreta'];
        $respostaSecreta = $rows['respostaSecreta'];


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
		if($CPF == $_GET['CPF']){
        echo '
            <div class="miniCard">
                <a href="adm_editar_cadastro.php?CPF=' . $CPF . '">
                    <span class="userImgContainer">
                        <img id="userImg" src="' . $targetDir . '" alt="Imagem do usuário">
                    </span>
                    <div class="mini-text-container">
						<button> ALTERAR </button>
                        <p class="mini-title">Nome: ' . $nome . '</p>
                        <h4 class="mini-origem">CPF: ' . $CPF . '</h4>
                        <p class="mini-destino">E-mail: ' . $email . '</p>
                        <p class="mini-volta">Pergunta Secreta: ' . $perguntaSecreta . '</p>
                        <p class="mini-valor">Resposta Secreta: ' . $respostaSecreta . '</p>						
                    </div>
                </a>
            </div>';
		} else {

			echo '
            <div class="miniCard">
                <a href="adm_editar_cadastro.php?CPF=' . $CPF . '">
                    <span class="userImgContainer">
                        <img id="userImg" src="' . $targetDir . '" alt="Imagem do usuário">
                    </span>
                    <div class="mini-text-container">
						<button> ALTERAR </button>
                        <p class="mini-title">Nome: ' . $nome . '</p>
						<h4 class="mini-destino">E-mail: ' . $email . '</h4>
                        <p class="mini-origem">CPF: ' . $CPF . '</p>   
                        <p class="mini-volta">Pergunta Secreta: ' . $perguntaSecreta . '</p>
                        <p class="mini-valor">Resposta Secreta: ' . $respostaSecreta . '</p>
						
                    </div>
                </a>
            </div>';
		}
    }
 mysqli_free_result($result);

} else {
    // Se não houver CPF ou E-Mail fornecidos, exibe todos os usuários.
    $query = "SELECT * FROM usuario WHERE TGM = 0 ORDER BY nome ";

    $result = mysqli_query($conexao, $query) or die("Não foi possível realizar a consulta");

    while ($rows = mysqli_fetch_array($result)) {

        $CPF = $rows['CPF'];
        $nome = $rows['nome'];
        $email = $rows['email'];
        $perguntaSecreta = $rows['perguntaSecreta'];
        $respostaSecreta = $rows['respostaSecreta'];

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

        echo '
            <div class="miniCard">
                <a href="adm_editar_cadastro.php?CPF=' . $CPF . '">
                    <span class="userImgContainer">
                        <img id="userImg" src="' . $targetDir . '" alt="Imagem do usuário">
                    </span>
                    <div class="mini-text-container">
						<button> ALTERAR </button>
                        <p class="mini-title">Nome: ' . $nome . '</p>
                        <h4 class="mini-origem">CPF: ' . $CPF . '</h4>
                        <p class="mini-destino">E-mail: ' . $email . '</p>
                        <p class="mini-volta">Pergunta Secreta: ' . $perguntaSecreta . '</p>
                        <p class="mini-valor">Resposta Secreta: ' . $respostaSecreta . '</p>		
                    </div>
                </a>
            </div>';
    }

    mysqli_free_result($result);
}

mysqli_close($conexao);
?>

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
