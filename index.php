<?php 
  session_start();
  include_once("./php/connection.php");
  
  if (!isset($_SESSION['userEmail'])) {
    // Verifica se o usuário está logado e altera a nav e idx. A idx só existe para usuário não logados
    $nav = '
      <ul>
        <li><button class="loginButton" onclick="window.location.href=\'login.php\'">Login</button></li>  
      </ul>';
	  $idx = '<div class="idx_container">           
	  <form class="form_buscador_header" action="./php/termos_de_uso.php" method="GET">
	  <img class="img2"src="./imgs/bus-icon-table.png" width="150px"></img><br><br>
	  <label for = "button">Ou organizar suas excursões?</label><br>
	  <button class="cadastre" value="Cadastre-se">Cadastre-se</button>
	  </form>
	  </div>' ;
  } else {
    $CPF = $_SESSION['CPF'];
    $nome = $_SESSION['nome'];
    $email = $_SESSION['userEmail'];
    $nav = '
      <ul>
        <li><input type="button" value="Meu Painel" class="btn btn-primary" onclick="window.location.href=\'userdashboard.php\'"></li>
        <li><input type="button" value="Sair" class="btn btn-outline-primary me-2" onclick="window.location.href=\'./php/logout.php\'"></li>
		<li class="usuario" id="userStatus">Bem-vindo(a), ' . $nome . '</li> 
      </ul>';
	  $idx = '';

	
}

// Diretório onde as imagens estão armazenadas
$targetDir = './imgs/userbusPics/';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AQ.Chegamos</title>
    <link rel="stylesheet" type="text/css" href="./css/index-style.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="./css/slides-style.css" media="screen" />
	<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
	<script>
		function validarFormulario() {
			var origem = document.getElementById("origem").value;
			var destino = document.getElementById("destino").value;
			
			if (origem.trim() === "" && destino.trim() === "") {
				alert("Você precisa escolher a origem, o destino ou ambos.");
				return false;
			}
			
			window.dispatchEvent(new Event('load'));
			return true;
		}
		</script>
</head>
<main>
	<body>
		<header id="inicio">
		<div class="slider-container">
			<div class="login">
						<?php echo $nav; ?>         
			</div>
			<div class="index-container" >
				<div class="idx_container">  
				<div class="form_buscador_header" id="formBuscar">
					<input type="hidden" name="buscar" value="true">
					<img class="img1" src="./imgs/busicon.png">
					<p>Buscando Viagens?</p>
					<button class="buscadorButton" onclick="scrollToResult()">Buscar</button>
				</div>
				</div>
				<?php echo $idx ?>
			</div>
					<div class="slide active-slide" style="background-image: url('./imgs/banner1.png');">
						<div class="slide-content">
						</div>
					</div>      
						<div class="slide" style="background-image: url('./imgs/banner2.png');">
						<div class="slide-content">
						</div>
					</div>
						<div class="slide" style="background-image: url('./imgs/banner3.png');">
						<div class="slide-content">
						</div>
					</div>    
						<button class="prev-slide">&#10094;</button>
						<button class="next-slide">&#10095;</button>
					</div>
				</div>
			</div>			
		</header>
		<script src="./js/slide.js"></script>
		<script src="./js/main.js"></script>
		<script>
		window.addEventListener('DOMContentLoaded', function() {
			scrollToInicio();
		});
		</script>
	</body>		  
