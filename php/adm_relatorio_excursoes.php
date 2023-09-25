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
  <title>Relatório Excursões</title>
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
      <img src="../imgs/busicon.png" alt="AQ.CHEGAMOS RELATÓRIO" width="50px">
      
    </div>
  <h1>AQ.CHEGAMOS</h1>
    <?php
    // DEFINE O FUSO HORARIO COMO O HORARIO DE BRASILIA
    date_default_timezone_set('America/Sao_Paulo');
    // CRIA UMA VARIAVEL E ARMAZENA A HORA ATUAL DO FUSO-HORÀRIO DEFINIDO (BRASÍLIA)
    $dataLocal = date('d/m/Y H:i:s', time());;
    echo '<p>'.$dataLocal.'</p>';

    $query_total_excursoes = "SELECT COUNT(ID) AS total_excursoes FROM excursao";
    $result_total_excursoes = mysqli_query($conexao, $query_total_excursoes);
    $row_total_excursoes = mysqli_fetch_assoc($result_total_excursoes);
    $total_excursoes = $row_total_excursoes['total_excursoes'];

    ?>
    <hr>
    <h2>Total de Excursões</h2>
    <table border="1">
        <tr>
            <th>ID da Excursão</th>
            <th>ID do Usuário Excurseiro</th>
            <th>Título da Excursão</th>
            <th>Cidade de Origem</th>
            <th>Cidade de Destino</th>
            <th>Vagas</th>
            <th>Valor</th>
        </tr>
        <?php
        // Consulta na tabela 'excursao'
        $query = "SELECT ID, cpf_excurseiro, titulo, cidadeOrigem, cidadeDestino, vagas, preco
                  FROM excursao 
                  ORDER BY ID";

        $result = mysqli_query($conexao, $query) or die("Não foi possível realizar a consulta");

        while ($rows = mysqli_fetch_array($result)) {
            $id = $rows["ID"];
            $usuario_excurseiro_id = $rows["cpf_excurseiro"];
            $titulo = $rows["titulo"];
            $origem = $rows["cidadeOrigem"];
            $destino = $rows["cidadeDestino"];
            $vagas = $rows['vagas'];
            $preco = $rows['preco'];

            echo "<tr>"; // Inicia a linha da tabela
            echo "<td>$id</td>";
            echo "<td>$usuario_excurseiro_id</td>";
            echo "<td>$titulo</td>";
            echo "<td>$origem</td>";
            echo "<td>$destino</td>";
            echo "<td>$vagas</td>";
            echo "<td>$preco</td>";
            echo "</tr>"; // Finaliza a linha da tabela
        }

        mysqli_free_result($result);
        mysqli_close($conexao);
        ?>
        <td colspan="6" ><strong>Total de Excursões</strong></td>
        <td><strong><?php echo $total_excursoes ?></strong></td>
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
