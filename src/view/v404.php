<?php

$title = 'DaCode | Not found';

ob_start();
include './view/inc/navbar.php';
$navbar = ob_get_clean();
?>

<?php ob_start(); ?>

<main class="flex-1 flex flex-col gap-10 items-center justify-center">
    <h1 class="text-center text-4xl">404 Page introuvable</h1>
    <a class="btn-default" href="<?= APP_ROOT ?>/accueil">Retourner à l'accueil</a>
</main>

<?php $content = ob_get_clean(); ?>


<?php
ob_start();
include './view/inc/footer.php';
$footer = ob_get_clean();
?>

<?php require('./view/base.php'); ?>