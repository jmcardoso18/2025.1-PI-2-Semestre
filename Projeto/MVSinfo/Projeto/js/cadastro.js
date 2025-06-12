document.getElementById("cadastroForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const tipo = document.querySelector('input[name="tipo"]:checked').value;
    const empresa = document.getElementById("empresa").value;
    const email = document.getElementById("email").value;
    const mensagem = document.getElementById("mensagem");

    if (tipo === "fornecedor") {
        mensagem.textContent = `Fornecedor ${empresa} cadastrado! Um e-mail de validação foi enviado para ${email}.`;
    } else {
        mensagem.textContent = `Cliente ${empresa} cadastrado com sucesso!`;
    }

    this.reset();
});
