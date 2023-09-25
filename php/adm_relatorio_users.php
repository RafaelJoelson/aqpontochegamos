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
  <title>Relatório Usuários</title>
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


    $query = "SELECT COUNT(DISTINCT usuario.CPF) AS total_usuarios, COUNT(excursao.ID) AS total_excursoes
    FROM usuario
    LEFT JOIN excursao ON usuario.CPF = excursao.cpf_excurseiro";

    $result = mysqli_query($conexao, $query);
    $rows = mysqli_fetch_array($result);
    $total_usuarios = $rows['total_usuarios'];
    $total_excursoes = $rows['total_excursoes'];




    ?>
    <hr>
    <h2>Relação Usuários e suas Excursões Ativas</h2>
    <table border="1">
        <tr>
            <th>CPF DO USUÁRIO</th>
            <th>NOME</th>
            <th>E-MAIL</th>
            <th>TOTAL DE EXCURSÕES</th>
        </tr>
        <?php
        // Consulta as informações de segurança do usuário no banco de dados
        $query = "SELECT usuario.CPF, usuario.nome, usuario.email, COUNT(excursao.ID) AS qtde_excursoes
        FROM usuario
        LEFT JOIN excursao ON usuario.CPF = excursao.cpf_excurseiro
        GROUP BY usuario.CPF, usuario.nome, usuario.email";

        $result = mysqli_query($conexao, $query);
         // onde $conexao é o objeto de conexão ao banco de dados

        while ($rows = mysqli_fetch_array($result)) {
            $CPF = $rows['CPF'];
            $nome = $rows['nome'];
            $email = $rows['email'];
            $qtde_excursoes = $rows['qtde_excursoes'];

            echo "<tr>"; // Inicia a linha da tabela
            echo "<td>$CPF</td>";
            echo "<td>$nome</td>";
            echo "<td>$email</td>";
            echo "<td>$qtde_excursoes</td>";
            echo "</tr>"; // Finaliza a linha da tabela
        }
        mysqli_free_result($result);
        ?>
        <td colspan="" ><strong>Total de Usuários</strong></td>
        <td><strong><?php echo $total_usuarios ?></strong></td>
        <td colspan="" ><strong>Total de Excursões</strong></td>
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
