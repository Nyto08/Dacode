<?php

$title = 'DaCode | À propos';

ob_start();
include './view/inc/navbar.php';
$navbar = ob_get_clean();
?>

<?php ob_start(); ?>

<main class="flex-1">

    <div class="max-w-[1080px] px-6 mx-auto">
        <h1 class="text-center text-4xl mt-20">Qu'est-ce que DaCode</h1>
        <p class="mt-14">DaCode, ou plutôt PedaCode, était à l'origine un projet avec <a class="underline" href="https://aimane-bougtaib.netlify.app/" target="_blank">Aïmane Bougtaïb</a> où nous souhaitions concevoir un site permettant d'apprendre le code avec tout un système de cours et de progression.</p>
        <p class="mt-6">La difficulté à venir pour ma part, était d'assurer la sécurité car le principe repose tout de même sur du code saisi par l'utilisateur, et il est aisé pour quelqu'un de confirmé de charger du code malveillant.</p>
        <p class="mt-6">Finalement, avec le manque de temps, nous avons décidé d'abandonner ce projet pour nous concentrer sur la fin de notre formation. Voici donc ce qui reste de ma contribution à ce projet.</p>

    </div>

</main>

<?php $content = ob_get_clean(); ?>


<?php
ob_start();
include './view/inc/footer.php';
$footer = ob_get_clean();
?>

<?php require('./view/base.php'); ?>