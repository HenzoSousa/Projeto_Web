function validarFormulario() {
    let inputs = document.querySelectorAll(".form-control[required]");
    for (let input of inputs) {
        if (input.value.trim() === "") {
            alert("Por favor, preencha todos os campos obrigatórios!");
            input.focus();
            return false;
        }
    }
    return true;
}