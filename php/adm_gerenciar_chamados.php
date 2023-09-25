<?php
session_start();
include_once("connection.php");
$TGM = $_SESSION['TGM'];
if (!isset($_SESSION['userEmail'])) {
    // Redirecionar o usuário para a página de login se não estiver logado
    header("Location: ../login.php");
    exit();
} else if ($TGM > 1) {
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
    <title>ADM GERENCIAR CHAMADOS</title>
    <link rel="stylesheet" type="text/css" href="../css/admstyles.css" media="screen" />
	<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
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
				<form class="form_buscador" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="GET">
					<label for="protocolo">Protocolo:</label>
					<input type="text" id="protocolo" name="protocolo" placeholder="Informe o protocolo...">
					<input type="submit" value="Buscar">
				</form>
			</div>
		</header>
		<div class="table-card" >
		<ul class="navmap">
			  <li>GERENCIAR GERENCIAR CHAMADOS </li>
		</ul>
			<ul class="navmap">	
			</ul>
			<button class="carregar" onclick="window.location.href='adm_gerenciar_chamados.php'">Carregar Todos</button>
			<table>
				<tr>
					<th></th>
					<th>PROTOCOLO</th>
					<th>Data de Abertura do Chamado</th>
					<th>CPF do usuário</th>
					<th>Tipo de chamado</th>
					<th>Status do Chamado</th>
					<th colspan="2"></th>
				</tr>
				<?php
				include_once("connection.php");
				// Verifica se há protocolo na buscaCALLPROT2023-06-30649e4687090ac9.71008907
				if (isset($_GET['protocolo']) && ($_GET['protocolo']) != NULL){
					$protocolo = $_GET['protocolo'];
					// Consulta SQL para buscar as excursões com base na protocolo ou destino fornecidos
					$query = "SELECT * FROM suporte WHERE protocolo = '$protocolo' ORDER BY protocolo";
					$result = mysqli_query($conexao, $query) or die("Não foi possível realizar a consulta");

					while ($rows = mysqli_fetch_array($result)) {

						$protocolo = $rows["protocolo"];
						$dataAbertura = $rows["data_criacao"];
						$usuario = $rows['id_usuario'];
						$tipoChamado = $rows['Tipo_do_chamado'];
						$status_chamado = $rows['status_chamado'];

						// Exibe os dados da excursão
						echo "<tr>";
						echo "<th><img src='../imgs/bus-icon-table.png' alt=''></th>";
						echo "<td>$protocolo</td>";
						echo "<td>$dataAbertura</td>";
						echo "<td>$usuario</td>";
						echo "<td>$tipoChamado</td>";
						echo "<td>$status_chamado</td>";
						echo "<th class='btns'>
						  <input type='button' value='Gerenciar' class='gerenciar' onclick=\"window.location.href='adm_visualizar_chamado.php?protocolo=$protocolo'\" />
						  </th>";
						echo "</tr>";
					}

					mysqli_free_result($result);
				} else {
			
					// Consulta SQL para buscar as excursões com base na protocolo ou destino fornecidos
					$query = "SELECT * FROM suporte ORDER BY protocolo";
					$result = mysqli_query($conexao, $query) or die("Não foi possível realizar a consulta");

					while ($rows = mysqli_fetch_array($result)) {

						$protocolo = $rows["protocolo"];
						$dataAbertura = $rows["data_criacao"];
						$usuario = $rows['id_usuario'];
						$tipoChamado = $rows['Tipo_do_chamado'];
						$status_chamado = $rows['status_chamado'];

						// Exibe os dados da excursão
						echo "<tr>";
						echo "<th><img src='../imgs/spbusicon.png' alt=''></th>";
						echo "<td>$protocolo</td>";
						echo "<td>$dataAbertura</td>";
						echo "<td>$usuario</td>";
						echo "<td>$tipoChamado</td>";
						echo "<td>$status_chamado</td>";
						echo "<th class='btns'>
						  <input type='button' value='Gerenciar' class='gerenciar' onclick=\"window.location.href='adm_visualizar_chamado.php?protocolo=$protocolo'\" />
						  </th>";
						echo "</tr>";
					}

					mysqli_free_result($result);

				}
				mysqli_close($conexao);
				?>
				<tr>
					<th></th>
					<th colspan="5"></th>
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
