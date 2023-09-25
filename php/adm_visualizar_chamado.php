<?php
session_start();
include_once("connection.php");
$TGM = $_SESSION['TGM'];
  if (!isset($_SESSION['userEmail'])) {
    // Verifica se o usuário está logado e altera a nav.
    $nav = '
      <ul>
        <li><input type="button" value="Login" class="btn btn-outline-primary me-2" onclick="window.location.href=\'login.php\'"></li>
        <li><input type="button" value="Cadastre-se" class="btn btn-primary" onclick="window.location.href=\'termos_de_uso.php\'"></li>
      </ul>';
    } else if ($TGM < 1) {
        $MD_TGM = '<li><a href="./php/adm_userdashboard.php">MDTGM</a></li>';
        echo "<script>alert('Você não tem permissão para acessar esta página. Você será desconectado.'); window.location.href = 'logout.php';</script>";
    }else {
    $ADMCPF = $_SESSION['CPF'];
    $ADMnome = $_SESSION['nome'];
    $ADMemail = $_SESSION['userEmail'];
    $nav = '
      <ul>
        <li id="userStatus">Bem-vindo(a), ' . $ADMnome . '</li> 
        <li><input type="button" value="Meu Painel" class="btn btn-primary" onclick="window.location.href=\'userdashboard.php\'"></li>
        <li><input type="button" value="Sair" class="btn btn-outline-primary me-2" onclick="window.location.href=\'logout.php\'"></li>
      </ul>';   
}
if (isset($_GET['protocolo'])) {
    $protocolo = $_GET['protocolo'];
    // Carrega os dados da excursão
    $query = "SELECT * FROM suporte WHERE protocolo = ?";
    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_bind_param($stmt, 's', $protocolo);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $rows = mysqli_fetch_array($result);
    $protocolo = $rows['protocolo'];
    $tipo_chamado = $rows['Tipo_do_chamado'];
    $descricao = $rows['descricao_chamado'];
    $status_chamado = $rows['status_chamado'];
    $data_criacao = $rows['data_criacao'];
    
    if($rows != 0){

        /// Diretório de destino para as imagens
        $targetDir = "../imgs/chamados/";

        // Obter o caminho completo da imagem
        $targetPath = $targetDir . $protocolo . '.jpg';

        // Verificar se a imagem existe
        if (file_exists($targetPath)) {
            // Obter o nome completo da imagem
            $nomeCompleto = basename($targetPath);
        } else {
            $nomeCompleto = NULL;
        }

}
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $protocolo = $_POST['protocolo'];
    $status_chamado = $_POST['update_status'];

    $query = "UPDATE suporte SET status_chamado = ? WHERE protocolo = ?";
    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_bind_param($stmt, 'ss', $status_chamado, $protocolo);
    mysqli_stmt_execute($stmt);

    if (mysqli_affected_rows($conexao) > 0) {
        //atualizado com sucesso!
        header("Location: adm_gerenciar_chamados.php");
        exit;

    } else {
        //Se atualização falhar
         header("Location: erro.php?code=04");
        exit;

    }


    
}
?>
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Visualizar Chamado</title>
        <link rel="stylesheet" type="text/css" href="../css/admcads-styles.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="../css/croppie.css">
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
                <ul class="navmap">
                    <li><a href="#" onclick="history.back();">Voltar</a></li>
                    <li>ADM VISUALIZAR CHAMADO</li>     
                </ul>
            </header>
            <div class="textCard">
                <div class= v-container>
                    <form class= prot1 action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                    <div class="v-container">
                        <h3><?php echo 'CHAMADO: '. $protocolo; ?></h3>
                        <input type="hidden" name="protocolo" value = "<?php echo $protocolo; ?>">
                        <h4><?php echo 'Tipo de Chamado: '. $tipo_chamado; ?></h4>
                        <h3><?php echo 'Data de Abertura: '. $data_criacao; ?></h3>
                    </div>
                    <h4>Descrição do Problema:</h4>
                    <p class="f-container-p">
                        <?php echo $descricao ?>
                    </p>
                    <div class="v-container">
                        <p>ARQUIVO EM ANEXO: </p><br>
                        <a href="<?php echo $targetPath; ?>" target="_blank"><?php echo $nomeCompleto; ?></a>
                    </div>
                   <div class= "v-container">
                        <label for="status">*STATUS:</label>
                                <select id="status" name="update_status" required>
                                    <optgroup label="STATUS">
                                        <option value="ABERTO" <?php if ($status_chamado === 'ABERTO') echo 'selected'; ?>>ABERTO</option>
                                        <option value="EM DESENVOLVIMENTO" <?php if ($status_chamado === 'EM DESENVOLVIMENTO') echo 'selected'; ?>>EM DESENVOLVIMENTO</option>
                                        <option value="ENCERRADO" <?php if ($status_chamado === 'ENCERRADO') echo 'selected'; ?>>ENCERRADO</option>
                                    <optgroup label="STATUS">
                                </select>
                   </div>
                        <input type="submit" value="Atualizar">
                    </form>
                    <button class="v-button" onclick="history.back();">Voltar</button>
                </div>            
            </div>
        </main>
        <footer>
		<div class="eutemovoSA">
            <h3>AQ.Chegamos, eutemovoSA. São João del Rei - MG.  Brasil.</h3>
            <h4>Todos os Direitos reservados. Copyrights 2023.</h4>
        </div>
		<div class="div-p-footer">

		</div>
	</footer>
    </body>
    </html>
    