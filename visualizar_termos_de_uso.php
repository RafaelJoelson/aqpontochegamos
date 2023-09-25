<?php 
session_start();
include_once("./php/connection.php");

  if (!isset($_SESSION['userEmail'])) {
    // Verifica se o usuário está logado e alterna a nav.(Barra de Navegação) 
    $nav = '
      <ul>
        <li><input type="button" value="Login" class="btn btn-outline-primary me-2" onclick="window.location.href=\'login.php\'"></li>
        <li><input type="button" value="Cadastre-se" class="btn btn-primary" onclick="window.location.href=\'./php/termos_de_uso.php\'"></li>
      </ul>';
  } else {
    $CPF = $_SESSION['CPF'];
    $nome = $_SESSION['nome'];
    $email = $_SESSION['userEmail'];
    $nav = '
      <ul>
        <li id="userStatus">Bem-vindo(a), ' . $nome . '</li> 
        <li><input type="button" value="Meu Painel" class="btn btn-primary" onclick="window.location.href=\'userdashboard.php\'"></li>
        <li><input type="button" value="Sair" class="btn btn-outline-primary me-2" onclick="window.location.href=\'./php/logout.php\'"></li>
      </ul>';
  }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AQ.Chegamos</title>
    <link rel="stylesheet" type="text/css" href="./css/doc-styles.css" media="screen" />
	  <link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
</head>
<body>
	<main>
		<header>
			<nav>
				<div class="navFaixa">          
        <a href="index.php"><img src="./imgs/aq_logo.png" width=""></img></a>
				</div>
				<div class="login">
					<?php echo $nav; ?>
				</div>
			</nav>
		</header>
		<div class="index-container">
      <div class="textCard">
        <div class="carregaTermos">

        <h1>Contrato de Termos de Uso</h1>
    
            <h2>1. Aceitação dos Termos</h2>
            <p>Ao clicar no botão "Aceito" ou ao acessar e utilizar o website/aplicativo AQ.Chegamos, o Usuário declara ter lido, compreendido e concordado em cumprir todos os termos e condições estabelecidos neste Contrato, bem como todas as leis e regulamentos aplicáveis. Se o Usuário não concordar com algum dos termos e condições, solicitamos que não utilize o website/aplicativo.</p>
            
            <h2>2. Uso do Website/Aplicativo AQ.Chegamos</h2>
            <p>O Usuário concorda em utilizar o website/aplicativo AQ.Chegamos apenas para fins legais e de acordo com as disposições deste Contrato. O Usuário compromete-se a não utilizar o website/aplicativo AQ.Chegamos de forma que possa causar danos, interferir em seu funcionamento normal ou infringir os direitos de terceiros. Além disso, o Usuário concorda em não publicar, enviar ou disponibilizar qualquer conteúdo ofensivo, discriminatório, sensível, ilegal ou que viole os direitos de terceiros.</p>
            
            <h2>3. Propriedade Intelectual</h2>
            <p>O Usuário reconhece e concorda que todos os direitos de propriedade intelectual relacionados ao website/aplicativo AQ.Chegamos, incluindo, mas não se limitando a direitos autorais, marcas registradas, nomes comerciais e patentes, pertencem ao Fornecedor ou a terceiros autorizados.</p>
            
            <h2>4. Conteúdo do Usuário</h2>
            <p>O Usuário pode ter a possibilidade de fornecer conteúdo para o website/aplicativo AQ.Chegamos. Ao fazê-lo, o Usuário concede ao Fornecedor uma licença não exclusiva, global, irrevogável e sublicenciável para utilizar, reproduzir, modificar, adaptar, publicar, traduzir e distribuir tal conteúdo com o objetivo de operar e melhorar o website/aplicativo e oferecer os serviços correspondentes. O Usuário reconhece e concorda que é responsável pelo conteúdo que publica e que não deve postar, enviar ou disponibilizar qualquer conteúdo ofensivo, discriminatório, sensível, ilegal ou que viole os direitos de terceiros. O Usuário que organiza a excursão é o único responsável pelas informações fornecidas sobre a excursão, incluindo datas, locais, preços e outras informações relevantes. O Fornecedor não se responsabiliza por informações erradas ou inconsistentes fornecidas pelo Usuário organizador da excursão.</p>
            
            <h2>5. Limitação de Responsabilidade</h2>
            <p>O Fornecedor não será responsável por quaisquer danos diretos, indiretos, incidentais, especiais ou consequenciais decorrentes do uso ou da impossibilidade de uso do website/aplicativo, incluindo, entre outros, perda de lucros, interrupção do negócio ou perda de informações, mesmo que o Fornecedor tenha sido avisado sobre a possibilidade de tais danos. O Usuário entende e concorda que é responsável por verificar a precisão e veracidade das informações fornecidas sobre as excursões antes de tomar qualquer decisão de participação ou reserva.</p>
            
            <h2>6. Lei Aplicável e Jurisdição</h2>
            <p>Este Contrato será regido e interpretado de acordo com a Legislação Brasileira. Qualquer disputa decorrente ou relacionada a este Contrato será submetida à jurisdição exclusiva dos tribunais competentes na jurisdição do Fornecedor.</p>
            
            <p>Data: <span id="data-atual"></span></p>
            <button onclick="history.back();">Voltar</button>

            <script>
              var dataAtual = new Date();
              var dia = dataAtual.getDate();
              var mes = dataAtual.getMonth() + 1; // Os meses são indexados de 0 a 11
              var ano = dataAtual.getFullYear();

              document.getElementById('data-atual').textContent = dia + '/' + mes + '/' + ano;
            </script>
        </div>
        </div>
		</div>
	</main>
	<script src="./js/slide.js"></script>
    <footer>
      <div class="eutemovoSA">
        <h3>AQ.Chegamos, eutemovoSA. São João del Rei - MG.  Brasil.</h3>
          <h4>Todos os Direitos reservados. Copyrights 2023.</h4>
      </div>
      <div class="div-p-footer">
        <p>Mapa do Site</p>
        <ul>
          <li><a href="index.php">Home</a></li>
          <li><a href="login.php">Login</a></li>
          <li><a href="userdashboard.php">Painel do Usuário Requer Login</a></li>
          <li><a href="fale.php">Fale Conosco</a></li>
          <li><a href="ajuda_e_suporte.php">Ajuda e Suporte</a></li>
          <li><a href="visualizar_termos_de_uso.php">Termos de Uso</a></li>		
        </ul>
      </div>
  </footer>
</body>
</html>
