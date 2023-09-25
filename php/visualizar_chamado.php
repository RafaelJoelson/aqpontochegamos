<?php
session_start();
include_once("connection.php");

  if (!isset($_SESSION['userEmail'])) {
    // Verifica se o usuário está logado e altera a nav.
    $nav = '
      <ul>
        <li><input type="button" value="Login" class="btn btn-outline-primary me-2" onclick="window.location.href=\'login.php\'"></li>
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
?>
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Visualizar Chamado</title>
        <link rel="stylesheet" type="text/css" href="../css/cads-styles.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="../css/croppie.css">
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
                    <li><a href="#" onclick="history.back();">Voltar</a></li>
                    <li>> Fale Conosco</li>     
                </ul>
            </header>
            <div class="textCard">
          
            <div class ="card" id="prot2">
                <?php 
                      if (isset($_GET['protocolo'])) {
                        $protocolo = $_GET['protocolo'];

                        // Consulta na tabela de suporte por metodo GET
                        $suporte = "SELECT * FROM suporte WHERE protocolo = '$protocolo'";
                        $result = mysqli_query($conexao, $suporte) or die("Não foi possível a consulta");
                        $rows = mysqli_fetch_array($result);
                        $protocolo = $rows['protocolo'];
                        $tipo_chamado = $rows['Tipo_do_chamado'];
                        $descricao = $rows['descricao_chamado'];
                        $status_chamado = $rows['status_chamado'];
                        $data_criacao = $rows['data_criacao'];
                        if($rows != 0){
                        echo '
                        <h2>Seu Protocolo: '.$protocolo.'</h2>
                        <p>Anote, copie seu protocolo. Você precisa dele para acompanhar seu chamado!</p>
                        <h3>Tipo do Chamado: '.$tipo_chamado.'</h3>
                        <h3>Descrição do Problema</h3>
                        <p>'.$descricao.'</p>
                        <p>Data de abertura: '.$data_criacao.'</p>
                        <h3>Status: '.$status_chamado.'</h3>
                        <h2>Em breve entraremos em contato pelo seu e-mail</h2>                 
                        ';
                        } else {
                            // Protocolo está incorreto ou não existe.
                            header("Location: erro.php?code=26");
                            exit();
                        }
                      } else { 
                    
                      if (isset($_POST['busca'])) {
                        $protocolo = $_POST['busca'];
                        
                        // Consulta na tabela de suporte por metodo POST
                        $suporte = "SELECT * FROM suporte WHERE protocolo = '$protocolo'";
                        $result = mysqli_query($conexao, $suporte) or die("Não foi possível a consulta");
                        $rows = mysqli_fetch_array($result);
                        $protocolo = $rows['protocolo'];
                        $tipo_chamado = $rows['Tipo_do_chamado'];
                        $descricao = $rows['descricao_chamado'];
                        $status_chamado = $rows['status_chamado'];
                        $data_criacao = $rows['data_criacao'];

                        if($rows != 0){
                        echo '
                        <h2>Seu Protocolo: '.$protocolo.'</h2>
                        <p>Anote, copie seu protocolo. Você precisa dele para acompanhar seu chamado!</p>
                        <h3>Tipo do Chamado: '.$tipo_chamado.'</h3>
                        <h3>Descrição do Problema</h3>
                        <p>'.$descricao.'</p>
                        <p>Data de abertura: '.$data_criacao.'</p>
                        <h3>Status: '.$status_chamado.'</h3>
                        <h2>Em breve entraremos em contato pelo seu e-mail</h2>               
                        ';
                        } else {
                            
                            // Protocolo está incorreto ou não existe.
                            header("Location: erro.php?code=26");
                            exit();
                        }
                      } else {

                        // Erro ao submeter busca
                        header("Location: erro.php?code=04");
                        exit();

                      }
                    }
                
                ?>         
                <button onclick="history.back();">Voltar</button>
            </div>
                        

            </div>
        </main>
        <!--Script que carrega e recorta a imagem-->
        <script src="./js/croppie.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var preview = document.getElementById('crop-container');
                var input = document.getElementById('image');
                var croppie = new Croppie(preview, {
                    viewport: { width: 200, height: 200, type: 'circle' },
                    boundary: { width: 300, height: 300 },
                    enableOrientation: true
                });
    
                var defaultImageUrl = './imgs/users/userDefaultimg.png';
    
                // Carrega a imagem padrão na inicialização
                croppie.bind({
                    url: defaultImageUrl
                });
    
                input.addEventListener('change', function(e) {
                    var file = e.target.files[0];
                    var reader = new FileReader();
    
                    reader.onload = function(e) {
                        croppie.bind({
                            url: e.target.result
                        });
                    }
    
                    reader.readAsDataURL(file);
                });
            });
        </script>
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
    