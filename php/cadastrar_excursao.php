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
    $nav = '
      <ul>
        <li id="userStatus">Bem-vindo(a), ' . $nome . '</li> 
        <li><input type="button" value="Meu Painel" class="btn btn-primary" onclick="window.location.href=\'../userdashboard.php\'"></li>
        <li><input type="button" value="Sair" class="btn btn-outline-primary me-2" onclick="window.location.href=\'logout.php\'"></li>
      </ul>';
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

// Coleta das informações da excursão
$id = 'EXC' . uniqid('', true); // Gera o ID da EXCURSÃO
$id = substr($id, 0, 15); // Limita o ID a 15 caracteres
$data = date('Y-m-d'); // Obtém apenas a data no formato "Ano-mês-dia"
$hora = date('H:i:s'); // Obtém apenas a hora no formato "Hora:minutos"
$CPF_excurseiro = $CPF;
$title = $_POST['titulo'];
$origem = $_POST['origem'];
$destino = $_POST['destino'];
$descricao = $_POST['descricao'];
$preco = $_POST['valor'];
$vagas = $_POST['vagas'];
$dataPartida = $_POST['dataPartida'];
$horaPartida = $_POST['hpartida'];
$dataChegada = $_POST['dataChegada'];
$horaChegada = $_POST['hchegada'];
$dataRetorno = $_POST['dataRetorno'];
$pontoEmbarque = $_POST['pontoEmbarque'];
$pontoDesembarque = $_POST['pontoDesembarque'];
$telefone = $_POST['telefone'];
//Formata a entrada do telefone na tabela para somente númericos
$telefone = str_replace(['-', '(', ')', ' '], '', $telefone);
$horaRetorno = $_POST['horaVolta'];

