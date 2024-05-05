<?php

$title = 'DaCode | Accueil';

ob_start();
include './view/inc/navbar.php';
$navbar = ob_get_clean();
?>

<?php ob_start(); ?>

<main class="flex-1 flex flex-col gap-6 my-6 items-center justify-center">
    <div class="flex flex-col gap-6 my-6 items-center justify-center">
        <div class="flex flex-col w-screen items-center mt-[-100px]">
            <h1 class="text-center text-primary-light-dm uppercase">Bienvenue sur DaCode !</h1>
            <p class="text-center max-w-[800px] mt-8">Vous pouvez aller au mode playground pour commencer à coder mais pour sauvegarder votre espace ou partager votre code vous aurez besoin d'un compte</p>
        </div>
        <div class="items-center justify-center flex gap-2 relative wrap">
            <a href="<?= APP_ROOT ?>/cursus" class="overflow-hidden w-32 p-2 h-12 bg-nightsky-light-dm border-none rounded-md text-xl font-bold cursor-pointer text-center relative z-10 group">
                Connexion
                <span class="absolute w-36 h-32 -top-8 -left-2 bg-nightsky-light-lm rotate-12 transform scale-x-0 group-hover:scale-x-100 transition-transform group-hover:duration-500 duration-1000 origin-right">
                </span>
                <span class="absolute w-36 h-32 -top-8 -left-2 bg-nightsky-regular-dm rotate-12 transform scale-x-0 group-hover:scale-x-100 transition-transform group-hover:duration-1000 duration-500 origin-right">
                </span>
                <span class="group-hover:opacity-100 group-hover:duration-1000 duration-100 opacity-0 absolute top-2.5 left-6 z-10">Allons-y !</span>
            </a>
            <a href="<?= APP_ROOT ?>/playground" class="overflow-hidden w-32 p-2 h-12 bg-nightsky-light-dm border-none rounded-md text-xl font-bold cursor-pointer text-center relative z-10 group">
                Playground
                <span class="absolute w-36 h-32 -top-8 -left-2 bg-nightsky-light-lm rotate-12 transform scale-x-0 group-hover:scale-x-100 transition-transform group-hover:duration-500 duration-1000 origin-right">
                </span>
                <span class="absolute w-36 h-32 -top-8 -left-2 bg-nightsky-regular-dm rotate-12 transform scale-x-0 group-hover:scale-x-100 transition-transform group-hover:duration-1000 duration-500 origin-right">
                </span>
                <span class="group-hover:opacity-100 group-hover:duration-1000 duration-100 opacity-0 absolute top-2.5 left-6 z-10">C'est parti</span>
            </a>
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