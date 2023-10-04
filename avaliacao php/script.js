document.addEventListener('DOMContentLoaded', function() {

document.getElementById('filtro-form').addEventListener('submit', function (e) {
    e.preventDefault(); // Impede o envio padrão do formulário
    
    // Obter os valores dos campos de filtro
    var filtroEmpresa = document.getElementById('filtro-empresa').value.trim();
    var filtroOperador = document.getElementById('filtro-operador').value;
    var filtroValor = parseFloat(document.getElementById('valor-filtro').value); // Atualizado para usar o campo "valor-filtro"
    var filtroData = document.getElementById('filtro-data').value;
    
    // Filtrar a tabela com base nos critérios
    var rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(function (row) {
        var empresa = row.querySelector('.empresa').textContent;
        var valor = parseFloat(row.querySelector('.valor').textContent.replace('R$', '').replace(',', '.'));
        var data = row.querySelector('.data-pagar').textContent;
        
        var mostrar = true;
        
        // Aplicar filtros
        if (filtroEmpresa && empresa.toLowerCase().indexOf(filtroEmpresa.toLowerCase()) === -1) {
            mostrar = false;
        }
        
        if (!isNaN(filtroValor)) {
            if (filtroOperador === 'maior' && valor <= filtroValor) {
                mostrar = false;
            } else if (filtroOperador === 'menor' && valor >= filtroValor) {
                mostrar = false;
            } else if (filtroOperador === 'igual' && valor !== filtroValor) {
                mostrar = false;
            }
        }
        
        if (filtroData && data !== filtroData) {
            mostrar = false;
        }
        
        // Exibir ou ocultar a linha com base no resultado do filtro
        if (mostrar) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Adiciona o evento "blur" para o campo "valor-filtro"
const valorFiltroInput = document.getElementById('valor-filtro');
valorFiltroInput.addEventListener('blur', function() {
    formatarValor(this);
});

function formatarValor(input) {
    // Remove qualquer caractere não numérico, exceto ponto (.)
    input.value = input.value.replace(/[^\d.]/g, '');

    // Formata o valor como um número decimal com duas casas decimais
    input.value = parseFloat(input.value).toFixed(2);

    // Adiciona a formatação monetária (R$)
    input.value =  input.value.replace('.', ',');
}
});