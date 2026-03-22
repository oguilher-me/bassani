document.addEventListener('DOMContentLoaded', function() {
    const placaInput = document.getElementById('placa');

    if (placaInput) {
        placaInput.addEventListener('input', function(e) {
            let value = e.target.value;

            // Remove todos os caracteres que não são letras ou números e converte para maiúsculas
            value = value.replace(/[^a-zA-Z0-9]/g, '').toUpperCase();

            // Limita o tamanho da placa para 7 caracteres (AAA0A00 ou AAA0000)
            value = value.substring(0, 7);

            let formattedValue = value;

            // Aplica o hífen apenas se for uma placa antiga completa (AAA0000)
            if (value.length === 7) {
                // Regex para o padrão de placa antiga: LLLNNNN
                const oldPlateRegex = /^[A-Z]{3}[0-9]{4}$/;
                if (oldPlateRegex.test(value)) {
                    formattedValue = value.substring(0, 3) + '-' + value.substring(3, 7);
                }
                // Para placas Mercosul (LLLNLNN), nenhum hífen é adicionado, então formattedValue permanece como 'value'.
            }

            e.target.value = formattedValue;
        });
    }
});

