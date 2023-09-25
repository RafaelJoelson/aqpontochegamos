<?php
session_start();
include_once('connection.php');

$session_CPF = $_SESSION['CPF'];
$session_nome = $_SESSION['nome'];
$session_email = $_SESSION['userEmail'];

// Consulta as informações de segurança do usuário no banco de dados
$query = "SELECT CPF, nome, senha, perguntaSecreta, respostaSecreta
FROM usuario WHERE CPF = '$session_CPF'";

$result = mysqli_query($conexao, $query);

if ($result) {

    $rows = mysqli_fetch_assoc($result);
    $CPF = $rows['CPF'];
    $nome = $rows['nome'];
    $senhaAtual = $rows['senha'];
    $perguntaSecreta = $rows['perguntaSecreta'];
    $respostaSecreta = $rows['respostaSecreta'];

} else {
    // Tratar erro ao consultar informações de segurança do usuário
    header("Location: erro.php?code=18");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $respostaSecretaFormulario = $_POST["SecretAnswer"];
    $newSecretQuestion = $_POST["new_secretQuestion"];
    $newSecretAnswer = $_POST["new_secretAnswer"];
    $NovaSenha = $_POST["senha"];
    //Verificar se a respostaScreta é igual.
    if ($respostaSecretaFormulario === $respostaSecreta ) {
        //Verificar se senha Nova é diferente da antiga.
        if($senhaAtual != $NovaSenha ) {
            // Atualiza as credenciais
            $updateQuery = "UPDATE usuario SET perguntaSecreta = '$newSecretQuestion', 
            respostaSecreta = '$newSecretAnswer', senha = '$NovaSenha' WHERE CPF = '$CPF'";
            $result = mysqli_query($conexao, $updateQuery);
            if($result){
            //Redireciona para Logout
            header("Location: logout.php");
            exit;
            }
        } else {
            //Senhas não podem ser iguais.
            header("Location: erro.php?code=23");
            exit;
        }
    } else {
        //Resposta Secreta incorreta!
        header("Location: erro.php?code=09");
        exit;

    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Redefinição de segurança</title>
    <link rel="stylesheet" type="text/css" href="../css/login-style.css" media="screen" />
    <link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
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

        // Função para exibir o aviso de confirmação
        
    </script>
</head>
<body>
    <main>
        <header>
            <nav>
                <div class="navFaixa">
                    <a href="../index.php"><img src="../imgs/aq_logo.png" width=""></img></a>
                </div>
            </nav>
            <ul class="navmap">
                <li><a href="../index.php">Home</a></li>
                <li>> Redefinição de senha ou Pergunta secreta</li>
            </ul>
        </header>
        <div class="login">
            <form class="formQuestion" method="POST">
                <h3><?php echo "Bem-vindo(a), " . $session_nome; ?></h3>
                <h3>*Por favor, responda sua atual pergunta secreta:</h3>
                <label for="userSecretQuestion">*<?php echo $perguntaSecreta; ?></label>
                <input type="text" id="userSecretAnswer" name="SecretAnswer" maxlength="120" placeholder="Resposta">

                <label for="new_secretQuestion">*Defina uma nova pergunta secreta:</label>
                <select type="text" id="new_secretQuestion" name="new_secretQuestion" required>
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
                <label for="new_secretAnswer">*Nova resposta Secreta:</label>
                <input type="text" id="new_secretAnswer" name="new_secretAnswer" maxlength="120" required>

                <label for="password">*Nova Senha:</label>
                <input type="password" id="senha" name="senha" maxlength="36" placeholder="De 4 a 16 caracteres" oninput="verificarSenhas()">

                <label for="confirm-password">*Repita a Nova Senha:</label>
                <input type="password" id="confirma-senha" name="confirma-senha" maxlength="36" placeholder="Repita a senha" oninput="verificarSenhas()">
                <div id="senha-error" style="color: red;"></div>
                <input type="submit" value="Enviar" onclick="return confirmarRedefinicao();">
                <p>* Campos Obrigatórios</p>
                <br>
                <a href="../ajuda_e_suporte.php" class="forgot-password">Ajuda ou Suporte</a>
            </form>
        </div>
    </main>
    <script src="../js/main.js"></script>
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
