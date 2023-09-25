<?php
session_start();
include_once("connection.php");

    //Recupera o CPF pela URL
    $CPF_exclusao = $_POST['CPF_EXCLUIR'];
    $CPF_exclusao = str_replace(['.', '-'], '', $CPF_exclusao);
    $respostaSecretaFormulario = $_POST['SecretAnswer']; // Coletou pelo POST a resposta secreta para usar na verificação de submissão.
    $novo_email = $_POST['confirma-novo-email']; // Coletou pelo o POST o novo e-mail do usuário.
    $nome = $_POST['nome']; // Coletou pelo o POST o novo nome do usuário.
    $senhaNova = $_POST['confirma-senha'];
    $coringa = $_POST['coringa'];

    // Consulte as informações de segurança e dados das excursões do usuário no banco de dados
    $query = "SELECT u.senha, u.respostaSecreta, u.email, e.ID
            FROM usuario u
            LEFT JOIN excursao e ON u.CPF = e.cpf_excurseiro
            WHERE u.CPF = ?";

    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_bind_param($stmt, "s", $CPF_exclusao);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        $rows = mysqli_fetch_assoc($result);

    
            $respostaSecreta = $rows['respostaSecreta'];
            $senha_BD = $rows['senha'];
            $email_BD = $rows['email'];

            if (array_key_exists('ID', $rows)) {
                $id = $rows['ID'];
            } else {
                $id = null;
            }
            // Comparação das senhas para não permitir que o usuário utilize uma senha antiga caso deseje alterar.
            if (empty($senhaNova)){
                
                $senhaAtual = $coringa;

            } else {

                $senhaAtual = $senhaNova;
            }
            if (empty($novo_email)) {
        
                //email certifica recebendo o email do BD em caso de não preenchemento.
                $emailAtual = $email_BD;
            
            
            } else { 
                    
                    // Verifica se o novo email pertence a outro usuário no BD
                    $queryEmail = "SELECT email FROM usuario WHERE email = ?";
                    $stmtEmail = mysqli_prepare($conexao, $queryEmail);
                    mysqli_stmt_bind_param($stmtEmail, "s", $novo_email);
                    mysqli_stmt_execute($stmtEmail);
                    $resultEmail = mysqli_stmt_get_result($stmtEmail);
        
                    if ($resultEmail) {
                        $rows_email = mysqli_fetch_array($resultEmail);
        
                        if ($rows_email) {
                            // Email já está em uso
                            header("Location: erro.php?code=05");
                            exit;
                        } else {
        
                            // Atualiza o email recebido pelo POST para a SESSÃO
                            $emailAtual = $_POST['confirma-novo-email'];
        
                        }
                    }
                }//Email Verificado.

                // Verifica se algum arquivo foi carregado
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

                    // Arquivo carregado
                    $file = $_FILES['image'];

                    // Obter o caminho temporário do arquivo
                    $tempFilePath = $file['tmp_name'];

                    $fileExtension = ".jpg"; // Extensão de arquivo permitida
                    
                    //Defininir caminhos para exclusão das imagens vinculadas ao cpf do usuário.
                    $targetDir_user = '../imgs/users/';
                    $targetDir_bus = "../imgs/userBusPics/";

                    // Definir o caminho completo do arquivo usando o CPF
                    $imagePath = $targetDir_user . $CPF_exclusao . $fileExtension;
                    
                    if ($respostaSecretaFormulario === $respostaSecreta) {
                        // Resposta secreta correta, executar ação de atualização ou exclusão do cadastro
 /************************************************************************************************************************************/     
                        // Usuário Solicitou Exclusão da Conta
                        if (isset($_POST['excluir'])) {
                            // Verificar se o arquivo de imagem existe
                            if (file_exists($imagePath)) {
                                // Remover as imagens correspondentes da pasta de imagens
                                unlink($imagePath);
                            }
                            // Remover os arquivos de imagem do diretório targetDir2
                            $fileExtensions = ['jpg', 'jpeg', 'png']; // Lista de extensões de arquivo permitidas

                            if($id != NULL){

                                foreach ($fileExtensions as $extension) {
                                    $imagePath = $targetDir_bus . $id . '_1.' . $extension;
                                    if (file_exists($imagePath)) {
                                        unlink($imagePath);
                                    }
                    
                                    $imagePath = $targetDir_bus . $id . '_2.' . $extension;
                                    if (file_exists($imagePath)) {
                                        unlink($imagePath);
                                    }
                    
                                    $imagePath = $targetDir_bus . $id . '_3.' . $extension;
                                    if (file_exists($imagePath)) {
                                        unlink($imagePath);
                                    }
                                }
                            }
                            // Remover Usuário do banco de dados
                            $queryRemoverUsuario = "DELETE FROM usuario WHERE CPF = ?";
                            $stmtRemoverUsuario = mysqli_prepare($conexao, $queryRemoverUsuario);
                            mysqli_stmt_bind_param($stmtRemoverUsuario, "s", $CPF_exclusao);
                            $resultRemoverUsuario = mysqli_stmt_execute($stmtRemoverUsuario);

                            if ($resultRemoverUsuario) {
                                // Usuário removido com sucesso
                                session_destroy();
                                header("Location: adm_gerenciar_users.php?");
                                exit;
                            } else {
                                // Trate o erro ao remover o usuário
                                header("Location: erro.php?code=20");
                                exit;
                            }
                } else {
                    // Atualizar usuário
                    // Mova o arquivo de imagem carregado para o diretório de destino
                    if (move_uploaded_file($tempFilePath, $imagePath)) {
                        // Atualize os dados do usuário no banco de dados
                        $queryAtualizaUsuario = "UPDATE usuario SET nome = ?, senha = ?, email = ? WHERE CPF = ?";
                        $stmtAtualizaUsuario = mysqli_prepare($conexao, $queryAtualizaUsuario);
                        mysqli_stmt_bind_param($stmtAtualizaUsuario, "ssss", $nome, $senhaAtual, $emailAtual, $CPF_exclusao);
                        $resultAtualizaUsuario = mysqli_stmt_execute($stmtAtualizaUsuario);

                        if ($resultAtualizaUsuario) {
                            // Usuário atualizado com sucesso
                            $queryAtualizaSessao = "UPDATE usuario SET nome = ? WHERE CPF = ?";
                            $stmtAtualizaSessao = mysqli_prepare($conexao, $queryAtualizaSessao);
                            mysqli_stmt_bind_param($stmtAtualizaSessao, "ss", $nome, $CPF_exclusao);
                            mysqli_stmt_execute($stmtAtualizaSessao);
                            header("Location: adm_gerenciar_users.php");
                            exit;
                        } else {
                            // Trate o erro ao atualizar o usuário
                            header("Location: erro.php?code=21");
                            exit;
                        }
                    } else {
                        // Trate o erro ao mover o arquivo de imagem
                        header("Location: erro.php?code=02");
                        exit;
                    }
                }
            } else {
                // Resposta secreta incorreta, redirecionar o usuário para uma página de erro ou exibir uma mensagem de erro.
                header("Location: erro.php?code=09");
                exit;
            }
        } else {
            
            // imagem não foi carregada, apenas atualize ou exclua o cadastro
                    
            $fileExtension = ".jpg"; // Extensão de arquivo permitida
                    
            //Defininir caminhos para exclusão das imagens vinculadas ao cpf do usuário.
            $targetDir_user = '../imgs/users/';
            $targetDir_bus = "../imgs/userBusPics/";

            // Definir o caminho completo do arquivo usando o CPF
            $imagePath = $targetDir_user . $CPF_exclusao . $fileExtension;

            if ($respostaSecretaFormulario === $respostaSecreta) {

                // Resposta secreta correta, executar ação de atualização ou exclusão do cadastro

                if (isset($_POST['excluir'])) {
/************************************************************************************************************************************/     
                // Usuário Solicitou Exclusão da Conta  

                    // Verificar se o arquivo de imagem existe
                    if (file_exists($imagePath)) {
                        // Remover as imagens correspondentes da pasta de imagens
                        unlink($imagePath);
                    }

                    // Remover os arquivos de imagem do diretório targetDir2
                    $fileExtensions = ['jpg', 'jpeg', 'png']; // Lista de extensões de arquivo permitidas

                    if($id != NULL){
                        
                        foreach ($fileExtensions as $extension) {
                            $imagePath = $targetDir_bus . $id . '_1.' . $extension;
                            if (file_exists($imagePath)) {
                                unlink($imagePath);
                            }
            
                            $imagePath = $targetDir_bus . $id . '_2.' . $extension;
                            if (file_exists($imagePath)) {
                                unlink($imagePath);
                            }
            
                            $imagePath = $targetDir_bus . $id . '_3.' . $extension;
                            if (file_exists($imagePath)) {
                                unlink($imagePath);
                            }
                        }
                    }
                    // Remover Usuário do banco de dados
                    $queryRemoverUsuario = "DELETE FROM usuario WHERE CPF = ?";
                    $stmtRemoverUsuario = mysqli_prepare($conexao, $queryRemoverUsuario);
                    mysqli_stmt_bind_param($stmtRemoverUsuario, "s", $CPF_exclusao);
                    $resultRemoverUsuario = mysqli_stmt_execute($stmtRemoverUsuario);

                    if ($resultRemoverUsuario) {
                        // Usuário removido com sucesso
                        header("Location: adm_gerenciar_users.php?");
                        exit;
                    } else {
                        // Trate o erro ao remover o usuário
                        header("Location: erro.php?code=20");
                        exit;
                    }
                } else {
                    // Atualizar usuário
                    // Atualize os dados do usuário no banco de dados
                    $queryAtualizaUsuario = "UPDATE usuario SET nome = ?, senha = ?, email = ? WHERE CPF = ?";
                    $stmtAtualizaUsuario = mysqli_prepare($conexao, $queryAtualizaUsuario);
                    mysqli_stmt_bind_param($stmtAtualizaUsuario, "ssss", $nome, $senhaAtual, $emailAtual, $CPF_exclusao);
                    $resultAtualizaUsuario = mysqli_stmt_execute($stmtAtualizaUsuario);
                    

                    if ($resultAtualizaUsuario) {
                        // Usuário atualizado com sucesso
                        $queryAtualizaSessao = "UPDATE usuario SET nome = ? WHERE CPF = ?";
                        $stmtAtualizaSessao = mysqli_prepare($conexao, $queryAtualizaSessao);
                        mysqli_stmt_bind_param($stmtAtualizaSessao, "ss", $nome, $CPF_exclusao);
                        mysqli_stmt_execute($stmtAtualizaSessao);
                   
                        header("Location: adm_gerenciar_users.php");
                        exit;
                    } else {
                        // Trate o erro ao atualizar o usuário
                        header("Location: erro.php?code=21");
                        exit;
                    }
                }
            } else {
                // Reposta Secreta Incorreta
                header("Location: erro.php?code=09");
                exit;
            }
        }
    }else{
        echo $semconexao;
    }   
?>
