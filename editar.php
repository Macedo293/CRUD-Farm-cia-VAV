<?php
/**
 * =====================================================
 * CRUD FARMÁCIA VAV - Edição de Produtos (editar.php)
 * =====================================================
 * Responsável por:
 * - Receber o ID do produto via GET
 * - Buscar os dados atuais desse produto no banco
 * - Exibir um formulário preenchido para edição
 * - Processar a atualização (UPDATE) via POST
 * =====================================================
 */

// -----------------------------------------------------
// 1. Inclusão da conexão com o banco (PROCESSA primeiro)
// -----------------------------------------------------
require_once 'config/conexao.php';

$erro = '';

// -----------------------------------------------------
// 2. Validação do ID recebido via GET
// Verifica se o parâmetro existe e se é um número,
// prevenindo valores inválidos ou maliciosos na URL.
// -----------------------------------------------------
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // ID ausente ou em formato inválido: volta para a listagem
    header('Location: index.php?msg=erro');
    exit();
}

// Converte para inteiro por segurança extra (defesa em profundidade)
$id = (int) $_GET['id'];

// -----------------------------------------------------
// 3. Processamento do formulário de edição (se for POST)
// -----------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome       = trim($_POST['nome'] ?? '');
    $fabricante = trim($_POST['fabricante'] ?? '');
    $preco      = trim($_POST['preco'] ?? '');
    $estoque    = trim($_POST['estoque'] ?? '');

    // ---------------------------------------------
    // 3.1 Validação: nenhum campo pode estar vazio
    // ---------------------------------------------
    if (empty($nome) || empty($fabricante) || $preco === '' || $estoque === '') {
        $erro = 'Por favor, preencha todos os campos antes de salvar as alterações.';

    } else {

        // Aceita vírgula ou ponto no preço, assim como no cadastro.php
        $preco = str_replace(',', '.', $preco);

        if (!is_numeric($preco) || $preco < 0) {
            $erro = 'Informe um preço válido (apenas números, ex: 12.50).';

        } elseif (!is_numeric($estoque) || $estoque < 0) {
            $erro = 'Informe uma quantidade de estoque válida (apenas números inteiros).';

        } else {

            // ---------------------------------------------
            // 3.2 Atualização segura no banco com prepare/execute
            // Usamos placeholders nomeados (:nome, :preco, etc.)
            // ---------------------------------------------
            $sql = "UPDATE produtos
                    SET nome = :nome,
                        fabricante = :fabricante,
                        preco = :preco,
                        estoque = :estoque
                    WHERE id = :id";

            $comando = $conexao->prepare($sql);
            $comando->execute([
                ':nome'       => $nome,
                ':fabricante' => $fabricante,
                ':preco'      => $preco,
                ':estoque'    => (int) $estoque,
                ':id'         => $id,
            ]);

            // Padrão PRG: redireciona após sucesso, evitando reenvio duplicado
            header('Location: index.php?msg=sucesso_edicao');
            exit();
        }
    }
}

// -----------------------------------------------------
// 4. Busca dos dados atuais do produto no banco
// Isso roda tanto na primeira visita à página (GET inicial)
// quanto quando o POST falhou na validação (para re-exibir
// o formulário com o id correto e os dados originais como
// referência, embora os campos sejam repreenchidos com o
// que o usuário digitou via $_POST mais abaixo).
// -----------------------------------------------------
$sql = "SELECT id, nome, fabricante, preco, estoque FROM produtos WHERE id = :id";
$comando = $conexao->prepare($sql);
$comando->execute([':id' => $id]);
$produto = $comando->fetch(); // fetch() retorna uma única linha (ou false se não encontrar)

// -----------------------------------------------------
// 5. Se o produto não existir no banco, redireciona
// Isso cobre o caso de alguém digitar um ID inexistente
// diretamente na URL (ex: editar.php?id=9999)
// -----------------------------------------------------
if (!$produto) {
    header('Location: index.php?msg=erro');
    exit();
}

// -----------------------------------------------------
// 6. Inclusão do cabeçalho (a partir daqui começa o HTML)
// -----------------------------------------------------
require_once 'includes/header.php';
?>

<h2 class="titulo-pagina">✏️ Editar Produto</h2>

<?php
if (!empty($erro)) {
    echo '<div class="alerta alerta-erro">⚠️ ' . htmlspecialchars($erro) . '</div>';
}
?>

<!--
    O formulário envia para o próprio editar.php, mantendo o
    id do produto na query string da action, para que o PHP
    saiba qual produto deve ser atualizado ao processar o POST.
-->
<form action="editar.php?id=<?= $produto['id'] ?>" method="post" class="formulario">

    <div class="campo">
        <label for="nome">Nome do Produto</label>
        <input
            type="text"
            id="nome"
            name="nome"
            value="<?= htmlspecialchars($_POST['nome'] ?? $produto['nome']) ?>"
            required
        >
    </div>

    <div class="campo">
        <label for="fabricante">Fabricante</label>
        <input
            type="text"
            id="fabricante"
            name="fabricante"
            value="<?= htmlspecialchars($_POST['fabricante'] ?? $produto['fabricante']) ?>"
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
            value="<?= htmlspecialchars($_POST['preco'] ?? $produto['preco']) ?>"
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
            value="<?= htmlspecialchars($_POST['estoque'] ?? $produto['estoque']) ?>"
            required
        >
    </div>

    <!-- Botão de ação principal: 10% cor de destaque (laranja) -->
    <button type="submit" class="botao botao-acao">
        💾 Salvar Alterações
    </button>

</form>

<!-- Link para voltar à listagem sem salvar -->
<div style="margin-top: var(--espaco-medio, 16px);">
    <a href="index.php" class="menu-link" style="color: var(--cor-secundaria); text-decoration: underline;">
        ← Cancelar e Voltar para o Estoque
    </a>
</div>

<?php
// -----------------------------------------------------
// 7. Inclusão do rodapé (fecha as tags HTML abertas no header)
// -----------------------------------------------------
require_once 'includes/footer.php';
?>