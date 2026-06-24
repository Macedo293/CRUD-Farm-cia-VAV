<?php
/**
 * =====================================================
 * CRUD FARMÁCIA VAV - Cadastro de Produtos (cadastro.php)
 * =====================================================
 * Responsável por:
 * - Exibir o formulário de cadastro de um novo produto
 * - Validar os dados enviados via POST
 * - Inserir o produto no banco de forma segura (PDO)
 * - Redirecionar para a listagem em caso de sucesso
 * =====================================================
 */

// -----------------------------------------------------
// 1. Inclusão da conexão com o banco (PROCESSA primeiro)
// -----------------------------------------------------
require_once 'config/conexao.php';

// -----------------------------------------------------
// 2. Variáveis de controle da página
// $erro guarda uma mensagem de erro de validação, se houver
// -----------------------------------------------------
$erro = '';

// -----------------------------------------------------
// 3. Processamento do formulário (somente se for POST)
// -----------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Captura os dados enviados pelo formulário.
    // trim() remove espaços em branco extras no início/fim.
    $nome       = trim($_POST['nome'] ?? '');
    $fabricante = trim($_POST['fabricante'] ?? '');
    $preco      = trim($_POST['preco'] ?? '');
    $estoque    = trim($_POST['estoque'] ?? '');

    // ---------------------------------------------
    // 3.1 Validação: nenhum campo pode estar vazio
    // ---------------------------------------------
    if (empty($nome) || empty($fabricante) || $preco === '' || $estoque === '') {
        $erro = 'Por favor, preencha todos os campos antes de cadastrar o produto.';

    } else {

        // ---------------------------------------------
        // 3.2 Tratamento do campo preço
        // Aceita tanto "12.50" quanto "12,50" digitados
        // pelo usuário, convertendo vírgula para ponto
        // antes de enviar ao banco (que exige formato
        // decimal com ponto).
        // ---------------------------------------------
        $preco = str_replace(',', '.', $preco);

        // Validação extra: preco e estoque devem ser numéricos e não-negativos
        if (!is_numeric($preco) || $preco < 0) {
            $erro = 'Informe um preço válido (apenas números, ex: 12.50).';

        } elseif (!is_numeric($estoque) || $estoque < 0) {
            $erro = 'Informe uma quantidade de estoque válida (apenas números inteiros).';

        } else {

            // ---------------------------------------------
            // 3.3 Inserção segura no banco com prepare/execute
            // Os "?" são placeholders que o PDO substitui
            // pelos valores reais de forma segura, evitando
            // SQL Injection.
            // ---------------------------------------------
            $sql = "INSERT INTO produtos (nome, fabricante, preco, estoque) VALUES (?, ?, ?, ?)";
            $comando = $conexao->prepare($sql);
            $comando->execute([$nome, $fabricante, $preco, (int) $estoque]);

            // ---------------------------------------------
            // 3.4 Padrão PRG (Post/Redirect/Get)
            // Redireciona para a listagem com uma mensagem
            // de sucesso, evitando reenvio duplicado do
            // formulário caso o usuário recarregue a página.
            // ---------------------------------------------
            header('Location: index.php?msg=sucesso_cadastro');
            exit(); // Importante: interrompe a execução do script após o redirecionamento
        }
    }
}

// -----------------------------------------------------
// 4. Inclusão do cabeçalho (a partir daqui começa o HTML)
// Só chegamos aqui se NÃO houve redirecionamento, ou seja,
// é a primeira visita à página OU houve erro de validação.
// -----------------------------------------------------
require_once 'includes/header.php';
?>

<h2 class="titulo-pagina">➕ Cadastrar Novo Produto</h2>

<?php
// Exibe a mensagem de erro de validação, se houver
if (!empty($erro)) {
    echo '<div class="alerta alerta-erro">⚠️ ' . htmlspecialchars($erro) . '</div>';
}
?>

<!--
    O formulário envia para o próprio cadastro.php (method="post"),
    onde a lógica acima é responsável por processar os dados.
-->
<form action="cadastro.php" method="post" class="formulario">

    <div class="campo">
        <label for="nome">Nome do Produto</label>
        <input
            type="text"
            id="nome"
            name="nome"
            placeholder="Ex: Paracetamol 750mg"
            value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>"
            required
        >
    </div>

    <div class="campo">
        <label for="fabricante">Fabricante</label>
        <input
            type="text"
            id="fabricante"
            name="fabricante"
            placeholder="Ex: EMS"
            value="<?= htmlspecialchars($_POST['fabricante'] ?? '') ?>"
            required
        >
    </div>

    <div class="campo">
        <label for="preco">Preço (R$)</label>
        <input
            type="number"
            id="preco"
            name="preco"
            step="0.01"
            min="0"
            placeholder="Ex: 12.50"
            value="<?= htmlspecialchars($_POST['preco'] ?? '') ?>"
            required
        >
    </div>

    <div class="campo">
        <label for="estoque">Quantidade em Estoque</label>
        <input
            type="number"
            id="estoque"
            name="estoque"
            step="1"
            min="0"
            placeholder="Ex: 100"
            value="<?= htmlspecialchars($_POST['estoque'] ?? '') ?>"
            required
        >
    </div>

    <!-- Botão de ação principal: 10% cor de destaque (laranja) -->
    <button type="submit" class="botao botao-acao">
        💾 Salvar Produto
    </button>

</form>

<!-- Link para voltar à listagem sem precisar cadastrar -->
<div style="margin-top: var(--espaco-medio, 16px);">
    <a href="index.php" class="menu-link" style="color: var(--cor-secundaria); text-decoration: underline;">
        ← Voltar para o Estoque
    </a>
</div>

<?php
// -----------------------------------------------------
// 5. Inclusão do rodapé (fecha as tags HTML abertas no header)
// -----------------------------------------------------
require_once 'includes/footer.php';
?>