</main>	
<footer>
	<div class="eutemovoSA">
		<h3>AQ.Chegamos, eutemovoSA. São João del Rei - MG.  Brasil.</h3>
    	<h4>Todos os Direitos reservados. Copyrights 2023.</h4>
	</div>
	  <div class="excursoes" id="resultado">
	  	<div class="titulo">
		  <h1>Viagens incriveis para você!</h1>
			<button class="voltar" onclick="scrollToInicio()">Voltar ao Topo</button>
			<button class="carregar" onclick="window.location.href='index.php'">Carregar Todos</button>
			<script>
				function scrollToInicio() {
					var elementoInicio = document.getElementById("inicio");
					elementoInicio.scrollIntoView({ behavior: 'smooth' });
				}
			</script>
		</div>
		<div class="buscador_container">       
		<form class="form_buscador" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET" onsubmit="return validarFormulario()">
			<input type="hidden" name="buscar" value="true">
			<label for="origem">Origem:</label>
			<input type="text" id="origem" name="origem" placeholder="Digite sua origem...">
			<label for="destino">Destino:</label>
			<input type="text" id="destino" name="destino" placeholder="Digite seu destino...">
			<input id="buscar-btn" type="submit" value="Buscar">
		</form>
		</div>
		<script>
			function scrollToResult() {
				var resultadoDiv = document.getElementById('resultado');
				resultadoDiv.scrollIntoView({ behavior: 'smooth' });
			}
	  </script>
	  </div>
	  <div class="cardstabela">	
			<?php
			// Verifica se há origem ou destino fornecidos na busca
			if (isset($_GET['origem']) && ($_GET['origem']) != NULL || isset($_GET['destino']) && ($_GET['destino']) != NULL) {
				
				$origem = $_GET['origem'];
				$destino = $_GET['destino'];
				echo '<script>
					window.addEventListener("load", (event) => {
					scrollToResult();
					});
				</script>';
				// Consulta SQL para buscar as excursões com base na origem ou destino fornecidos
				$query = "SELECT * FROM excursao WHERE cidadeOrigem = '$origem' OR cidadeDestino = '$destino' ORDER BY cidadeOrigem";
				$result = mysqli_query($conexao, $query) or die("Não foi possível realizar a consulta");

				while ($rows = mysqli_fetch_array($result)) {
					$id = $rows['ID'];
					$title = $rows["titulo"];
					$origem = $rows["cidadeOrigem"];
					$destino = $rows["cidadeDestino"];
					$dataPartida = $rows['dataIda'];
					$horaPartida = $rows['horaPartida'];
					$dataChegada = $rows['dataChegada'];
					$horaChegada = $rows['horaChegada'];
					$dataVolta = $rows['dataVolta'];
					$valor = $rows['preco'];
					$vagas = $rows['vagas'];
					
					if($rows == 0){
						echo '
						<div class="miniCard">
								<div class="mini-img-container">
									<img src="../imgs/userBusPics/default-bus.png" alt="">
								</div>
								<div class="mini-text-container">
									<p class="mini-title">OPS! Cidade inválida ou Incorreta!</p>
								</div>	
						</div>';
					} else {

					$targetPath1 = $targetDir . $id . '_1.*';
					$imageFiles = glob($targetPath1);
					if (!empty($imageFiles)) {
					$imagePath1 = $imageFiles[0];
					} else {
					$imagePath1 = $targetDir . 'default-bus.png?v=' . time();
					}
					if($origem == $_GET['origem']){
						echo '
							<div class="miniCard">
								<a href="./php/visualizar_excursao.php?id=' . $id . '">
									<div class="mini-img-container">
										<img src="' . $imagePath1 . '" alt="">
									</div>
									<div class="mini-text-container">
										<p class="mini-title">' . $title . '</p>
										<h3 class="mini-origem">Origem: ' . $origem . '</h3>
										<p class="mini-destino">Destino: ' . $destino . '</p>
										<p class="mini-ida">Ida: ' . $dataPartida . '</p>
										<p class="mini-volta">Volta: ' . $dataVolta . '</p>
										<p class="mini-valor">R$ ' . $valor . '</p>
										<p class="mini-vagas">Vagas: ' . $vagas . '</p>
										</a>
								</div>	
						</div>';
					} else {

						echo '
							<div class="miniCard">
								<a href="./php/visualizar_excursao.php?id=' . $id . '">
									<div class="mini-img-container">
										<img src="' . $imagePath1 . '" alt="">
									</div>
									<div class="mini-text-container">
										<p class="mini-title">' . $title . '</p>
										<h3 class="mini-destino">Destino: ' . $destino . '</h3>
										<p class="mini-origem">Origem: ' . $origem . '</p>
										<p class="mini-ida">Ida: ' . $dataPartida . '</p>
										<p class="mini-volta">Volta: ' . $dataVolta . '</p>
										<p class="mini-valor">R$ ' . $valor . '</p>
										<p class="mini-vagas">Vagas: ' . $vagas . '</p>
										</a>
								</div>	
						</div>';
				}
				}		
				}
				mysqli_free_result($result);
			} else {

				// Se não houver origem ou destino fornecidos, exibe todas as excursões
				$query = "SELECT * FROM excursao ORDER BY dataIda";
				$result = mysqli_query($conexao, $query) or die ("Não foi possível realizar a consulta");
				echo '<script>
				window.addEventListener("load", (event) => {
					var busca = false; // Altere para true ou false, dependendo da sua condição
					if (busca) {
					  scrollToResult();
					} else {
					  // Chame a função que você deseja chamar quando a condição for falsa, como scrollToInicio()
					  scrollToInicio();
					}
				  	});
					</script>';
				while ($rows = mysqli_fetch_array($result)) {
					$id = $rows['ID'];
					$title = $rows["titulo"];
					$origem = $rows["cidadeOrigem"];
					$destino = $rows["cidadeDestino"];
					$dataPartida = $rows['dataIda'];
					$horaPartida = $rows['horaPartida'];
					$dataChegada = $rows['dataChegada'];
					$horaChegada = $rows['horaChegada'];
					$dataVolta = $rows['dataVolta'];
					$valor = $rows['preco'];
					$vagas = $rows['vagas'];

					if($rows != 0){

						// Carrega as imagens salvas
					$targetPath1 = $targetDir . $id . '_1.*';
					$imageFiles = glob($targetPath1);
					if (!empty($imageFiles)) {
					$imagePath1 = $imageFiles[0];
					} else {
						$imagePath1 = $targetDir . 'default-bus.png?v=' . time();
					}

					echo '
						<div class="miniCard">
							<a href="./php/visualizar_excursao.php?id=' . $id . '">
								<div class="mini-img-container">
									<img src="' . $imagePath1 . '" alt="">
								</div>
								<div class="mini-text-container">
									<p class="mini-title">' . $title . '</p>
									<h3 class="mini-origem">Origem: ' . $origem . '</h3>
									<p class="mini-destino">Destino: ' . $destino . '</p>
									<p class="mini-ida">Ida: ' . $dataPartida . '</p>
									<p class="mini-volta">Volta: ' . $dataVolta . '</p>
									<p class="mini-valor">R$ ' . $valor . '</p>
									<p class="mini-vagas">Vagas: ' . $vagas . '</p>
									</a>
							</div>	
					</div>';

					} else {

						echo '
						<div class="miniCard">
								<div class="mini-img-container">
									<img src="../imgs/userBusPics/default-bus.png" alt="">
								</div>
								<div class="mini-text-container">
									<p class="mini-title">OPS! Cidade inválida ou Incorreta!</p>

							</div>	
					</div>';
					
					}

					

				}

				mysqli_free_result($result);
			}

			mysqli_close($conexao);
			?>
		</div>
		<div class="botaoVoltar">
			<button class="voltar" onclick="scrollToBusca()">Voltar para Busca</button>
			<script>
				function scrollToBusca() {
					var elementoInicio = document.getElementById("resultado");
					elementoInicio.scrollIntoView({ behavior: 'smooth' });
				}
			</script>
			<button class="voltar" onclick="scrollToInicio()">Voltar ao Topo</button>
			<script>
				function scrollToInicio() {
					var elementoInicio = document.getElementById("inicio");
					elementoInicio.scrollIntoView({ behavior: 'smooth' });
				}
			</script>
		</div>
	  <div class="div-p-footer">
		<p>Mapa do Site</p>
		<ul>
			<li><a href="index.php">Home</a></li>
			<li><a href="login.php">Login</a></li>
			<li><a href="./php/termos_de_uso.php">Cadastra-se</a></li>
			<li><a href="userdashboard.php">Painel do Usuário Requer Login</a></li>
			<li><a href="fale.php">Fale Conosco</a></li>
			<li><a href="ajuda_e_suporte.php">Ajuda e Suporte</a></li>
			<li><a href="visualizar_termos_de_uso.php">Termos de Uso</a></li>		
		</ul>
	</div>
</footer>
</html>
