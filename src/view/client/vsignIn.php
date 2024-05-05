<?php

$title = 'DaCode | Créer un compte';

ob_start();
include './view/inc/navbar.php';
$navbar = ob_get_clean();
?>

<?php ob_start(); ?>

<main class="flex-1 flex flex-col justify-center pb-12 pt-2">

  <div class="flex flex-col items-center justify-center mt-12 mx-auto py-8 px-12 w-[28rem] bg-white rounded-lg text-nightsky-regular-dm">

    <div class="flex flex-row items-center gap-4">
      
      </svg>
        <svg class="fill-nightsky-regular-dm w-16 h-auto" width="89" height="74" viewBox="0 0 89 74" xmlns="http://www.w3.org/2000/svg">
        <path d="M67.9682 37.2208L77.4573 33.6899V56.1253L45.3122 68.9245L24.0537 60.9066V50.682L45.3122 58.9206L67.9682 49.7993V37.2208Z"/>
        <path d="M39.2068 0V10.0775L11.9165 19.9344L39.2068 30.0855V40.3101L0 25.5249V14.7117L39.2068 0Z"/>
        <path d="M49.2112 0V10.0775L76.5015 19.9344L49.2112 30.0855V40.3101L88.418 25.5249V14.7117L49.2112 0Z"/>
        <path d="M11.9904 33.6899L18.0222 36.1174V55.2426H19.6405V74.0001H10.3721V55.2426H11.9168L11.9904 33.6899Z"/>
      </svg>

      <span class="text-3xl font-semibold">Pedacode</span>
    </div>

    <span class="mt-6">Prêt à nous rejoindre ?</span>

    <span class="mt-4 text-rose-600"><?= !empty($msg) ? $msg : '' ?></span>

    <form method="post" action="" class="flex flex-col gap-1 w-full">
      <label class="mt-2" for="pseudo">Pseudo</label>
      <input  class="border-none outline-none bg-gray-100 p-1" type="text" id="pseudo" name="pseudo">

      <label class="mt-2" for="email">Email</label>
      <input  class="border-none outline-none bg-gray-100 p-1" type="email" id="email" name="email">

      <label class="mt-2" for="password">Mot de passe</label>
      <input class="border-none outline-none bg-gray-100 p-1" type="password" id="password" name="password">

      <label class="ml-2" for="terms">
        <input class="mt-6 border-none outline-none" type="checkbox" id="terms" name="terms" required>&nbsp;&nbsp;J'accepte les <a class="text-primary-light-dm hover:text-nightsky-regular-dm underline" href="#">termes et conditions</a> de Pedacode
      </label>

      <input class="p-1 mt-4 border-none outline-none text-white bg-nightsky-light-dm active:bg-nightsky-regular-dm cursor-pointer" type="submit" value="Créer un compte">
    </form>

    <span class="mt-6">Mot de passe oublié ? <a class="text-primary-light-dm hover:text-nightsky-regular-dm underline" href="#">Cliquez-ici</a></span>

  </div>
</main>

<?php $content = ob_get_clean(); ?>


<?php
ob_start();
include './view/inc/footer.php';
$footer = ob_get_clean();
?>

<?php require('./view/base.php'); ?>