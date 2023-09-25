<?php
session_start();
include_once("connection.php");

if (!isset($_SESSION['userEmail'])) {
    // Redirecionar o usuário para a página de login se não estiver logado
    header("Location: ../login.php");
    exit();

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

  // Verifica se o ID da excursão foi fornecido na URL
  if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Carrega os dados da excursão
    $query = "SELECT * FROM excursao WHERE ID = ?";
    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_bind_param($stmt, 's', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $rows = mysqli_fetch_array($result);

    $title = $rows['titulo'];
    $descricao = $rows['descricao'];
    $origem = $rows['cidadeOrigem'];
    $destino = $rows['cidadeDestino'];
    $dataPartida = $rows['dataIda'];
    $pontoEmbarque = $rows['pontoEmbarque'];
    $pontoDesembarque = $rows['pontoDesembarque'];
    $horaPartida = $rows['horaPartida'];
    $dataChegada = $rows['dataChegada'];
    $horaChegada = $rows['horaChegada'];
    $dataRetorno = $rows['dataVolta'];
    $horaRetorno = $rows['horaVolta'];
    $telefone = $rows['telefone'];
    $preco = $rows['preco'];
    $vagas = $rows['vagas'];

      // Formata a saída do telefone para o padrão (DD) NNNNN-NNNN
      $formattedTelefone = preg_replace('/^(\d{2})(\d{5})(\d{4})$/', '($1) $2-$3', $telefone);

      // Diretório onde as imagens estão armazenadas
      $loadtargetDir = '../imgs/userbusPics/';

      // Carrega as imagens salvas
      $targetPath1 = $id . '_1.*';
      $targetPath2 = $id . '_2.*';
      $targetPath3 = $id . '_3.*';

      // Obter a lista de arquivos que correspondem ao padrão de nome
      $arquivos[0] = glob($loadtargetDir . $targetPath1);
      $arquivos[1] = glob($loadtargetDir . $targetPath2);
      $arquivos[2] = glob($loadtargetDir . $targetPath3);

      // Verificar se foi encontrado algum arquivo
      if (count($arquivos[0]) > 0) {
          // Encontrou o arquivo de imagem correspondente ao ID, usar esse arquivo para exibir a imagem
          $targetPath1 = $arquivos[0][0];
      } else {
          // Não foi encontrado um arquivo de imagem correspondente ao ID, usar uma imagem padrão
          $targetPath1 = $loadtargetDir . 'default-bus.png';
      }

      if (count($arquivos[1]) > 0) {
          $targetPath2 = $arquivos[1][0];
      } else {
          $targetPath2 = $loadtargetDir . 'default-bus.png';
      }

      if (count($arquivos[2]) > 0) {
          $targetPath3 = $arquivos[2][0];
      } else {
          $targetPath3 = $loadtargetDir . 'default-bus.png';
      }

  } else {
      // ID da excursão inválido ou não pode ser carregado.
      header("Location: erro.php?code=11");
      exit;
  }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="UTF-8">
    <title>Editar Excursão</title>
	<link rel="stylesheet" type="text/css" href="../css/cads-styles.css" media="screen" />
	<link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
	<script>
      function confirmarExclusao(id) {
        if (confirm("Tem certeza que deseja excluir esta excursão? Esta ação não pode ser desfeita.")) {
            window.location.href = "remover_excursao.php?id=" + id;
        }
      }
      function inicializarBotoesExcluir() {
        // Obtém todos os elementos com a classe "excluir"
        var botoesExcluir = document.getElementsByClassName("excluir");

        // Percorre todos os botões de remoção e adiciona o evento de clique a cada um
        for (var i = 0; i < botoesExcluir.length; i++) {
          var botao = botoesExcluir[i];
          botao.addEventListener("click", function() {
            var id = this.getAttribute("data-id");
            confirmarExclusao(id);
          });
        }
      }
      // Chamada da função após o carregamento da página
      window.addEventListener("DOMContentLoaded", inicializarBotoesExcluir);
	  function confirmarAtualizacao() {
        var confirmado = confirm("Tem certeza que deseja atualizar Excursão?");
        if (confirmado) {
            console.log("Excursão Atualizada com Sucesso!");
        }
        return confirmado;
    }
    </script>
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
			<li><a href="../userdashboard.php">> Meu Painel</a></li>
			<li><a href="tabela_excursoes.php">> Minhas Excursões</a></li>
			<li>> Gerenciar Excursão</li>     
		  </ul>
		</header>
		<div class="titulo">
				<h3>EDITAR EXCURSÃO</h3>
		</div>
		<div class="textCard">
			<div class="form-container">
                <form method="post" action="processa_excursao.php?id=<?php echo $id; ?>" enctype="multipart/form-data">
					<div class="cardForm">
						<label for="titulo">Titulo:</label>
						<input type="text" id="titulo" name="titulo" value="<?php echo $title ?>" maxlength="40" required>
						
						<label for="telefone">Telefone para contato:</label>
						<input type="text" id="telefone" placeholder="Digite um número de telefone válido (apenas números)" name="telefone" maxlength="15" oninput="formatarTelefone(this)" value="<?php echo $formattedTelefone ?>" required>
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
							<select id="origem" name="origem" required>
							<optgroup label="Minas Gerais">
								<option value="São João Del Rei" <?php if ($origem === 'São João Del Rei') echo 'selected'; ?>>São João Del Rei - MG</option>
								<option value="Barbacena" <?php if ($origem === 'Barbacena') echo 'selected'; ?>>Barbacena - MG</option>
								<option value="Barroso" <?php if ($origem === 'Barroso') echo 'selected'; ?>>Barroso - MG</option>
								<option value="Lavras" <?php if ($origem === 'Lavras') echo 'selected'; ?>>Lavras - MG</option>
								<option value="Juiz de Fora" <?php if ($origem === 'Juiz de Fora') echo 'selected'; ?>>Juiz de Fora - MG</option>
								<option value="São Tomé das Letras" <?php if ($origem === 'São Tomé das Letras') echo 'selected'; ?>>São Tomé das Letras - MG</option>
								<option value="Manhaçu" <?php if ($origem === 'Manhaçu') echo 'selected'; ?>>Manhaçu - MG</option>
								<option value="Muriaé" <?php if ($origem === 'Muriaé') echo 'selected'; ?>>Muriaé - MG</option>
								<option value="Nazareno" <?php if ($origem === 'Nazareno') echo 'selected'; ?>>Nazareno - MG</option>
								<option value="Rio Pomba" <?php if ($origem === 'Rio Pomba') echo 'selected'; ?>>Rio Pomba - MG</option>
								<option value="Resende Costa" <?php if ($origem === 'Resende Costa') echo 'selected'; ?>>Resende Costa - MG</option>
								<option value="Santos Dumont" <?php if ($origem === 'Santos Dumont') echo 'selected'; ?>>Santos Dumont - MG</option>
								<option value="Bom Sucesso" <?php if ($origem === 'Bom Sucesso') echo 'selected'; ?>>Bom Sucesso - MG</option>
								<option value="Cataguases" <?php if ($origem === 'Cataguases') echo 'selected'; ?>>Cataguases - MG</option>
								<option value="Ubá" <?php if ($origem === 'Ubá') echo 'selected'; ?>>Ubá - MG</option>
								<option value="Viçosa" <?php if ($origem === 'Viçosa') echo 'selected'; ?>>Viçosa - MG</option>
								<option value="Belo Horizonte" <?php if ($origem === 'Belo Horizonte') echo 'selected'; ?>>Belo Horizonte - MG</option>
							</optgroup>
							<optgroup label="Espirito Santo">
								<option value="Guarapari" <?php if ($origem === 'Guarapari') echo 'selected'; ?>>Guarapari - ES</option>
								<option value="Vitória" <?php if ($origem === 'Vitória') echo 'selected'; ?>>Vitória - ES</option>
							</optgroup>
							<optgroup label="São Paulo">
								<option value="Aparecida do Norte" <?php if ($origem === 'Aparecida do Norte') echo 'selected'; ?>>Aparecida do Norte - SP</option>
								<option value="Santos" <?php if ($origem === 'Santos') echo 'selected'; ?>>Santos - SP</option>
								<option value="Guarujá" <?php if ($origem === 'Guarujá') echo 'selected'; ?>>Guarujá - SP</option>
								<option value="São Paulo" <?php if ($origem === 'São Paulo') echo 'selected'; ?>>São Paulo - SP</option>
							</optgroup>
							<optgroup label="Rio de Janeiro">
								<option value="Cabo Frio" <?php if ($origem === 'Cabo Frio') echo 'selected'; ?>>Cabo Frio - RJ</option>
								<option value="Rio de Janeiro" <?php if ($origem === 'Rio de Janeiro') echo 'selected'; ?>>Rio de Janeiro - RJ</option>
								<option value="Petrópolis" <?php if ($origem === 'Petrópolis') echo 'selected'; ?>>Petrópolis - RJ</option>
								<option value="Paraty" <?php if ($origem === 'Paraty') echo 'selected'; ?>>Paraty - RJ</option>
							</optgroup>
							</select>
							<label for="destino">*Destino:</label>
							<select type="text" id="destino" name="destino" required>
							<optgroup label="Minas Gerais">
								<option value="São João Del Rei" <?php if ($destino === 'São João Del Rei') echo 'selected'; ?>>São João Del Rei - MG</option>
								<option value="Barbacena" <?php if ($destino === 'Barbacena') echo 'selected'; ?>>Barbacena - MG</option>
								<option value="Barroso" <?php if ($destino === 'Barroso') echo 'selected'; ?>>Barroso - MG</option>
								<option value="Lavras" <?php if ($destino === 'Lavras') echo 'selected'; ?>>Lavras - MG</option>
								<option value="Juiz de Fora" <?php if ($destino === 'Juiz de Fora') echo 'selected'; ?>>Juiz de Fora - MG</option>
								<option value="São Tomé das Letras" <?php if ($origem === 'São Tomé das Letras') echo 'selected'; ?>>São Tomé das Letras - MG</option>
								<option value="Manhaçu" <?php if ($destino === 'Manhaçu') echo 'selected'; ?>>Manhaçu - MG</option>
								<option value="Muriaé" <?php if ($destino === 'Muriaé') echo 'selected'; ?>>Muriaé - MG</option>
								<option value="Nazareno" <?php if ($destino === 'Nazareno') echo 'selected'; ?>>Nazareno - MG</option>
								<option value="Rio Pomba" <?php if ($destino === 'Rio Pomba') echo 'selected'; ?>>Rio Pomba - MG</option>
								<option value="Resende Costa" <?php if ($destino === 'Resende Costa') echo 'selected'; ?>>Resende Costa - MG</option>
								<option value="Santos Dumont" <?php if ($destino === 'Santos Dumont') echo 'selected'; ?>>Santos Dumont - MG</option>
								<option value="Bom Sucesso" <?php if ($destino === 'Bom Sucesso') echo 'selected'; ?>>Bom Sucesso - MG</option>
								<option value="Cataguases" <?php if ($destino === 'Cataguases') echo 'selected'; ?>>Cataguases - MG</option>
								<option value="Ubá" <?php if ($destino === 'Ubá') echo 'selected'; ?>>Ubá - MG</option>
								<option value="Viçosa" <?php if ($destino === 'Viçosa') echo 'selected'; ?>>Viçosa - MG</option>
								<option value="Belo Horizonte" <?php if ($destino === 'Belo Horizonte') echo 'selected'; ?>>Belo Horizonte - MG</option>
							</optgroup>
							<optgroup label="Espirito Santo">
								<option value="Guarapari" <?php if ($destino === 'Guarapari') echo 'selected'; ?>>Guarapari - ES</option>
								<option value="Vitória" <?php if ($destino === 'Vitória') echo 'selected'; ?>>Vitória - ES</option>
							</optgroup>
							<optgroup label="São Paulo">
								<option value="Aparecida do Norte" <?php if ($destino === 'Aparecida do Norte') echo 'selected'; ?>>Aparecida do Norte - SP</option>
								<option value="Santos" <?php if ($origem === 'Santos') echo 'selected'; ?>>Santos - SP</option>
								<option value="Guarujá" <?php if ($origem === 'Guarujá') echo 'selected'; ?>>Guarujá - SP</option>
								<option value="São Paulo" <?php if ($destino === 'São Paulo') echo 'selected'; ?>>São Paulo - SP</option>
							</optgroup>
							<optgroup label="Rio de Janeiro">
								<option value="Cabo Frio" <?php if ($destino === 'Cabo Frio') echo 'selected'; ?>>Cabo Frio - RJ</option>
								<option value="Rio de Janeiro" <?php if ($destino === 'Rio de Janeiro') echo 'selected'; ?>>Rio de Janeiro - RJ</option>
								<option value="Petrópolis" <?php if ($destino === 'Petrópolis') echo 'selected'; ?>>Petrópolis - RJ</option>
								<option value="Paraty" <?php if ($origem === 'Paraty') echo 'selected'; ?>>Paraty - RJ</option>
							</optgroup>
						</select>
                 
						<label for="preco">*Valor:</label>
						<input type="number" id="preco" placeholder="R$: 100,00" name="preco" value="<?php echo $preco ?>" required>
						
						<label for="vagas">*Vagas Disponíveis</label>
						<input type="number" id="vagas" placeholder="48" name="vagas" value="<?php echo $vagas ?>" required>
						
						<label for="dataIda">*Data de Partida:</label>
						<input type="date" id="dataIda" name="dataIda" value="<?php echo $dataPartida ?>" required>
						
						<label for="horaPartida">*Hora de Partida:</label>
						<input type="time" id="horaPartida" name="horaPartida" value="<?php echo $horaPartida ?>" required>

                        <label for="pontoEmbarque">*Ponto de Embarque:</label>
						<input type="text" id="pontoEmbarque" name="pontoEmbarque" maxlength="120" placeholder="Em frente a escola, na rua tal" value="<?php echo $pontoEmbarque ?>"required>
						
						<label for="dataChegada">*Data de chegada:</label>
						<input type="date" id="dataChegada" name="dataChegada" value="<?php echo $dataChegada ?>" required>
						
						<label for="horaChegada">*Hora de Chegada:</label>
						<input type="time" id="horaChegada" name="horaChegada" value="<?php echo $horaChegada ?>" required>

						<label for="pontoDesembarque">*Ponto de Desembarque:</label>
						<input type="text" id="pontoDesembarque" name="pontoDesembarque" maxlength="120" placeholder="Na parada de ônibus da 25 de março."value="<?php echo $pontoDesembarque ?>"required>

						<label for="dataVolta">*Data de Retorno:</label>
						<input type="date" id="dataVolta" name="dataVolta" value="<?php echo $dataRetorno ?>" required>

						<label for="horaVolta">*Hora de Retorno:</label>
						<input type="time" id="horaVolta" name="horaVolta" value="<?php echo $horaRetorno ?>" required>
						
					</div>
					<div class="cardForm-img">
					<label for="descricao">Descrição:(máximo 255 caracteres)</label>
					<textarea name="descricao" id="descricao" maxlength="255" required><?php echo $descricao; ?></textarea>
                    <label for="cover">Foto da capa:</label>
                        <img id="preview1" src="<?php echo $targetPath1; ?>">
                        <input type="file" id="cover" name="image1" accept="image/*" onchange="previewImage1(event)">

                        <label for="imageBusEx">Foto do ônibus:</label>
                        <img id="preview2" src="<?php echo $targetPath2; ?>">
                        <input type="file" id="imageBusEx" name="image2" accept="image/*" onchange="previewImage2(event)">

                        <label for="imageBusInt">Foto do interior do ônibus:</label>
                        <img id="preview3" src="<?php echo $targetPath3; ?>">
                        <input type="file" id="imageBusInt" name="image3" accept="image/*" onchange="previewImage3(event)">
					</div>
					<h2><?php echo "ID da Excursão: ".$id?></h2>
					<button class="cadastrar" type="submit" name="atualizar" onclick="return confirmarAtualizacao();">Atualizar</button>
					<input id="excluir" type='button' value="Excluir Excursão" class='excluir' data-id='<?php echo $id; ?>'>
				</form>
			</div>
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