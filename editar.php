<?php
/**
 * =====================================================
 * CRUD FARMÁCIA VAV - Edição de Produtos (editar.php)
 * =====================================================
 * Fluxo de execução:
 * - Recupera e sanitiza o ID do produto via GET.
 * - Busca o registro correspondente no banco de dados.
 * - Renderiza o formulário com os valores atuais.
 * - Processa e valida a atualização (UPDATE) via POST.
 * =====================================================
 */

// -----------------------------------------------------
// 1. Inicialização e Conectividade
// -----------------------------------------------------
require_once 'config/conexao.php';

$erro = '';

// -----------------------------------------------------
// 2. Validação de Segurança do Parâmetro de Entrada
// Garante a presença do ID e mitiga ataques de URL/Injection.
// -----------------------------------------------------
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // ID ausente ou inválido: aborta e retorna para a listagem
    header('Location: index.php?msg=erro');
    exit();
}

// Aplica conversão para inteiro como camada de defesa em profundidade
$id = (int) $_GET['id'];

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
        $erro = 'Por favor, preencha todos os campos antes de salvar as alterações.';

    } else {

        // Normaliza o formato monetário (substitui vírgula por ponto)
        $preco = str_replace(',', '.', $preco);

        if (!is_numeric($preco) || $preco < 0) {
            $erro = 'Informe um preço válido (apenas números, ex: 12.50).';

        } elseif (!is_numeric($estoque) || $estoque < 0) {
            $erro = 'Informe uma quantidade de estoque válida (apenas números inteiros).';

        } else {

            // ---------------------------------------------
            // 3.2 Atualização no Banco de Dados (Prepared Statements)
            // Uso de placeholders nominais para blindagem contra SQL Injection
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
                ':estoque'    => (int) $estoque, // Força tipagem estrita
                ':id'         => $id,
            ]);

            // Padrão PRG (Post/Redirect/Get): evita reenvio de formulário ao atualizar a página
            header('Location: index.php?msg=sucesso_edicao');
            exit();
        }
    }
}

// -----------------------------------------------------
// 4. Recuperação do Estado Atual do Registro
// Executado no carregamento inicial (GET) ou em caso de falha no POST
// -----------------------------------------------------
$sql = "SELECT id, nome, fabricante, preco, estoque FROM produtos WHERE id = :id";
$comando = $conexao->prepare($sql);
$comando->execute([':id' => $id]);
$produto = $comando->fetch(); // Retorna o array do produto ou 'false' se não localizado

// -----------------------------------------------------
// 5. Verificação de Existência do Registro
// Previne o acesso caso o ID mapeado na URL não conste no banco
// -----------------------------------------------------
if (!$produto) {
    header('Location: index.php?msg=erro');
    exit();
}

// -----------------------------------------------------
// 6. Renderização da Interface (Visão / HTML)
// -----------------------------------------------------
require_once 'includes/header.php';
?>

<h2 class="titulo-pagina">✏️ Editar Produto</h2>

<?php
// Exibição de mensagens de erro de validação
if (!empty($erro)) {
    echo '<div class="alerta alerta-erro">⚠️ ' . htmlspecialchars($erro) . '</div>';
}
?>

<form action="editar.php?id=<?= $produto['id'] ?>" method="post" class="formulario">

    <div class="campo">
        <label for="nome">Nome do Produto</label>
        <input
            type="text"
            id="nome"
            name="nome"
            /* Mantém os dados digitados em caso de falha no POST, senão carrega o original do banco */
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

    <button type="submit" class="botao botao-acao">
        💾 Salvar Alterações
    </button>

</form>

<div style="margin-top: var(--espaco-medio, 16px);">
    <a href="index.php" class="menu-link" style="color: var(--cor-secundaria); text-decoration: underline;">
        ← Cancelar e Voltar para o Estoque
    </a>
</div>

<?php
// -----------------------------------------------------
// 7. Encerramento do Documento HTML
// -----------------------------------------------------
require_once 'includes/footer.php';
?>
