<?php
/**
 * =====================================================
 * SISTEMA CRUD FARMÁCIA VAV - ARQUIVO DE CABEÇALHO (Header)
 * =====================================================
 * Define a estrutura padrão do topo da aplicação, tags meta,
 * carregamento de estilos e menu de navegação global.
 *
 * Uso: Deve ser incluído no início de todas as páginas com:
 * require_once 'includes/header.php';
 *
 * NOTA ARQUITETURAL: A tag <main> inicia neste arquivo e permanece
 * aberta. Ela será fechada obrigatoriamente no arquivo 'footer.php'.
 * Isso cria uma moldura fixa para o conteúdo dinâmico das páginas.
 * =====================================================
 */
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmácia VAV - Gestão de Estoque</title>

    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <header class="cabecalho">
        <div class="cabecalho-container">

            <h1 class="logo">💊 Farmácia VAV</h1>

            <nav class="menu">
                <ul class="menu-lista">
                    <li>
                        <a href="index.php" class="menu-link">Estoque</a>
                    </li>
                    <li>
                        <a href="cadastro.php" class="menu-link">Cadastrar Produto</a>
                    </li>
                </ul>
            </nav>

        </div>
    </header>

    <main class="conteudo-principal">
