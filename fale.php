<?php
session_start();
include_once("./php/connection.php");

  if (!isset($_SESSION['userEmail'])) {
    // Verifica se o usuário está logado e altera a nav.
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
        <title>Fale Conosco</title>
        <link rel="stylesheet" type="text/css" href="./css/cads-styles.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="./css/croppie.css">
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
                <ul class="navmap">
                    <li><a href="#" onclick="history.back();">Voltar</a></li>
                    <li>> Fale Conosco</li>     
                </ul>
            </header>
            <div class="textCard">
                <div class="chamado-container"><h2>Acompanhar um Chamado</h2>
                    <form class="prot1" action="./php/visualizar_chamado.php" method="POST">
                    <div>
                        
                        <div class="img-suporte">
                            <img src="./imgs/spbusicon.png" width="">   
                        </div>
                        <label for="protocolo">*Protocolo: </label>
                        <input type="text" id="busca" name="busca" required>

                        <input name="buscarpt"type="submit" value="Buscar"> 
                    </div>  
                    </form>
                </div> 
                <div class="chamado-container"><h2>Abrir Chamado</h2>
                    <form class="prot1" action="./php/processa_chamado.php" method="POST" enctype="multipart/form-data">
                        <label for="CPF">CPF:(*Caso seja cadastrado)</label>
                        <input type="text" id="CPF" name="CPF" placeholder="Somente números" maxlength="14" oninput="formatarCPF(this)">
                        <script>
                        function formatarCPF(input) {
                            var value = input.value;
                            value = value.replace(/\D/g, ''); // Remove todos os caracteres não numéricos
                            value = value.replace(/(\d{3})(\d)/, '$1.$2'); // Adiciona ponto após os primeiros 3 dígitos
                            value = value.replace(/(\d{3})(\d)/, '$1.$2'); // Adiciona ponto após os próximos 3 dígitos
                            value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2'); // Adiciona hífen antes dos últimos 2 dígitos
                            input.value = value;
                        }
                        </script>
                        <script>
                          // Função para verificar se os campos de e-mail e confirmação de e-mail são iguais
                        function verificarEmails() {
                        var email = document.getElementById('email-contato').value;
                        var confirmEmail = document.getElementById('confirma_contato').value;

                        if (email !== confirmEmail) {
                            document.getElementById('email-error').innerHTML = 'Os e-mails não coincidem';
                        } else if (email !== '' && !validateEmail(email)) {
                            document.getElementById('email-error').innerHTML = 'Formato de e-mail inválido';
                        } else {
                            document.getElementById('email-error').innerHTML = '';
                        }
                        }

                        function validateEmail(email) {
                            // Expressão regular para validar o formato do e-mail
                            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                            return emailRegex.test(email);
                        }
                        </script>
                        <div class="fcontainer">
                            <label for="email-contato">*Email de Contato:</label>
                            <input type="email" id="email-contato" name="email-contato" maxlength="120" placeholder="Seu email" oninput="verificarEmails()" required><br>
                            
                            <label for="confirma_contato">*Confirme o Email:</label>
                            <input type="email" id="confirma_contato" name="confirma_contato" maxlength="120" placeholder="Confirme seu email" oninput="verificarEmails()" required>
                            <div id="email-error" style="color: red;"></div><br>
                        </div>
                        <label for="tipo_chamado">*Tipo do Problema:</label>
                            <select id="tipo_chamado" name="tipo_chamado" required>
                                <option value="">Selecione</option>
                                <option value="Reportar Problema">Reportar Problema</option>
                                <option value="Reportar Usuário">Reportar Usuário</option>
                                <option value="Chamado de Suporte">Chamado de Suporte</option>
                                <option value="Dúvidas ou Sugestões">Dúvidas ou Sugestões</option>
                            </select>    
                        
                        <label for="descricao">*Descrição do Problema (máximo 255 caracteres):</label>
                        <textarea id="descricao" name="descricao" maxlength="255" required></textarea><br>
                        <div class="print-tela">
                            <label for="image">Print da Tela:</label>
                            <input type="file" id="cover" name="image" accept="image/*">
                        </div>
                            <input type="submit" value="Enviar">
                    </form>            
                </div>
            </div>
        </main>
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
    