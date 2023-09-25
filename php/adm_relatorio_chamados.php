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
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Relatório Chamados</title>
  <link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
  <style>
     /*Estilo adequado para a impressão em folhas A4 ou em impressoras magnéticas (Impressoras fiscais)*/
 body {
    font-family: Arial, sans-serif;
  }

  .container {
    width: 800px;
    margin: 0 auto;
    padding: 20px;
  }

  h1, p {/* , Elementos recebem o mesmo estilo*/
    text-align: center;
    margin-bottom: 30px;
  }

  .logo {
    text-align: center;
    margin-bottom: 20px;
  }

  .logo img {/*Elemento img dentro do elemento logo recebe o estilo*/
    max-width: 200px;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
  }

  th, td {
    padding: 10px;
    text-align: left;
    border-bottom: 1px solid #ddd;
  }

  th {
    background-color: #f2f2f2;
  }
  </style>
</head>
<body>
  <div class="container"><!--Recomendável estilizar tudo dentro dessa div para forçar a impressão adequada-->
    <div class="logo">
      <img src="../imgs/spbusicon.png" alt="AQ.CHEGAMOS RELATÓRIO" width="50px">
      
    </div>
  <h1>AQ.CHEGAMOS</h1>
    <?php
    // DEFINE O FUSO HORARIO COMO O HORARIO DE BRASILIA
    date_default_timezone_set('America/Sao_Paulo');
    // CRIA UMA VARIAVEL E ARMAZENA A HORA ATUAL DO FUSO-HORÀRIO DEFINIDO (BRASÍLIA)
    $dataLocal = date('d/m/Y H:i:s', time());;
    echo '<p>'.$dataLocal.'</p>';
    $query_status_aberto = "SELECT COUNT(*) AS total_aberto FROM suporte WHERE status_chamado = 'ABERTO'";
    $result_status_aberto = mysqli_query($conexao, $query_status_aberto);
    $row_status_aberto = mysqli_fetch_assoc($result_status_aberto);
    $total_aberto = $row_status_aberto['total_aberto'];

    $query_status_desenvolvimento = "SELECT COUNT(*) AS total_desenvolvimento FROM suporte WHERE status_chamado = 'EM DESENVOLVIMENTO'";
    $result_status_desenvolvimento = mysqli_query($conexao, $query_status_desenvolvimento);
    $row_status_desenvolvimento = mysqli_fetch_assoc($result_status_desenvolvimento);
    $total_desenvolvimento = $row_status_desenvolvimento['total_desenvolvimento'];

    $query_status_encerrado = "SELECT COUNT(*) AS total_encerrado FROM suporte WHERE status_chamado = 'ENCERRADO'";
    $result_status_encerrado = mysqli_query($conexao, $query_status_encerrado);
    $row_status_encerrado = mysqli_fetch_assoc($result_status_encerrado);
    $total_encerrado = $row_status_encerrado['total_encerrado'];

    ?>
    <hr>
    <h2>Relatório de Chamados</h2>
    <table border="1">
        <tr>
          <th>PROTOCOLO</th>
					<th>Data de Abertura do Chamado</th>
					<th>CPF do usuário</th>
					<th>Tipo de chamado</th>
					<th>Status do Chamado</th>
        </tr>
        <?php
        // Consulta na tabela 'excursao'
        $query = "SELECT * FROM suporte ORDER BY status_chamado, data_criacao";
        $result = mysqli_query($conexao, $query) or die("Não foi possível realizar a consulta");

        while ($rows = mysqli_fetch_array($result)) {

          $protocolo = $rows["protocolo"];
          $dataAbertura = $rows["data_criacao"];
          $usuario = $rows['id_usuario'];
          $tipoChamado = $rows['Tipo_do_chamado'];
          $status_chamado = $rows['status_chamado'];
          // Exibe os dados do chamados
          echo "<tr>";
          echo "<td>$protocolo</td>";
          echo "<td>$dataAbertura</td>";
          echo "<td>$usuario</td>";
          echo "<td>$tipoChamado</td>";
          echo "<td>$status_chamado</td>";
          echo "</tr>"; // Finaliza a linha da tabela
        }

        mysqli_free_result($result);
        mysqli_close($conexao);
        ?>
        <tr></tr>
        <td colspan="4" ><strong>TOTAL EM ABERTO</strong></td>
        <td><strong><?php echo $total_aberto ?></strong></td>
        <tr></tr>
        <td colspan="4" ><strong>TOTAL EM DESENVOLVIMENTO</strong></td>
        <td><strong><?php echo $total_desenvolvimento?></strong></td>
        <tr></tr>
        <td colspan="4" ><strong>TOTAL ENCERRADO</strong></td>
        <td><strong><?php echo $total_encerrado?></strong></td>
    </table>
    <hr>
    <!--JAVASCRIPT CHAMA A FUNÇÃO NATIVA DO NAVEGADOR(IGUAL AO CTRL+P) TORNA POSSÍVEL SALVAR EM PDF-->
    <script>//Ao entrar na página a função chama o "CTRL+P"
        window.onload = function() {
            window.print();
        };
    </script>
  </div><!--FIM da DIV de Área de IMPRESSÃO-->
</body>
</html>
