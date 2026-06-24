<?php
/**
 * =====================================================
 * CRUD FARMÁCIA VAV - Exclusão de Produtos (excluir.php)
 * =====================================================
 * Responsável por:
 * - Receber o ID do produto via GET
 * - Executar a exclusão (DELETE) no banco de forma segura
 * - Redirecionar de volta para a listagem (index.php)
 *
 * Este arquivo NÃO possui interface visual (HTML), pois é
 * apenas uma rota de processamento. A confirmação da
 * exclusão já é feita pelo usuário antes de chegar aqui,
 * através do confirm() em JavaScript presente nos links
 * de exclusão do index.php.
 * =====================================================
 */

// -----------------------------------------------------
// 1. Inclusão da conexão com o banco
// -----------------------------------------------------
require_once 'config/conexao.php';

// -----------------------------------------------------
// 2. Validação do ID recebido via GET
// Verifica se o parâmetro existe e se é numérico, antes
// de qualquer interação com o banco de dados.
// -----------------------------------------------------
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php?msg=erro');
    exit();
}

// Converte para inteiro por segurança extra (defesa em profundidade)
$id = (int) $_GET['id'];

// -----------------------------------------------------
// // 3. Vincula o ID via prepared statement (:id) para higienizar a entrada de dados
// -----------------------------------------------------
$sql = "DELETE FROM produtos WHERE id = :id";
$comando = $conexao->prepare($sql);
$comando->execute([':id' => $id]);

// -----------------------------------------------------
// 4. Valida se a operação teve impacto no banco
// rowCount() contabiliza as linhas afetadas pela última query.
// Um resultado igual a zero indica que nenhum produto possui o ID fornecido..
// -----------------------------------------------------
if ($comando->rowCount() > 0) {
    header('Location: index.php?msg=sucesso_exclusao');
} else {
    header('Location: index.php?msg=erro');
}

exit(); // Garante que nenhum código adicional seja executado após o redirecionamento
