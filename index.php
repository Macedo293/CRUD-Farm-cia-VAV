<?php
/**
 * =====================================================
 * CRUD FARMÁCIA VAV - Listagem de Produtos (index.php)
 * =====================================================
 * Responsável por:
 * - Buscar todos os produtos cadastrados no banco
 * - Exibir em formato de tabela (desktop) / card (mobile)
 * - Exibir mensagem amigável quando o estoque está vazio
 * - Exibir mensagens de feedback (sucesso/erro) vindas
 *   das outras páginas (cadastro, editar, excluir)
 * =====================================================
 */

// -----------------------------------------------------
// 1. Inclusão da conexão com o banco (PROCESSA primeiro)
// -----------------------------------------------------
require_once 'config/conexao.php';

// -----------------------------------------------------
// 2. Consulta geral de registros no banco
// Adotamos a execução via prepare()/execute() como diretriz
// obrigatória de segurança da aplicação, mesmo sem parâmetros dinâmicos.
// -----------------------------------------------------
$sql = "SELECT id, nome, fabricante, preco, estoque FROM produtos ORDER BY nome ASC";
$comando = $conexao->prepare($sql);
$comando->execute();
$produtos = $comando->fetchAll(); // Retorna um array associativo (FETCH_ASSOC já é padrão)

// -----------------------------------------------------
// 3. Renderização da interface (início da estrutura visual)
// -----------------------------------------------------
require_once 'includes/header.php';
?>

<h2 class="titulo-pagina">📋 Estoque de Produtos</h2>

<?php
// -----------------------------------------------------
// 4. Mensagens de feedback (sucesso/erro) vindas via URL
// Ex: index.php?msg=sucesso ou index.php?msg=erro
// Usado pelas páginas cadastro.php, editar.php e excluir.php
// -----------------------------------------------------
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'sucesso_cadastro') {
        echo '<div class="alerta alerta-sucesso">✅ Produto cadastrado com sucesso!</div>';
    } elseif ($_GET['msg'] === 'sucesso_edicao') {
        echo '<div class="alerta alerta-sucesso">✅ Produto atualizado com sucesso!</div>';
    } elseif ($_GET['msg'] === 'sucesso_exclusao') {
        echo '<div class="alerta alerta-sucesso">🗑️ Produto excluído com sucesso!</div>';
    } elseif ($_GET['msg'] === 'erro') {
        echo '<div class="alerta alerta-erro">❌ Ocorreu um erro ao processar a operação.</div>';
    }
}
?>

<?php if (count($produtos) > 0) : ?>

    <!-- ===================================================
         CASO HAJA PRODUTOS: exibe a tabela/cards
         A mesma tabela vira "card" no mobile e tabela
         tradicional no desktop, graças ao style.css
         =================================================== -->
    <table class="tabela-produtos">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Fabricante</th>
                <th>Preço</th>
                <th>Estoque</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produtos as $produto) : ?>
                <tr>
                    <!--
                        O atributo data-label é o que permite ao CSS
                        exibir o nome da coluna ao lado do valor
                        quando a tabela se transforma em card no mobile.
                    -->
                    <td data-label="Nome">
                        <?= htmlspecialchars($produto['nome']) ?>
                    </td>

                    <td data-label="Fabricante">
                        <?= htmlspecialchars($produto['fabricante']) ?>
                    </td>

                    <td data-label="Preço">
                        R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                    </td>

                    <td data-label="Estoque">
                        <?php if ($produto['estoque'] == 0) : ?>
                            <!-- Destaque em vermelho quando o estoque está zerado -->
                            <span style="color: var(--cor-alerta); font-weight: 600;">
                                Esgotado
                            </span>
                        <?php else : ?>
                            <?= $produto['estoque'] ?> un.
                        <?php endif; ?>
                    </td>

                    <td data-label="Ações" class="acoes">
                        <!-- Link de edição: leva o id do produto via GET -->
                        <a
                            href="editar.php?id=<?= $produto['id'] ?>"
                            class="botao botao-editar"
                        >
                            ✏️ Editar
                        </a>

                        <!--
                            Link de exclusão: leva o id via GET e pede
                            confirmação via JavaScript antes de seguir.

                            Observação acadêmica: em um sistema em produção
                            real, exclusões deveriam ser feitas via método
                            POST (idealmente com token CSRF) em vez de um
                            link GET, por segurança. Para fins didáticos
                            deste CRUD, mantemos o link simples com a
                            confirmação via confirm().
                        -->
                        <a
                            href="excluir.php?id=<?= $produto['id'] ?>"
                            class="botao botao-excluir"
                            onclick="return confirm('Tem certeza que deseja excluir o produto \'<?= htmlspecialchars($produto['nome']) ?>\'? Essa ação não pode ser desfeita.');"
                        >
                            🗑️ Excluir
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Botão de atalho para cadastrar um novo produto -->
    <div style="margin-top: var(--espaco-grande, 24px);">
        <a href="cadastro.php" class="botao botao-acao">
            ➕ Cadastrar Novo Produto
        </a>
    </div>

<?php else : ?>

    <!-- ===================================================
         CASO NÃO HAJA PRODUTOS: mensagem amigável + CTA
         =================================================== -->
    <div class="alerta alerta-erro" style="text-align: center;">
        <p style="margin-bottom: var(--espaco-medio, 16px); font-size: 1rem;">
            📦 O estoque está vazio no momento.
        </p>
        <a href="cadastro.php" class="botao botao-acao">
            ➕ Cadastrar o Primeiro Produto
        </a>
    </div>

<?php endif; ?>

<?php
// -----------------------------------------------------
// 5. Inclusão do rodapé (fecha as tags HTML abertas no header)
// -----------------------------------------------------
require_once 'includes/footer.php';
?>
