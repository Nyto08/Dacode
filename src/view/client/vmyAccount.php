<?php

$title = 'DaCode | Mon compte';

ob_start();
include './view/inc/navbar.php';
$navbar = ob_get_clean();
?>

<?php ob_start(); ?>

<main class="flex-1">
    <h1 class="text-center mt-6">Mon compte</h1>

    <!-- TODO : afficher les infos user -->
</main>

<?php $content = ob_get_clean(); ?>


<?php
ob_start();
include './view/inc/footer.php';
$footer = ob_get_clean();
?>

<?php require('./view/base.php'); ?>