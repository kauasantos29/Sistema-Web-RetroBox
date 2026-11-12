const form = document.getElementById('form-cliente');
const telefone = document.getElementById('telefone');
const erro = document.getElementById('erro-js');

// formata o telefone enquanto digita: (38) 99999-9999
telefone.addEventListener('input', function () {
    let numeros = telefone.value.replace(/\D/g, '').slice(0, 11);

    if (numeros.length > 6) {
        telefone.value = '(' + numeros.slice(0, 2) + ') ' + numeros.slice(2, 7) + '-' + numeros.slice(7);
    } else if (numeros.length > 2) {
        telefone.value = '(' + numeros.slice(0, 2) + ') ' + numeros.slice(2);
    } else {
        telefone.value = numeros;
    }
});

form.addEventListener('submit', function (e) {
    const senha = document.getElementById('senha').value;
    const confirmar = document.getElementById('confirmar_senha').value;
    const numeros = telefone.value.replace(/\D/g, '');
    let mensagem = '';

    if (numeros.length < 10) {
        mensagem = 'Digite um telefone válido com DDD.';
    } else if (senha !== '' && senha.length < 6) {
        mensagem = 'A senha precisa ter pelo menos 6 caracteres.';
    } else if (senha !== confirmar) {
        mensagem = 'As senhas não são iguais.';
    }

    if (mensagem) {
        e.preventDefault();
        erro.textContent = mensagem;
        erro.hidden = false;
    }
});
