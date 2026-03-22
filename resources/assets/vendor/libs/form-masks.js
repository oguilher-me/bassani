document.addEventListener('DOMContentLoaded', function() {
    // Máscara para CPF
    $('#cpf').mask('000.000.000-00');

    // Máscara para Telefone (com e sem DDD)
    $('#phone').mask('(00) 00000-0000');

    // Máscara para CNH (11 dígitos numéricos)
    $('#cnh').mask('00000000000');
});