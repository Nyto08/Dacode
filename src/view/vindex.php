<?php

$title = 'DaCode | Accueil';

ob_start();
include './view/inc/navbar.php';
$navbar = ob_get_clean();
?>

<?php ob_start(); ?>

<main class="flex-1 flex flex-col gap-6 my-6 items-center justify-center">
    <div class="flex flex-col w-screen items-center px-6">
        <h1 class="text-center text-4xl text-primary-light-dm">Bienvenue sur DaCode !</h1>
        <p class="text-center max-w-[800px] mt-8">Vous pouvez aller au mode playground pour commencer à coder mais pour sauvegarder ou charger du code vous aurez besoin d'un compte</p>
    </div>
    <div class="items-center justify-center flex gap-x-4 gap-y-1 flex-wrap">
        <a href="<?= APP_ROOT ?>/login" class="btn-default text-xl p-2">Connexion</a>
        <a href="<?= APP_ROOT ?>/playground" class="btn-default text-xl p-2">Playground</a>
    </div>
</main>

<?php $content = ob_get_clean(); ?>

<?php
ob_start();
include './view/inc/footer.php';
$footer = ob_get_clean();
?>

<?php require('./view/base.php'); ?>