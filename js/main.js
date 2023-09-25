function confirmLogout2() {
    console.log("Usuário será deslogado. Confirme para continuar.");
    if (confirm("Usuário será deslogado. Deseja continuar?")) {
    window.location.href = "./php/logout.php";
    }
}

function confirmLogout1() {
    console.log("Usuário será deslogado. Confirme para continuar.");
    if (confirm("Usuário será deslogado. Deseja continuar?")) {
    window.location.href = "searchenvaluation_password_or_secret_question.php";
    }
}
function limparCampo(campo) {
    document.getElementById(campo).value = '';
}
function selecionarCampo(campo) {
    if (campo === 'userEmail') {
        limparCampo('CPF');
    } else if (campo === 'CPF') {
        limparCampo('userEmail');
    }
}
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
function confirmarRedefinicao() {
    var confirmado = confirm("Tem certeza que deseja alterar suas credenciais?");
    if (confirmado) {
        console.log("Usuário Atualizado com Sucesso!");
    }
    return confirmado;
}
