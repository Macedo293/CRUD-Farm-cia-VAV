<?php
/**
 * =====================================================
 * CRUD FARMÁCIA VAV - Cabeçalho (Header)
 * =====================================================
 * Este arquivo contém a estrutura inicial do HTML
 * (head, meta tags, css) e o menu de navegação.
 *
 * É incluído no topo de todas as páginas através de:
 * require_once 'includes/header.php';
 *
 * IMPORTANTE: a tag <main> é aberta aqui e só é
 * fechada no footer.php. Ou seja, todo o conteúdo
 * específico de cada página (index, cadastro, editar)
 * fica "dentro" dessa moldura header + footer.
 * =====================================================
 */
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <!-- Meta viewport é OBRIGATÓRIA para o Mobile First funcionar corretamente -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmácia VAV - Gestão de Estoque</title>

    <!-- Importação do CSS principal do projeto -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <!-- ===================================================== -->
    <!-- Cabeçalho fixo no topo: logo/título + menu de navegação -->
    <!-- ===================================================== -->
    <header class="cabecalho">
        <div class="cabecalho-container">

            <!-- Logo/Nome do sistema -->
            <h1 class="logo">💊 Farmácia VAV</h1>

            <!-- Menu de navegação principal -->
            <nav class="menu">
                <ul class="menu-lista">
                    <li>
                        <!--
                            Sugestão de evolução: usar
                            basename($_SERVER['PHP_SELF']) para comparar
                            com o nome do arquivo atual e adicionar a
                            classe "menu-ativo" automaticamente no link
                            da página em que o usuário está.
                        -->
                        <a href="index.php" class="menu-link">Estoque</a>
                    </li>
                    <li>
                        <a href="cadastro.php" class="menu-link">Cadastrar Produto</a>
                    </li>
                </ul>
            </nav>

        </div>
    </header>

    <!-- ===================================================== -->
    <!-- Conteúdo principal: cada página (index, cadastro, etc) -->
    <!-- escreve seu conteúdo aqui dentro                       -->
    <!-- ===================================================== -->
    <main class="conteudo-principal">