<?php
/**
 * =====================================================
 * CRUD FARMÁCIA VAV - Conexão com Banco de Dados
 * =====================================================
 * Este arquivo é responsável por estabelecer a conexão
 * com o banco de dados MySQL utilizando PDO.
 *
 * Ele será incluído (require_once) em todas as páginas
 * que precisarem acessar o banco: index.php, cadastro.php,
 * editar.php e excluir.php.
 * =====================================================
 */

// ---------------------------------------------------
// Variáveis de configuração do banco de dados
// Ajuste estes valores conforme o seu ambiente
// (XAMPP, Laragon, servidor de produção, etc.)
// ---------------------------------------------------
$host   = 'localhost';        // Endereço do servidor do banco
$dbname = 'farmacia_vav';     // Nome do banco de dados criado no database.sql
$usuario = 'root';            // Usuário do banco (padrão do XAMPP/Laragon)
$senha   = '';                // Senha do banco (padrão do XAMPP é vazio)

// ---------------------------------------------------
// Monta a string de conexão (DSN - Data Source Name)
// Define também o charset utf8mb4 para suportar
// acentuação correta (ç, ã, é, etc.)
// ---------------------------------------------------
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

// ---------------------------------------------------
// Tenta estabelecer a conexão com o banco de dados
// Usamos try/catch para capturar falhas de conexão
// (ex: banco fora do ar, credenciais incorretas)
// sem expor informações sensíveis na tela do usuário
// ---------------------------------------------------
try {
    // Cria o objeto PDO de conexão
    $conexao = new PDO($dsn, $usuario, $senha);

    // Faz com que o PDO lance exceções em caso de erro
    // em qualquer execução de query (INSERT, UPDATE, etc.)
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Define que os resultados de SELECT virão sempre
    // como array associativo (ex: $produto['nome'])
    $conexao->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $erro) {
    // Caso a conexão falhe, exibe uma mensagem amigável
    // e interrompe a execução do script com die()
    die('Erro ao conectar com o banco de dados: ' . $erro->getMessage());
}

/**
 * A partir daqui, qualquer arquivo que fizer
 * require_once 'config/conexao.php'
 * terá acesso à variável $conexao para realizar
 * operações no banco usando prepare() e execute().
 */