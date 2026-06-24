<?php
/**
 * =====================================================
 * CRUD FARMÁCIA VAV - Cadastro de Produtos (cadastro.php)
 * =====================================================
 * Fluxo de execução:
 * - Exibe o formulário de inserção de um novo produto.
 * - Captura, higieniza e valida as entradas via POST.
 * - Executa a persistência segura no banco usando PDO.
 * - Redireciona para o painel principal em caso de sucesso.
 * =====================================================
 */

// -----------------------------------------------------
// 1. Inicialização e Conectividade
// -----------------------------------------------------
require_once 'config/conexao.php';

// -----------------------------------------------------
// 2. Variáveis de Controle da Página
// -----------------------------------------------------
$erro = '';

// -----------------------------------------------------
// 3. Processamento e Persistência do Formulário (POST)
// -----------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Captura e higieniza espaços em branco das entradas
    $nome       = trim($_POST['nome'] ?? '');
    $fabricante = trim($_POST['fabricante'] ?? '');
    $preco      = trim($_POST['preco'] ?? '');
    $estoque    = trim($_POST['estoque'] ?? '');

    // ---------------------------------------------
    // 3.1 Regras de Negócio e Validações de Campo
    // ---------------------------------------------
    if (empty($nome) || empty($fabricante) || $preco === '' || $estoque === '') {
        $erro = 'Por favor, preencha todos os campos antes de cadastrar o produto.';

    } else {

        // Normaliza o formato monetário (substitui vírgula por ponto)
        $preco = str_replace(',', '.', $preco);

        if (!is_numeric($preco) || $preco < 0) {
            $erro = 'Informe um preço válido (apenas números, ex: 12.50).';

        } elseif (!is_numeric($estoque) || $estoque < 0) {
            $erro = 'Informe uma quantidade de estoque válida (apenas números inteiros).';

        } else {

            // ---------------------------------------------
            // 3.2 Inserção no Banco de Dados (Prepared Statements)
            // Uso de placeholders posicionais (?) para blindagem contra SQL Injection
            // ---------------------------------------------
            $sql = "INSERT INTO produtos (nome, fabricante, preco, estoque) VALUES (?, ?, ?, ?)";
            $comando = $conexao->prepare($sql);
            $comando->execute([$nome, $fabricante, $preco, (int) $estoque]);

            // Padrão PRG (Post/Redirect/Get): evita reenvio de formulário ao atualizar a página
            header('Location: index.php?msg=sucesso_cadastro');
            exit(); 
        }
    }
}

// -----------------------------------------------------
// 4. Renderização da Interface (Visão / HTML)
// Acessado no carregamento inicial (GET) ou se houver falhas de validação
// -----------------------------------------------------
require_once 'includes/header.php';
?>

<h2 class="titulo-pagina">➕ Cadastrar Novo Produto</h2>

<?php
// Exibição de mensagens de erro de validação
if (!empty($erro)) {
    echo '<div class="alerta alerta-erro">⚠️ ' . htmlspecialchars($erro) . '</div>';
}
?>

<form action="cadastro.php" method="post" class="formulario">

    <div class="campo">
        <label for="nome">Nome do Produto</label>
        <input
            type="text"
            id="nome"
            name="nome"
            placeholder="Ex: Paracetamol 750mg"
            /* Preserva o valor preenchido caso ocorra algum erro de validação */
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

    <button type="submit" class="botao botao-acao">
        💾 Salvar Produto
    </button>

</form>

<div style="margin-top: var(--espaco-medio, 16px);">
    <a href="index.php" class="menu-link" style="color: var(--cor-secundaria); text-decoration: underline;">
        ← Voltar para o Estoque
    </a>
</div>

<?php
// -----------------------------------------------------
// 5. Encerramento do Documento HTML
// -----------------------------------------------------
require_once 'includes/footer.php';
?>
