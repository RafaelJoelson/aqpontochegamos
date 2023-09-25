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


    $CPF = $_SESSION['CPF'];
    $nome = $_SESSION['nome'];
    $email = $_SESSION['userEmail'];
    $nav = '
      <ul>
        <li id="userStatus">MODO ADMINISTRADOR, ' . $nome . '</li> 
        <li><input type="button" value="Meu Painel" class="btn btn-primary" onclick="window.location.href=\'adm_userdashboard.php\'"></li>
        <li><input type="button" value="Sair" class="btn btn-outline-primary me-2" onclick="window.location.href=\'logout.php\'"></li>
      </ul>';

// Recupere as informações do usuário a partir das variáveis de sessão
$CPF = $_SESSION['CPF'];
$nome = $_SESSION['nome'];
$email = $_SESSION['userEmail'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADM GERENCIAR EXCURSÕES</title>
    <link rel="stylesheet" type="text/css" href="../css/admstyles.css" media="screen" />
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
				<input type="hidden" name="buscar" value="true">
				<label for="origem">Origem:</label>
				<input type="text" id="origem" name="origem" placeholder="Digite sua origem...">
				<label for="destino">Destino:</label>
				<input type="text" id="destino" name="destino" placeholder="Digite seu destino...">
				<input id="buscar-btn" type="submit" value="Buscar">
			</form>
			</div>
		</header>
		<div class="table-card" >
		<ul class="navmap">
			  <li>GERENCIAR GERENCIAR EXCURSÕES </li>
			  <li><a href="adm_gerenciar_users.php">/ GERENCIAR USUÁRIOS</a></li>
		</ul>
			<ul class="navmap">
			<button class="carregar" onclick="window.location.href='adm_gerenciar_excursoes.php'">Carregar Todos</button>
			</ul>
			<table>
				<tr>
					<th></th>
					<th>ID</th>
					<th>Título</th>
					<th>Origem</th>
					<th>Destino</th>
					<th>Data de Partida</th>
					<th>Horário de Partida</th>
					<th>Data de Chegada</th>
					<th>Horário de Chegada</th>
					<th>Retorno</th>
					<th>Horário de Retorno</th>
					<th>Valor</th>
					<th>Vagas</th>
					<th colspan="3"></th>
				</tr>
				<?php
				include_once("connection.php");

				// Verifica se há origem ou destino fornecidos na busca
				if (isset($_GET['origem']) && ($_GET['origem']) != NULL || isset($_GET['destino']) && ($_GET['destino']) != NULL) {
					$origem = $_GET['origem'];
					$destino = $_GET['destino'];

					// Consulta SQL para buscar as excursões com base na origem ou destino fornecidos
					$query = "SELECT * FROM excursao WHERE cidadeOrigem = '$origem' OR cidadeDestino = '$destino' AND cpf_excurseiro = '$CPF' ORDER BY cidadeOrigem";
					$result = mysqli_query($conexao, $query) or die("Não foi possível realizar a consulta");

					while ($rows = mysqli_fetch_array($result)) {
						$id = $rows["ID"];
						$title = $rows["titulo"];
						$origem = $rows["cidadeOrigem"];
						$destino = $rows["cidadeDestino"];
						$dataPartida = $rows['dataIda'];
						$horaPartida = $rows['horaPartida'];
						$dataChegada = $rows['dataChegada'];
						$horaChegada = $rows['horaChegada'];
						$dataVolta = $rows['dataVolta'];
						$preco = $rows['preco'];
						$vagas = $rows['vagas'];

						// Exibe os dados da excursão
						echo "<tr>";
						echo "<th><img src='../imgs/bus-icon-table.png' alt=''></th>";
						echo "<td>$id</td>";
						echo "<td>$title</td>";
						echo "<td>$origem</td>";
						echo "<td>$destino</td>";
						echo "<td>$dataPartida</td>";
						echo "<td>$horaPartida</td>";
						echo "<td>$dataChegada</td>";
						echo "<td>$horaChegada</td>";
						echo "<td>$dataVolta</td>";
						echo "<td>$preco</td>";
						echo "<td>$vagas</td>";
						echo "<th class='btns'><input type='button' value='Visualizar' class='btn btn-primary' onclick=\"window.location.href='adm_visualizar_bus.php?id=$id'\" /></th>";
						echo "<th class='btns'>
						  <input type='button' value='Gerenciar' class='gerenciar' onclick=\"window.location.href='adm_editar_bus.php?id=$id'\" />
						  </th>";
						echo "</tr>";
					}

					mysqli_free_result($result);
				} else {
					// Se não houver origem ou destino fornecidos, exibe todas as excursões
					$query = "SELECT * FROM excursao ORDER BY cidadeOrigem";
					$result = mysqli_query($conexao, $query) or die ("Não foi possível realizar a consulta");

					while ($rows = mysqli_fetch_array($result)) {
						$id = $rows["ID"];
						$title = $rows["titulo"];
						$origem = $rows["cidadeOrigem"];
						$destino = $rows["cidadeDestino"];
						$dataPartida = $rows['dataIda'];
						$horaPartida = $rows['horaPartida'];
						$dataChegada = $rows['dataChegada'];
						$horaChegada = $rows['horaChegada'];
						$dataVolta = $rows['dataVolta'];
						$horaVolta = $rows['horaVolta'];
						$telefone = $rows['telefone'];
						$preco = $rows['preco'];
						$vagas = $rows['vagas'];

						// Exibe os dados da excursão
						echo "<tr>";
						echo "<th><img src='../imgs/bus-icon-table.png' alt=''></th>";
						echo "<td>$id</td>";
						echo "<td>$title</td>";
						echo "<td>$origem</td>";
						echo "<td>$destino</td>";
						echo "<td>$dataPartida</td>";
						echo "<td>$horaPartida</td>";
						echo "<td>$dataChegada</td>";
						echo "<td>$horaChegada</td>";
						echo "<td>$dataVolta</td>";
						echo "<td>$horaVolta</td>";
						echo "<td>$preco</td>";
						echo "<td>$vagas</td>";
						echo "<th class='btns'><input type='button' value='Visualizar' class='btn btn-primary' onclick=\"window.location.href='adm_visualizar_bus.php?id=$id'\" /></th>";
						echo "<th class='btns'>
						  <input type='button' value='Gerenciar' class='gerenciar' onclick=\"window.location.href='adm_editar_bus.php?id=$id'\" />
						  </th>";

						echo "</tr>";
					}

					mysqli_free_result($result);
				}

				mysqli_close($conexao);
				?>
				<tr>
					<th></th>
					<th class="novaExcursao" colspan="13"></th>
					<th></th>
				</tr>
			</table>
			
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
