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
    // Recupere as informações do usuário a partir das variáveis de sessão

    $ADMnome = $_SESSION['nome'];
    $AMDemail = $_SESSION['userEmail'];
    $nav = '
      <ul>
        <li id="userStatus">Bem-vindo(a), ' . $ADMnome . '</li> 
        <li><input type="button" value="Meu Painel" class="btn btn-primary" onclick="window.location.href=\'adm_userdashboard.php\'"></li>
        <li><input type="button" value="Sair" class="btn btn-outline-primary me-2" onclick="window.location.href=\'logout.php\'"></li>
      </ul>';

    if (isset($_GET['CPF'])) {//Recupera o CPF pela URL
        $CPF = $_GET['CPF'];

        // Consulta as informações de segurança do usuário no banco de dados
        $query = "SELECT usuario.CPF, usuario.nome, usuario.email, usuario.senha, 
        usuario.perguntaSecreta, usuario.respostaSecreta, 
        COUNT(excursao.ID) AS qtde_excursoes
        FROM usuario
        LEFT JOIN excursao ON usuario.CPF = excursao.cpf_excurseiro
        WHERE usuario.CPF = '$CPF'
        GROUP BY usuario.CPF, usuario.nome, usuario.email, usuario.senha, usuario.perguntaSecreta, usuario.respostaSecreta";

        $result = mysqli_query($conexao, $query);

        if ($result) {
            $rows = mysqli_fetch_assoc($result);
            $CPF = $rows['CPF'];
            $nome = $rows['nome'];
            $email = $rows['email'];
            $senhaAtual = $rows['senha'];
            $perguntaSecreta = $rows['perguntaSecreta'];
            $respostaSecreta = $rows['respostaSecreta'];
            $qtde_excursoes = $rows['qtde_excursoes'];

        } else {
            // Tratar erro ao consultar informações de segurança do usuário
            header("Location: erro.php?code=18");
            exit;
        }

        $fileExtension = "jpg";

        // Carregamento da imagem do usuário
        $userFileName = $CPF . '.' . $fileExtension;

        // Diretório onde as imagens estão armazenadas
        $targetDir = '../imgs/users/';

        // Obter a lista de arquivos que correspondem ao padrão de nome
        $arquivos = glob($targetDir . $userFileName);

        // Verificar se foi encontrado algum arquivo
        if (count($arquivos) > 0) {
            // Encontrou o arquivo de imagem correspondente ao CPF, usar esse arquivo para exibir a imagem
            $targetDir .= basename($arquivos[0]) . '?v=' . time();
        } else {
            // Não foi encontrado um arquivo de imagem correspondente ao CPF, usar uma imagem padrão
            $targetDir .= 'userDefaultimg.png';
        }           
    }//GET

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>ADM Editar Usuário</title>
    <link rel="stylesheet" type="text/css" href="../css/admcads-styles.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="../css/croppie.css">
    <link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
    <!--Script que carrega e recorta a imagem-->
    <script src="../js/croppie.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    var preview = document.getElementById('crop-container');
    var input = document.getElementById('profile-pic');
    var croppie = new Croppie(preview, {
        viewport: { width: 200, height: 200, type: 'circle' },
        boundary: { width: 300, height: 300 },
        enableOrientation: true
    });

    var defaultImageUrl = preview.getAttribute('data-imagem-atual');

    // Carrega a imagem do usuário ou a imagem padrão na inicialização, se o campo de imagem estiver vazio
    if (input.value === '') {
        croppie.bind({
            url: defaultImageUrl
            });
        }

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
<script>
    function confirmarExclusao() {
        var confirmado = confirm("Tem certeza que deseja excluir o usuário?");
        if (confirmado) {

            console.log("Usuário Removido com Sucesso!");
        }
        return confirmado;
    }
    
    function confirmarAtualizacao() {
        var confirmado = confirm("Tem certeza que deseja atualizar o usuário?");
        if (confirmado) {
            console.log("Usuário Atualizado com Sucesso!");
        }
        return confirmado;
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
			<ul class="navmap">
				<li><a href="../index.php">SAIR PARA HOME</a></li>
				<li><a href="adm_userdashboard.php">/ Meu Painel</a></li>
				<li>/ Editar Cadastro</li>
			</ul>
		</header>
		<div class="textCard">
			<h3>EDITAR CADASTRO</h3>
			<form action="adm_processa_usuario.php?CPF=<?php echo $CPF?>" method="POST" enctype="multipart/form-data">
				<div class="image-preview">
                <div id="crop-container" class="circular-preview" data-imagem-atual="<?php echo $targetDir; ?>"><h3>Foto de Perfil:</h3></div>
				</div>
				<div class="crop-button">
					<input class="carregarImg" type="file" id="profile-pic" name="image" accept="image/*">
                    <input type="hidden" name="imagemAtual" value="<?php echo basename($targetDir); ?>">
				</div>
                <script>
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
                // Função para verificar se os campos de e-mail e confirmação de e-mail são iguais
                function verificarEmails() {
                                    var email = document.getElementById('email').value;
                                    var confirmEmail = document.getElementById('confirm-email').value;
            
                                    if (email !== confirmEmail) {
                                        document.getElementById('email-error').innerHTML = 'Os e-mails não coincidem';
                                    } else {
                                        document.getElementById('email-error').innerHTML = '';
                                    }
                                }
                </script>
				<div class="campos">
					<h4 name="CPF">CPF: <?php echo $CPF ?></h4>
                    <input maxlength="11" type="text" id="CPF_EXCLUIR" name="CPF_EXCLUIR" value="<?php echo $CPF ?>">
                    <label for="nome">Nome Completo:</label>
					<input type="text" id="nome" name="nome" maxlength="120" value="<?php echo $nome ?>"required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email"  maxlength="120" placeholder="seuemail@email.com"
                    oninput="verificarEmails()" value="<?php echo $email ?>" required>
    
                    <label for="confirm-email">Confirme o email:</label>
                    <input type="email" id="confirma-novo-emaill"  maxlength="120" name="confirma-novo-email" placeholder="Confirme seu e-mail"
                    oninput="verificarEmails()">
                    <div id="email-error" style="color: red;"></div>
                    
					<input type="hidden" id="coringa" name="coringa" maxlength="36" placeholder="De 4 a 16 caracteres" value="<?php echo $senhaAtual ?>">
    
					<label for="password"> Nova Senha:</label>
					<input type="password" id="senha" name="senha" maxlength="36 "placeholder="De 4 a 16 caracteres" oninput="verificarSenhas()">

					<label for="confirm-password">Repita a Senha:</label>
					<input type="password" id="confirma-senha" name="confirma-senha" maxlength="36" placeholder="Repita a senha"oninput="verificarSenhas()">
					<div id="senha-error" style="color: red;"></div>
					<p>Para atualizar ou excluir sua conta:</p>
					<label for="SecretAnswer">Responda a pergunta secreta:</label>
					<h4><?php echo $perguntaSecreta ?></h4>
					<label for="SecretAnswer">Resposta Secreta:</label>
					<input type="text" id="SecretAnswer" name="SecretAnswer" maxlength="120" value="<?php echo $respostaSecreta ?>"required>
                    <h4>TOTAL DE EXCURSÕES DO USUÁRIO: <?php echo $qtde_excursoes ?></h4>
				</div>
				<button class="cadastrar" type="submit" name="atualizar" onclick="return confirmarAtualizacao();">Atualizar</button>
                <button class="excluir" type="submit" id="excluir"name="excluir" onclick="return confirmarExclusao();">Excluir</button>
			</form>
		</div>
	</main>
<footer>
      <h3>AQ.Chegamos, eutemovoSA. São João del Rei - MG.  Brasil.</h3>
      <h4>Todos os Direitos reservados. Copyrights 2023.</h4>
</footer>
</body>
</html>