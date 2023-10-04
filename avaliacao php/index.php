<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <title>Controle Financeiro de Contas a Pagar</title>
</head>
<body>
    <h1>Contas a Pagar</h1>

    <!-- Formulário para adicionar uma conta a pagar -->
    <form action="adicionar_conta.php" method="post">
        <label for="empresa">Empresa:</label>
        <select name="empresa" id="empresa">
            <?php
            // Inclua o arquivo de configuração para obter a conexão com o banco de dados
            include("config.php");

            if ($conn->connect_error) {
                echo "<option value='-1'>Erro de conexão com o banco de dados</option>";
            } else {
                // Consulta SQL para obter as opções de empresas do banco de dados
                $sql = "SELECT id_empresa, nome FROM tbl_empresa";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . $row["id_empresa"] . '">' . $row["nome"] . '</option>';
                    }
                }
            }
            ?>
        </select>
        <br>
        <label for="data_pagar">Data a ser pago:</label>
        <input type="date" name="data_pagar" id="data_pagar" required>
        <br>
        <label for="valor-filtro">Valor a ser pago (R$):</label>
        <input type="text" name="valor-filtro" id="valor-filtro">
        <br>
        <input type="submit" value="Inserir">
    </form>

    <!-- Formulário de Filtro -->
    <form id="filtro-form">
        <label for="filtro-empresa">Filtrar por Nome da Empresa:</label>
        <input type="text" id="filtro-empresa" name="filtro-empresa">
        
        <label for="filtro-valor">Filtrar por Valor:</label>
        <select id="filtro-operador" name="filtro-operador">
            <option value="maior">Maior que</option>
            <option value="menor">Menor que</option>
            <option value="igual">Igual a</option>
        </select>
        <input type="text" id="filtro-valor" name="filtro-valor">
        
        <label for="filtro-data">Filtrar por Data de Pagamento:</label>
        <input type="date" id="filtro-data" name="filtro-data">
        
        <input type="submit" value="Filtrar">
    </form>

    <!-- Tabela para listar contas a pagar -->
    <table>
        <thead>
            <tr>
                <th>Empresa</th>
                <th>Data a ser pago</th>
                <th>Valor</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Inclua o arquivo de configuração para obter a conexão com o banco de dados
            include("config.php");

            // Verifique se a conexão foi estabelecida com sucesso
            if (!$conn->connect_error) {
                // Consulta SQL para obter as contas a pagar do banco de dados
                $sql = "SELECT tbl_empresa.nome AS empresa, tbl_conta_pagar.data_pagar, tbl_conta_pagar.valor, tbl_conta_pagar.pago
                        FROM tbl_conta_pagar
                        INNER JOIN tbl_empresa ON tbl_conta_pagar.id_empresa = tbl_empresa.id_empresa";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Aplicar desconto ou acréscimo com base na data de pagamento
                        $dataPagamento = strtotime($row["data_pagar"]);
                        $hoje = strtotime(date("Y-m-d"));
                        $valorConta = $row["valor"];
                        $status = "A pagar";

                        if ($row["pago"] == 1) {
                            $status = "Pago";
                        } elseif ($hoje < $dataPagamento) {
                            $valorConta *= 0.95; // Desconto de 5% antes da data de pagamento
                        } elseif ($hoje > $dataPagamento) {
                            $valorConta *= 1.10; // Acréscimo de 10% após a data de pagamento
                        }

                        echo "<tr>";
                        echo "<td class='empresa'>" . $row["empresa"] . "</td>";
                        echo "<td class='data-pagar'>" . $row["data_pagar"] . "</td>";
                        echo "<td class='valor'>R$ " . number_format($valorConta, 2, ',', '.') . "</td>";
                        echo "<td class='status'>" . $status . "</td>";
                        echo "<td><button class='editar'>Editar</button> <button class='excluir'>Excluir</button></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>Nenhuma conta a pagar encontrada.</td></tr>";
                }

                // Feche a conexão com o banco de dados
                $conn->close();
            } else {
                echo "<tr><td colspan='5'>Erro de conexão com o banco de dados</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <script src="script.js"></script>
</body>
</html>
