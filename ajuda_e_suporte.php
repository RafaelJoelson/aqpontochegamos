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
    <h1>Ajuda e Suporte</h1>
      <div class="textCard">
        <div class="carregaTutoriais">
            <h1>Manual do Site</h1>
                <h2>Como cadastrar</h2>
                  <p>1.Para cadastrar você deve clicar no botão cadastrar, que direcionará você para uma página onde você aceitar os termos de uso</p>
                  <img src="./imgs/Lon_Ca.png" width=""></img>
                 
                   <p> Aqui você vai ler o termo de uso quando chegar na rolagem la em baixo  você deve aceita para continuar o cadastro.</p>
                   <img src="./imgs/Termo.png" width=""></img>
                 
                   <p>Aqui você deve aperta o quadradinho e no aceita prosseguir depois te direcionar para uma outra página onde você preenchera com seus dados.</P>
                   <img src="./imgs/Termo2.png" width=""/>

                   <p> • Nessa página você pode colocar uma foto sua , onde você pode escolher o tamanho que ela fique. Depois você deve preencher os dados que se pede abaixo da foto.
                        <li> 1. Nome completo;</li>
                        <li>2. CPF (você deve colocar somente só os números);</li>
                        <li>3. E-mail;</li>
                        <li>4. Você confirmara seu e-mail; </li></p>
                      <img src="./imgs/Cadastro.png" width=""/>
                
                    <p>   <li> 5. Você criara uma senha que tenha mais mínimo de 4 caracteres e máximo de 16 caracteres(onde deve haver letras maiúsculas e minúscula, números e caracteres especiais.); </li>
                        <li> 6. Você deve só confirmara sua senha;</li>
                        <li> 7. Escolher uma pergunta secreta e responder em baixo.</li>
                        <li> E por fim aperta cadastrar e você criou seu cadastro. E te direcionar para página do login</li></p>
                      <img src="./imgs/Cadastro2.png" width=""/>
                     <h2> Como fazer login></h2>    
                    <p>Para fazer login você deve clicar no botão login que direcionará você para uma outra página.</p>           
                    <img src="./imgs/Lon_Bus.png" width=""/>

                    <p> • Nessa página onde você deve preencher com seu e-mail e senha que você criou na página de cadastro.</p>
                    <img src="./imgs/Login.png" width=""/>

                    <p>No caso se esqueceu a senha, você devera aperta esqueceu senha que direcionará para página de verificação de segurança, você insira o e-mail ou número de cpf  e direcionar para outra página.</p>
                    <img src="./imgs/Esq_Senha.png" width=""/>
                     
                    <p>    <li> 1.Você vai preencher sua pergunta secreta do jeito que você preencheu quando você fez seu cadastro cada vez que você redefinir a senha e mesma pergunta que você preencheu anteriormente;</li>
                            <li>2.Você irá escolher uma outra pergunta secreta para responder;</li>
                            <li>3. Você vai responder a pergunta que você escolheu; </li>
                            <li> 4. Você vai criar uma senha que tenha mais mínimo de 4 caracteres e máximo de 16 caracteres(onde deve haver letras maiúsculas e minúscula, números e caracteres especiais.)
                            <li>Obs.: Senha não pode ser mesma que de antes;</li> 
                            <li>5. Você vai confirmar senha do jeito que você criou;</li>
                            <li> E apertar enviar que voltará para página do login de novo(Todos os campos são obrigatório);</li>
                          Agora você podera fazer login</p>
                    <img src="./imgs/For_Senha.png" width=""/>
                <h2>Meu Painel</h2>
                    <h2>Editar minhas informações</h2>
                      <p> Para editar suas informações só aperta em cima de Editar minhas informações que direcionara para outra página</p>
                      <img src="./imgs/Painel.png" width=""/>

                      <p>   <li> • 1.Aqui você vai atualizar sua foto;</li>
                            <li> 2. Atualizar seu e-mail;</li>
                            <li> 3. Confirmar a seu e-mail</li>
                            <li>4.Atualizar sua senha;</li>
                            <li>5.Confirmar sua senha;</li>
                            <li>6.Para você atualizar dados excluir você tem responder a pergunta secreta;</li>
                            <li>7. se você excluir aperta excluir se for atualizar aperta atualizar;</li>
                            <li>8. Se for atualizar ele voltar para página do painel;</li>
                            <li>9.Se foi excluir, se dados estiver certo excluirá com sucesso;</li> 
                            <li> 10. Se esqueceu a senha aperta saiba mais;</li></p>    
                      <img src="./imgs/Infor_Dados.png" width=""/>
                      <img src="./imgs/Infor_Dados2.png" width=""/>

                      <p>Para gerenciar as excursões você aperta em gerenciar excursões.</p>
                      <img src="./imgs/Ger_Excursao.png" width=""/>


                      <p>  Para cadastrar uma excursão aperta em nova excursão </p>
                      <img src="./imgs/Ger_Excursao2.png" width=""/>

                      <p><li>Você  vai colocar um titulo  na excursão ;</li>
                        <li> Colocar número de telefone de contato;</li>
                        <li>Colocar do origem( onde o ônibus vai sair);</li>
                        <li>Colocar para qual destino ele está indo(qual cidade);</li>
                        <li>Colocar o valor da excursão</li>
                        <li>Colocar tanto de vagas disponível;</li>
                        <li>Colocar a data de partida(dia que vai sair); </li>
                        <li>Colocar horário de partida(hora que vai sair);</li>
                        <li>Colocar o ponto de embarque (onde ônibus vai pegar a pessoas); </li>
                        <li>O dia que vai chegar ao destino;</li>
                        <li>Horário que vai chegar ao destino;</li>
                        <li>Colocar o ponto de desembarque (onde ônibus vai deixar a pessoas); </li>
                        <li>Colocar data que dia vai voltar;</li>
                        <li>Colocar horário que vai voltar;</li>
                        <li>Colocar descrição da excursão;</li>
                        <li>Colocar foto sobre anúncio da excursão;</li>
                        <li>Colocar foto do ônibus por fora;</li>
                        <li>Colocar foto do ônibus por dentro;</li>
                        <li>Todos campos são obrigatório.</li>
                        E aperta cadastrar. </p>
                      <img src="./imgs/Cadas_Exc.png" width=""/>
                      <img src="./imgs/Cadas_Exc2.png" width=""/>

                     <p>Para visualizar excursão só aperta visualizar</p>
                     <img src="./imgs/Visual.png" width=""/>

                     <p> Para gerenciar excursão só aperta em gerenciar</p>
                     <img src="./imgs/Gerenciar.png" width=""/>

                     <p><li>Você  vai atualiza o titulo  na excursão ;</li>
                        <li> Atualiza o número de telefone de contato;</li>
                        <li> Atualiza a  origem( onde o ônibus vai sair);</li>
                        <li> Atualiza para qual destino ele está indo(qual cidade);</li>
                        <li> Atualiza o valor da excursão</li>
                        <li> Atualiza o tanto de vagas disponível;</li>
                        <li> Atualiza  a data de partida(dia que vai sair); </li>
                        <li> Atualiza o horário de partida(hora que vai sair);</li>
                        <li> Atualiza o ponto de embarque (onde ônibus vai pegar a pessoas); </li>
                        <li> Atualiza o dia que vai chegar ao destino;</li>
                        <li> Atualiza o horário que vai chegar ao destino;</li>
                        <li> Atualiza o ponto de desembarque (onde ônibus vai deixar a pessoas); </li>
                        <li> Atualiza a data que dia vai voltar;</li>
                        <li> Atualiza o horário que vai voltar;</li>
                        <li> Atualiza a descrição da excursão;</li>
                        <li> Atualiza a foto sobre anúncio da excursão;</li>
                        <li> Atualiza a foto do ônibus por fora;</li>
                        <li> Atualiza a do ônibus por dentro;</li>
                        <li>Se for atualizar aperta atualizar, ou Se for excluir aperta excluir e aperta ok para confirmar a exclusão </li></p>
                      <img src="./imgs/Atual.png" width=""/>
                      <img src="./imgs/Atual2.png" width=""/>

                    <h2> Voltando para o meu painel</h2>
                      <img src="./imgs/M_p.png" width=""/>
                     
                    <p> Para alterar a senha ou pergunta secreta aperte alterar senha  ou pergunta secreta você vai ser voltado para página de verificação de segurança.</p>
                    <img src="./imgs/Perfil.png" width=""/>

                    <p>Acaso precisa de ajuda, aperte em suporte vai está lá uma auxilio para ajudar.</p>
                    <img src="./imgs/Suporte_Ca.png" width=""/>

                    <p>Caso o não encontre a ajuda ou a solução, no final da página tem  botão  de para tentar falar conosco aperte, que ira direcionar para falar conosco.</p>
                    <img src="./imgs/Fale_con.png" width=""/>

                    <p>Esse  protocolo acaso de você tenha  direcionado seu problema para nos você ira companhar como esta o andamento do seu prolema.
                    <li> Colocar seu  cpf  que já esteja cadastrado;</li>
                    <li> Colocar seu e-mail para entrarmos contato com voce ;</li>
                    <li> Confirmar seu email;</li>
                    <li> selecionar o tipo de problema;</li>
                    <li> colocar descrição  do problema</li>
                    <li>selecionar foto do problema que você quer resolva </li>
                    </p>
                    <img src="./imgs/chamado.png" width=""/>
                    <img src="./imgs/chamado2.png" width=""/>

                      <p> Para sair, aperte sair</p>
                      <img src="./imgs/Sair.png" width=""/>
        </div>
        <button class="voltar"onclick="history.back();">Voltar</button>
        <h1>Ainda precisando de ajuda? <a href="fale.php">Fale Conosco e Abra um Chamado.</a> </h1>
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
				<li><a href="excursoes.php">Excursões</a></li>
				<li><a href="./php/termos_de_uso.php">Cadastra-se</a></li>
				<li><a href="userdashboard.php">Painel do Usuário</a></li>
				<li><a href="fale.php">Fale Conosco</a></li>
				<li><a href="ajuda_e_suporte.php">Ajuda e Suporte</a></li>
				<li><a href="visualizar_termos_de_uso.php">Termos de Uso</a></li>		
			</ul>
		</div>
	</footer>
</html>
