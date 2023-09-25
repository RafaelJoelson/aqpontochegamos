<?php
session_start();
include_once("connection.php");

if (isset($_SESSION['userEmail'])) {
    // Redirecionar o usuário para a página de logout se estiver logado
    echo "<script>alert('Você não tem permissão para acessar esta página. Você será desconectado.'); window.location.href = 'logout.php';</script>";
    header("Location: logout.php");
    exit();
}
$nav = '
      <ul>
        <li><input type="button" value="Login" class="btn btn-outline-primary me-2" onclick="window.location.href=\'../login.php\'"></li>
        <li><input type="button" value="Cadastre-se" class="btn btn-primary" onclick="window.location.href=\'termos_de_uso.php\'"></li>
      </ul>';

      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome = $_POST['userName'];
        $CPF = $_POST['CPF'];
        $CPF = str_replace(['.', '-'], '', $CPF);
    
        if (strlen($CPF) !== 11) {
            echo "CPF inválido.";
            exit;
        }
    
        $perguntaSecreta = $_POST['secretQuestion'];
        $respostaSecreta = $_POST['secretAnswer'];
    
        // Aqui você pode fazer o tratamento dos dados recebidos, como validação, sanitização, etc.
        //********************************************************************************************************************/
        // Verificar se os campos estão preenchidos corretamente, validar o formato do CPF.
        // Verificar se o e-mail e senha nos campos de confirmação coincidem.
        //********************************************************************************************************************/
        $email = $_POST['userEmail'];
        $confirmaEmail = $_POST['confirm-email'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $email != $confirmaEmail) {
            // Redirecionar o usuário para uma página de erro ou exibir uma mensagem de erro
            header("Location: erro.php?code=06");
            exit;
        }

        $emailConfirmado = $email;

    
        $senha = $_POST['senha'];
        $confirmaSenha = $_POST['confirma-senha'];

        if ($senha !== $confirmaSenha) {
            header("Location: erro.php?code=05");
            exit;
        } else {
            $senhaConfirmada = $confirmaSenha;
        }

    
        /*********************************************************************************************************************/
        // Verificar se foi enviado um arquivo de imagem
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['image'];
            // Obter o caminho temporário do arquivo
            $tempFilePath = $file['tmp_name'];
    
            // Definir o diretório onde a imagem será salva
            $targetDir = '../imgs/users/';
    
            $fileExtension = "jpg";
    
            // Gerar um novo nome de arquivo com base no CPF do usuário
            $newFileName = $CPF . '.' . $fileExtension;
    
            // Definir o caminho completo do arquivo de destino
            $targetPath = $targetDir . $newFileName;
    
            // Mover o arquivo para o diretório de destino
            if (move_uploaded_file($tempFilePath, $targetPath)) {
                // O arquivo foi salvo com sucesso
    
                // Agora, podemos continuar com a verificação do email e inserção dos dados no banco de dados
    
                // Verificar se o email já está em uso
                $queryVerificaEmail = "SELECT * FROM usuario WHERE email = '$emailConfirmado'";
                $resultVerificaEmail = mysqli_query($conexao, $queryVerificaEmail);
                // Verificar se o CPF já está em uso
                $queryVerificaCPF = "SELECT * FROM usuario WHERE CPF = '$CPF'";
                $resultVerificaCPF = mysqli_query($conexao, $queryVerificaCPF);
    
                if ($resultVerificaEmail && mysqli_num_rows($resultVerificaEmail) > 0) {
                    // Email já está em uso
                    header("Location: erro.php?code=05");
                    exit;
                } else if ($resultVerificaCPF && mysqli_num_rows($resultVerificaCPF) > 0) {
                    // CPF já está em uso
                    header("Location: erro.php?code=05");
                    exit;
                } else {
                    // Email não está em uso, pode prosseguir com a inserção dos dados no banco de dados
    
                    $query = "INSERT INTO usuario (CPF, nome, email, senha, perguntaSecreta, respostaSecreta) 
                        VALUES ('$CPF', '$nome', '$emailConfirmado', '$senhaConfirmada', '$perguntaSecreta', '$respostaSecreta')";
                    $result = mysqli_query($conexao, $query);
    
                    if ($result) {
                        // Se a inserção foi bem-sucedida, redirecionar o usuário para uma página de sucesso ou exibir uma mensagem de sucesso.
                        header("Location: confirma_prim_cadastro.php");
                        exit;
                    } else {
                        // Se ocorreu algum erro na inserção, redirecionar o usuário para uma página de erro ou exibir uma mensagem de erro.
                        header("Location: erro.php?code=01");
                        exit;
                    }
                }
            } else {
                // Ocorreu um erro ao salvar o arquivo
                header("Location: erro.php?code=02");
                exit;
            }
        } else {
            // Não foi enviado um arquivo de imagem
            header("Location: erro.php?code=03");
            exit;
        }
    }
    ?>
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Cadastro</title>
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
                    <li><a href="../index.php">Home</a></li>
                    <li>> Cadastro</li>     
                </ul>
            </header>
            <div class="titulo">
			    <h3>CADASTRO</h3>
		    </div>
            <div class="textCard">
                <h4>*ATENÇÃO: Prezado usuário de teste. Não utilize seu nome completo e nem dados reais.</h4>
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
                    <div class="image-preview">
                        <div id="crop-container" class="circular-preview">
                            <label for="image">Foto de Perfil:</label>
                        </div>
                    </div>
                    <div class="crop-button">
                        <input class="carregarImg" type="file" id="image" name="image" accept="image/*">
                    </div>
                    <div class="campos">
                        <label for="userName">*Nome Completo:</label>
                        <input type="text" id="userName" name="userName" maxlength="60" required>
    
                        <label for="CPF">*CPF:</label>
                        <input type="text" id="CPF" name="CPF" placeholder="Somente números" maxlength="14"  required oninput="formatarCPF(this)">
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
                        var email = document.getElementById('userEmail').value;
                        var confirmEmail = document.getElementById('confirm-email').value;

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
                        // Função para verificar se os campos de senha e confirmação de senha são iguais
                        function verificarSenhas() {
                            var senha = document.getElementById('senha').value;
                            var confirmSenha = document.getElementById('confirma-senha').value;
    
                            if (senha !== confirmSenha) {
                                document.getElementById('senha-error').innerHTML = 'As senhas não coincidem';
                            } else {
                                document.getElementById('senha-error').innerHTML = '';
                            }
                        }
                        function confirmarCadastro() {
                            var confirmado = confirm("Suas informações estão corretas? Você será direcionado para o Login.");
                            if (confirmado) {
                                console.log("Usuário Cadastrado com Sucesso! Efetue login de sua conta");
                            }
                            return confirmado;
                        }
                        </script>
    
                        <label for="userEmail">*Email:</label>
                        <input type="email" id="userEmail" name="userEmail" placeholder="seuemail@email.com"
                            oninput="verificarEmails()" maxlength="120" required>
                            
                        <label for="confirm-email">*Confirme o email:</label>
                        <input type="email" id="confirm-email" name="confirm-email" placeholder="Confirme seu e-mail"
                            oninput="verificarEmails()" maxlength="120" required>
                        <div id="email-error" style="color: red;"></div>


                        <label for="password">*Senha:</label>
                        <input type="password" id="senha" name="senha" placeholder="De 4 a 16 caracteres"
                            oninput="verificarSenhas()" maxlength="36" required>

                        <label for="confirm-password">*Repita a Senha:</label>
                        <input type="password" id="confirma-senha" name="confirma-senha" placeholder="Repita a senha"
                            oninput="verificarSenhas()" maxlength="36" required>
                        <div id="senha-error" style="color: red;"></div>

                        <label for="secretQuestion">*Defina uma pergunta secreta:</label>
                        <p>Para que serve isso?.<a href="../ajuda_e_suporte.php"> Clique aqui e saiba mais</a></p>
                        <select type="text" id="secretQuestion" name="secretQuestion" required>
                            <option value="Qual era o nome do seu primeiro animal de estimação?">Qual era o nome do seu primeiro animal de estimação?</option>
                            <option value="Qual é sua comida favorita?">Qual é sua comida favorita?</option>
                            <option value="Qual era meu personagem favorito na infância?">Qual era meu personagem favorito na infância?</option>
                            <option value="Quem você levaria para uma ilha deserta?">Quem você levaria para uma ilha deserta?</option>
                            <option value="Qual foram maior momento realizado em sua vida?">Qual foram maior momento realizado em sua vida?</option>
                            <option value="Se você pudesse morar em qualquer cidades do mundo, qual seria?">Se você pudesse morar em qualquer cidades do mundo, qual seria?</option>
                            <option value="Qual língua gostaria de aprender?">Qual língua gostaria de aprender?</option>
                            <option value="Para onde gostaria de viajar nas férias?">Para onde gostaria de viajar nas férias?</option>
                            <option value="Qual é seu filme favorito?">Qual é seu filme favorito?</option>
                            <option value="Café ou Coca-cola?">Café ou Coca-cola?</option>
                        </select>

                        <label for="secretAnswer">*Resposta Secreta:</label>
                        <input type="text" id="secretAnswer" name="secretAnswer" maxlength="120" required>

                        <p>* Campos Obrigatórios </p>
                    </div>
                    <input class="cadastrar" type="submit" value="Cadastrar" onclick="return confirmarCadastro();">
                </form>
            </div>
        </main>
        <!--Script que carrega e recorta a imagem-->
        <script src="../js/croppie.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var preview = document.getElementById('crop-container');
                var input = document.getElementById('image');
                var croppie = new Croppie(preview, {
                    viewport: { width: 200, height: 200, type: 'circle' },
                    boundary: { width: 300, height: 300 },
                    enableOrientation: true
                });
    
                var defaultImageUrl = '../imgs/users/userDefaultimg.png';
    
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
    