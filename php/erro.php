<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo "ERRO!Cod: ".$_GET['code']; ?></title>
    <link rel="stylesheet" type="text/css" href="../css/erropage-style.css" media="screen" />
    <link href="../favicon.ico" rel="shortcut icon" type="image/x-icon" />
</head>
<body>
    <main>
        <header>
            <nav>
                <div class="navFaixa">
                    <a href="../index.php"><img src="../imgs/aq_logo.png" width=""></img></a>
                </div>
            </nav>
        </header>
        <div class="textCard">
            <h3>OPS! Algo deu errado!</h3>
            <div class="card">
            <?php
            // Recupera o código de erro da URL
            $errorCode = $_GET['code'];

            // Define as mensagens de erro correspondentes aos códigos
            $errorMessages = [

                // Adicione mais códigos de erro e mensagens aqui, se necessário
                
                '01' => 'Código: 01 - Erro ao salvar o registro do usuário.',   
                '02' => 'Código: 02 - Erro ao salvar a imagem.',
                '03' => 'Código: 03 - Nenhuma imagem foi carregada.',
                '04' => 'Código: 04 - Falha na conexão com o banco de dados.',
                '05' => 'Código: 05 - O e-mail informado já esta em uso.',
                '06' => 'Código: 06 - O CPF informado já esta em uso.',
                '07' => 'Código: 07 - Usuário não encontrado.',
                '08' => 'Código: 08 - Senha ou E-mail incorretos.',
                '09' => 'Código: 09 - Resposta secreta incorreta.',
                '10' => 'Código: 10 - Ocorreu um erro ao remover a excursão.',
                '11' => 'Código: 11 - ID da excursão inválido ou não pode ser carregado.',
                '12' => 'Código: 12 - Erro ao remover usuário. CPF inválido ou não pode ser carregado.',
                '13' => 'Código: 13 - Erro ao remover a excursão. ID da excursão inválido ou não pode ser carregado.',
                '14' => 'Código: 14 - Ocorreu um erro ao salvar as imagens.',
                '15' => 'Código: 15 - Erro ao salvar o registro da excursão.',
                '16' => 'Código: 16 - Erro ao atualizar usuário. CPF inválido ou não pode ser carregado.',
                '17' => 'Código: 17 - Erro ao atualizar usuário.',
                '18' => 'Código: 18 - Erro ao consultar informações do usuário no banco de dados.',
                '19' => 'Código: 19 - Erro ao submeter formulário.',
                '20' => 'Código: 20 - Erro ao remover usuário.',
                '21' => 'Código: 21 - Os campos e-mails não conferem.',
                '22' => 'Código: 22 - Os campos das senhas não conferem.',
                '23' => 'Código: 23 - A Nova senha não pode ser igual a anterior.',
                '24' => 'Código: 24 - A data de chegada não pode ser anterior à data de partida.',
                '25' => 'Código: 25 - A data de retorno não pode ser anterior à data de chegada.',
                '26' => 'Código: 25 - Protocolo não encontrado ou incorreto.',
            ];

            // Verifica se o código de erro existe nas mensagens definidas
            if (array_key_exists($errorCode, $errorMessages)) {
                // Exibe a mensagem de erro correspondente ao código
                echo '<h2>' . $errorMessages[$errorCode] . '</h2>';
            } else {
                // Código de erro inválido ou não encontrado
                echo '<h2>Erro desconhecido.</h2>';
            }
            ?> <h4>Deseja abrir um chamado?<a href="../fale.php"> Clique Aqui</a></h4>
                <img src="../imgs/bus404.png" width ="200" alt="">    
               
            </div>
            
            <input type="button" value="Voltar" onclick="history.back();">
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
				<li><a href="../userdashboard.php">Painel do Usuário</a></li>
				<li><a href="../fale.php">Fale Conosco</a></li>
				<li><a href="../ajuda_e_suporte.php">Ajuda e Suporte</a></li>
				<li><a href="../visualizar_termos_de_uso.php">Termos de Uso</a></li>		
			</ul>
		</div>
	</footer>
</body>
</html>
