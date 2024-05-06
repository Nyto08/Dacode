<?php

$title = 'DaCode | Mon compte';

ob_start();
include './view/inc/navbar.php';
$navbar = ob_get_clean();
?>

<?php ob_start(); ?>

<main class="flex-1">
    <h1 class="text-center mt-16">Mon compte</h1>

    <div class="flex flex-col justify-center mx-auto mt-12 gap-2 font-bold text-accent-dark-dm">
        <div class="flex flex-row justify-center p-2">
            <span class="w-48 rounded-l-lg bg-primary-light-dm py-2 pr-8 text-right">Pseudo :</span>
            <span class="w-64 rounded-r-lg bg-primary-light-dm py-2 pl-4 text-left"><?= $user->getPseudo() ?></span>
        </div>
        <div class="flex flex-row justify-center p-2">
            <span class="w-48 rounded-l-lg bg-primary-light-dm py-2 pr-8 text-right">Email :</span>
            <span class="w-64 rounded-r-lg bg-primary-light-dm py-2 pl-4 text-left"><?= $user->getMail() ?></span>
        </div>
        <div class="flex flex-row justify-center p-2">
            <span class="w-48 rounded-l-lg bg-primary-light-dm py-2 pr-8 text-right">Créé le :</span>
            <span class="w-64 rounded-r-lg bg-primary-light-dm py-2 pl-4 text-left"><?= $user->getDateCreation() ?></span>
        </div>
    </div>
</main>

<?php $content = ob_get_clean(); ?>


<?php
ob_start();
include './view/inc/footer.php';
$footer = ob_get_clean();
?>

<?php require('./view/base.php'); ?>