// Verifique se os três campos de arquivo foram enviados e não têm erros
if (isset($_FILES['image1']) && $_FILES['image1']['error'] === UPLOAD_ERR_OK &&
    isset($_FILES['image2']) && $_FILES['image2']['error'] === UPLOAD_ERR_OK &&
    isset($_FILES['image3']) && $_FILES['image3']['error'] === UPLOAD_ERR_OK) {

    $file1 = $_FILES['image1'];
    $file2 = $_FILES['image2'];
    $file3 = $_FILES['image3'];

    // Diretório de destino para as imagens
    $targetDir = "../imgs/userBusPics/";
    $fileExtension = "jpg";

    // Processe e salve a primeira imagem
    $tempFilePath1 = $file1['tmp_name'];
    $targetPath1 = $targetDir . $id . '_1.' . $fileExtension;
    if (move_uploaded_file($tempFilePath1, $targetPath1)) {
        // Imagem 1 salva com sucesso

        // Processe e salve a segunda imagem
        $tempFilePath2 = $file2['tmp_name'];
        $targetPath2 = $targetDir . $id . '_2.' . $fileExtension;
        if (move_uploaded_file($tempFilePath2, $targetPath2)) {
            // Imagem 2 salva com sucesso

            // Processe e salve a terceira imagem
            $tempFilePath3 = $file3['tmp_name'];
            $targetPath3 = $targetDir . $id . '_3.' . $fileExtension;
            if (move_uploaded_file($tempFilePath3, $targetPath3)) {
                // Imagem 3 salva com sucesso

                // Inserir os dados na tabela "excursao"
                $query = "INSERT INTO excursao (ID, cpf_excurseiro, titulo, descricao, vagas, cidadeOrigem, cidadeDestino, horaPartida, horaChegada, dataIda, dataChegada, dataVolta, preco, pontoEmbarque, pontoDesembarque, telefone, horaVolta)
				VALUES ('$id', '$CPF_excurseiro', '$title', '$descricao', '$vagas', '$origem', '$destino', '$horaPartida', '$horaChegada', '$dataPartida', '$dataChegada', '$dataRetorno', '$preco', '$pontoEmbarque', '$pontoDesembarque','$telefone','$horaRetorno')";
				$result = mysqli_query($conexao, $query);

                if ($result) {
                    // Se a inserção foi bem-sucedida, redirecionar o usuário para uma página de sucesso ou exibir uma mensagem de sucesso.
                    header("Location: confirma_excursao.php");
                    exit;
                } else {
                    // Se ocorreu algum erro na inserção, redirecionar o usuário para uma página de erro ou exibir uma mensagem de erro.
                    header("Location: erro.php?code=01");
                    exit;
                }
            } else {
                // Ocorreu um erro ao salvar a terceira imagem
                header("Location: erro.php?code=023");
                exit;
            }
        } else {
            // Ocorreu um erro ao salvar a segunda imagem
            header("Location: erro.php?code=022");
            exit;
        }
    } else {
        // Ocorreu um erro ao salvar a primeira imagem
        header("Location: erro.php?code=021");
        exit;
    }
} else {
    // Não foram enviados todos os arquivos de imagem ou ocorreu um erro nos arquivos enviados
    header("Location: erro.php?code=03");
    exit;
}
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Excursão</title>
	<link rel="stylesheet" type="text/css" href="../css/cads-styles.css" media="screen" />
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
			<li><a href="../userdashboard.php">> Minhas Excursões</a></li>
			<li>> Cadastrar Excursão</li>     
		  </ul>
		</header>
		<div class="titulo">
			<h3>CADASTRAR NOVA EXCURSÃO</h3>
		</div>
		<div class="textCard">	
			<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
				<div class="cardForm">
				<label for="titulo">*Título:</label>
					<input type="text" id="titulo" name="titulo" placeholder="Brás Bate-Volta" maxlength="60" required>

					<label for="telefone">*Telefone para contato:</label>
					<input type="text" id="telefone" placeholder="Digite um número de telefone válido (apenas números)" name="telefone" maxlength="15" oninput="formatarTelefone(this)" required>
					<script>
						function formatarTelefone(input) {
							var value = input.value;
							value = value.replace(/\D/g, ''); // Remove todos os caracteres não numéricos
							value = value.replace(/(\d{2})(\d)/, '($1) $2'); // Adiciona parênteses entre os dois primeiros dígitos
							value = value.replace(/(\d{5})(\d)/, '$1-$2'); // Adiciona hífen após os primeiros 5 dígitos
							input.value = value;
						}
					</script>	
					<label for="origem">*Origem:</label>
					<select type="text" id="origem" name="origem" required>
					<optgroup label = "Minas Gerais">
							<option value="São João Del Rei">São João Del Rei - MG</option>
							<option value="Barbacena">Barbacena - MG</option>
							<option value="Barroso">Barroso - MG</option>
							<option value="Lavras">Lavras - MG</option>
							<option value="Juiz de Fora">Juiz de Fora - MG</option>
							<option value="São Tomé das Letras">São Tomé das Letras - MG</option>
							<option value="Manhaçu">Manhaçu - MG</option>
							<option value="Muriaé">Muriaé - MG</option>
							<option value="Nazareno">Nazareno - MG</option>
							<option value="Rio Pomba">Rio Pomba - MG</option>
							<option value="Resende Costa">Resende Costa - MG</option>
							<option value="Santos Dumont">Santos Dumont - MG</option>
							<option value="Bom Sucesso">Bom Sucesso - MG</option>
							<option value="Cataguases">Cataguases - MG</option>
							<option value="Ubá">Ubá - MG</option>
							<option value="Viçosa">Viçosa - MG</option>
							<option value="Belo Horizonte">Belo Horizonte - MG</option>
						</optgroup>
						<optgroup label = "Espirito Santo">
							<option value="Guarapari">Guarapari - ES</option>
							<option value="Vitória">Vitória - ES</option>
						</optgroup>
						<optgroup label = "São Paulo">
							<option value="Aparecida do Norte">Aparecida do Norte - SP</option>
							<option value="Santos">Santos - SP</option>
							<option value="Guarujá">Guarujá - SP</option>
							<option value="São Paulo">São Paulo - SP</option>
						</optgroup>
						<optgroup label = "Rio de Janeiro">
							<option value="Cabo Frio">Cabo Frio - RJ</option>
							<option value="Rio de Janeiro">Rio de Janeiro - RJ</option>
							<option value="Petrópolis">Petrópolis - RJ</option>
							<option value="Paraty">Paraty - RJ</option>
						</optgroup>
					</select>					
					<label for="destino">*Destino:</label>
						<select type="text" id="destino" name="destino" required>
						<optgroup label = "Minas Gerais">
							<option value="São João Del Rei">São João Del Rei - MG</option>
							<option value="Barbacena">Barbacena - MG</option>
							<option value="Barroso">Barroso - MG</option>
							<option value="Lavras">Lavras - MG</option>
							<option value="Juiz de Fora">Juiz de Fora - MG</option>
							<option value="São Tomé das Letras">São Tomé das Letras - MG</option>
							<option value="Manhaçu">Manhaçu - MG</option>
							<option value="Muriaé">Muriaé - MG</option>
							<option value="Nazareno">Nazareno - MG</option>
							<option value="Rio Pomba">Rio Pomba - MG</option>
							<option value="Resende Costa">Resende Costa - MG</option>
							<option value="Santos Dumont">Santos Dumont - MG</option>
							<option value="Bom Sucesso">Bom Sucesso - MG</option>
							<option value="Cataguases">Cataguases - MG</option>
							<option value="Ubá">Ubá - MG</option>
							<option value="Viçosa">Viçosa - MG</option>
							<option value="Belo Horizonte">Belo Horizonte - MG</option>
						</optgroup>
						<optgroup label = "Espirito Santo">
							<option value="Guarapari">Guarapari - ES</option>
							<option value="Vitória">Vitória - ES</option>
						</optgroup>
						<optgroup label = "São Paulo">
							<option value="Aparecida do Norte">Aparecida do Norte - SP</option>
							<option value="Santos">Santos - SP</option>
							<option value="Guarujá">Guarujá - SP</option>
							<option value="São Paulo">São Paulo - SP</option>
						</optgroup>
						<optgroup label = "Rio de Janeiro">
							<option value="Cabo Frio">Cabo Frio - RJ</option>
							<option value="Rio de Janeiro">Rio de Janeiro - RJ</option>
							<option value="Petrópolis">Petrópolis - RJ</option>
							<option value="Paraty">Paraty - RJ</option>
						</optgroup>
					</select>

					<label for="valor">*Valor:</label>
					<input type="number" id="valor" placeholder="R$: 100,00" name="valor" required>

					<label for="vagas">*Vagas Disponíveis:</label>
					<input type="number" id="vagas" placeholder="48" name="vagas" required>

					<label for="dataPartida">*Data de Partida:</label>
					<input type="date" id="dataPartida" name="dataPartida" value="<?php echo $data; ?>" min="<?php echo date('Y-m-d'); ?>" oninput="formatarData(this)" required>

					<label for="hpartida">*Hora de Partida:</label>
					<input type="time" id="hpartida" name="hpartida" value="<?php echo $hora; ?>" required>

					<label for="pontoEmbarque">*Ponto de Embarque:</label>
					<input type="text" id="pontoEmbarque" name="pontoEmbarque" placeholder="Em frente a escola, na rua tal" maxlength="120" required>

					<label for="dataChegada">*Data de Chegada:</label>
					<input type="date" id="dataChegada" name="dataChegada" min="<?php echo date('Y-m-d'); ?>" required>

					<label for="hchegada">*Hora de Chegada:</label>
					<input type="time" id="hchegada" name="hchegada" required>

					<label for="pontoDesembarque">*Ponto de Desembarque:</label>
					<input type="text" id="pontoDesembarque" name="pontoDesembarque" placeholder="Na parada de ônibus da 25 de março." maxlength="120" required>

					<label for="dataRetorno">*Data de Retorno:</label>
					<input type="date" id="dataRetorno" name="dataRetorno" min="<?php echo date('Y-m-d'); ?>" required>

					<label for="horaVolta">*Hora de Retorno:</label>
					<input type="time" id="horaVolta" name="horaVolta" required>
					
				</div>
				<div class="cardForm-img">
				<label for="descricao">*Descrição:(máximo 255 caracteres)</label>
				<textarea name="descricao" id="descricao" maxlength="255" placeholder="Fale sobre sua excursão, ônibus etc..." required></textarea>
					
					<label for="image1">*Foto do anúncio:</label>
					<img id="preview1" src="../imgs/userBusPics/default-bus.png">
					<input type="file" id="cover" name="image1" accept="image/*" onchange="previewImage1(event)" required>
					<label for="image2">*Foto do ônibus:</label>
					<img id="preview2" src="../imgs/userBusPics/default-bus.png">
					<input type="file" id="imageBusEx" name="image2" accept="image/*" onchange="previewImage2(event)" required>
					<label for="image3">*Foto do interior do ônibus:</label>
					<img id="preview3" src="../imgs/userBusPics/default-bus.png">
					<input type="file" id="imageBusInt" name="image3" accept="image/*" onchange="previewImage3(event)" required>
					<p>* Campos Obrigatórios</p>		
				</div>		
				<input class="cadastrar" type="submit" value="Cadastrar" onclick="return confirmarCadastro();">		
			</form>				
		</div>  
	</main>	
	<script>
		function previewImage1(event) {
			var preview = document.getElementById('preview1');
			preview.src = URL.createObjectURL(event.target.files[0]);
		}
		function previewImage2(event) {
			var preview = document.getElementById('preview2');
			preview.src = URL.createObjectURL(event.target.files[0]);
		}
		function previewImage3(event) {
			var preview = document.getElementById('preview3');
			preview.src = URL.createObjectURL(event.target.files[0]);
		}
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
