document.getElementById("loginForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const tipo = document.querySelector('input[name="tipo"]:checked').value;
    const cnpj = document.getElementById("cnpj").value;
    const mensagem = document.getElementById("mensagem");

    mensagem.textContent = `Bem-vindo, ${tipo === "cliente" ? "cliente" : "fornecedor"} com CNPJ ${cnpj}!`;
    this.reset();
});
