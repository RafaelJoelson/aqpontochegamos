<?php
session_start();
include_once("connection.php");

if (!isset($_SESSION['userEmail'])) {
    // Redirecionar o usuário para a página de login se não estiver logado
    header("Location: ../login.php");
    exit();
} else {
    // Recupere as informações do usuário a partir das variáveis de sessão
    $CPF = $_SESSION['CPF'];
    $nome = $_SESSION['nome'];
    $email = $_SESSION['userEmail'];
    $senhaAtual = $_SESSION['senha'];
    $nav = '
      <ul>
        <li id="userStatus">Bem-vindo(a), ' . $nome . '</li> 
        <li><input type="button" value="Meu Painel" class="btn btn-primary" onclick="window.location.href=\'../userdashboard.php\'"></li>
        <li><input type="button" value="Sair" class="btn btn-outline-primary me-2" onclick="window.location.href=\'logout.php\'"></li>
      </ul>';

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

        $fileExtension = ".jpg";

        // Carregamento da imagem do usuário
        $userFileName = $CPF . $fileExtension;

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
    
    
}//sessão
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Cadastro</title>
    <link rel="stylesheet" type="text/css" href="../css/cads-styles.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="../css/croppie.css">
    <link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
    <script>
    // Função para verificar se os campos de senha e confirmação de senha são iguais
    function verificarSenhas() {
        var senhaAntiga = document.getElementById('coringa').value;
        var novaSenha = document.getElementById('senha').value;
        var confirmSenha = document.getElementById('confirma-senha').value;

        if (senhaAntiga === novaSenha) {
            document.getElementById('senha-error').innerHTML = 'A nova senha não pode ser igual à senha antiga';
        } else if (novaSenha !== confirmSenha) {
            document.getElementById('senha-error').innerHTML = 'As senhas não coincidem';
        } else {
            document.getElementById('senha-error').innerHTML = '';
        }
        }
    </script>
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
        var confirmado = confirm("Tem certeza que deseja excluir seu usuário?");
        if (confirmado) {
            console.log("Usuário Removido com Sucesso!");
        }
        return confirmado;
    }
    function confirmarAtualizacao() {
        var confirmado = confirm("Tem certeza que deseja atualizar seu usuário?");
        if (confirmado) {
            console.log("Usuário Atualizado com Sucesso!");
        }
        return confirmado;
    }
    function verificarEmails() {
    var email = document.getElementById('novoEmail').value;
    var confirmEmail = document.getElementById('confirma-novo-email').value;
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
				<li><a href="../userdashboard.php">> Meu Perfil</a></li>
				<li>> Editar Cadastro</li>
			</ul>
		</header>
		<div class="textCard">
            <div class="titulo">
                <h3>EDITAR CADASTRO</h3>
            </div>
			<form method="post" action="processa_usuario.php" enctype="multipart/form-data">
				<div class="image-preview">
                <div id="crop-container" class="circular-preview" data-imagem-atual="<?php echo $targetDir; ?>"><h3>Foto de Perfil:</h3></div>

				</div>
				<div class="crop-button">
					<input class="carregarImg" type="file" id="profile-pic" name="image" accept="image/*">
                    <input type="hidden" name="imagemAtual" value="<?php echo basename($targetDir); ?>">
				</div>
				<div class="campos">
					<h3 name="CPF">CPF: <?php echo $CPF ?></h3>
                    <h3 name="userEmail">Email: <?php echo $email; ?></h3>
                    
					<label for="userEmail">Alterar seu Email?</label>
                    <input type="email" id="novoEmail" name="novoEmail" value="" laceholder="seunovoemail@email.com"
                        oninput="verificarEmails()" maxlength="120">
                    <label for="confirm-email">*Confirme o email:</label>
                    <input type="email" id="confirma-novo-email" name="confirma-novo-email" placeholder="Confirme seu novo e-mail"
                        oninput="verificarEmails()" maxlength="120">
                    <div id="email-error" style="color: red;"></div>

					<label for="nome">Nome Completo:</label>
                    <input type="text" id="nome" name="nome" value="<?php echo $nome ?>" maxlength="60">
                    
                    <input type="hidden" id="coringa" name="coringa" value="<?php echo $senhaAtual ?>" maxlength="36">

                    <label for="password">Nova Senha:</label>
                    <input type="password" id="senha" name="senha" placeholder="De 4 a 16 caracteres" oninput="verificarSenhas()" maxlength="36">

                    <label for="confirm-password">*Repita a Senha:</label>
                    <input type="password" id="confirma-senha" name="confirma-senha" placeholder="Repita a senha" oninput="verificarSenhas()" maxlength="36">

                    <div id="senha-error" style="color: red;"></div>
                    
                    <p>Para atualizar ou excluir sua conta:</p>
                    <label for="SecretAnswer">Responda a pergunta secreta:</label>
                    <p>Caso esqueça sua senha ou resposta secreta.<a href="ajuda_e_suporte.php"> Clique aqui e saiba mais</a></p>
                    <h3><?php echo $perguntaSecreta ?></h3>

                    <label for="SecretAnswer">*Resposta Secreta:</label>
                    <input type="text" id="SecretAnswer" name="SecretAnswer" required maxlength="120">

                    <h3>Total de Excursões: <?php echo $qtde_excursoes ?></h3>
				</div>
				<button class="cadastrar" type="submit" name="atualizar" onclick="return confirmarAtualizacao();">Atualizar</button>
                <button class="excluir" type="submit" name="excluir" onclick="return confirmarExclusao();">Excluir</button>
			</form>
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