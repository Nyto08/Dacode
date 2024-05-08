<?php

$title = 'DaCode | Playground';

ob_start();
include './view/inc/navbar.php';
$navbar = ob_get_clean();
?>

<?php ob_start(); ?>

  <!-- modal pour sauvegarder/charger le code utilisateur -->
  <!-- le dialog est changé à la volée selon que l'utilisateur clique sur sauver ou charger -->
  <dialog id="user-data-modal" class="px-4 py-2 border-2 border-solid border-nightsky-light-dm text-lg font-sans tracking-[.0125em] leading-6 text-primary-regular-dm bg-nightsky-dark-dm backdrop-opacity-5">
    <div class="flex flex-col items-center w-full">
      <p id="slot-action-text" class="text-xl text-primary-regular-dm font-semibold">-</p>
      <div id="input-slot" class="flex flex-col items-center">
        <label for="slot-text" class="mt-6">Entrez un nom</label>
        <input type="text" id="slot-text" class="mt-2 min-w-72 max-w-72 text-nightsky-dark-dm">
      </div>
      <label for="user-slots" class="mt-6">Choisissez un emplacement</label>

      <select id="user-slots" class="mt-2 min-w-72 max-w-72">
      <!-- Add <option> for each slot from database -->
      <?php for($i = 0; $i < count($workspacesSlots); $i++) { ?>
        <option value=" <?= $i ?> "> <?= $workspacesSlots[$i] ?> </option>
      <?php } ?>
      </select>

      <p class="max-w-72 text-center mt-8 text-wrap <?= isset($_SESSION['is-logged']) ? 'hidden' : '' ?>">
      Connectez-vous pour utiliser cette fonctionnalité
      </p>

      <div class="flex flex-row gap-1 mt-6">
        <button id="delete-user-data" class="btn-default <?= !isset($_SESSION['is-logged']) ? 'hidden' : '' ?>">Supprimer</button>
        <button id="cancel-user-data" class="btn-default">Retour</button>
        <button id="confirm-user-data" class="btn-default <?= !isset($_SESSION['is-logged']) ? 'hidden' : '' ?>">Confirmer</button>
      </div>
    </div>
  </dialog>


  <main class="flex-1">
    <header class="px-2 flex flex-row flex-wrap justify-end items-center">

      <button id="code-reset" class="btn-default">Nettoyer</button>
      <button id="code-save" class="btn-default">Sauver</button>
      <button id="code-load" class="btn-default">Charger</button>
      
    </header>

    <div class="flex flex-col xl:flex-row mx-1">
      <div class="flex md:flex-row flex-col gap-1 w-full">
        <div class="w-full h-[80vh] flex flex-col">

          <div class="flex flex-1 flex-col">
            <div>
              <span class="border-none outline-none bg-nightsky-light-dm px-[3px] pb-1">HTML</span>
            </div>
            <div id="editor-area-html" class="editor flex-1 border-2 border-nightsky-light-dm"></div>
          </div>

          <div class="flex flex-1 flex-col">
            <div>
              <span class="border-none outline-none bg-nightsky-light-dm px-[3px] pb-1">CSS</span>
            </div>
            <div id="editor-area-css" class="editor flex-1 border-2 border-nightsky-light-dm"></div>
          </div>

          <div class="flex flex-1 flex-col">
            <div>
              <span class="border-none outline-none bg-nightsky-light-dm px-[3px] pb-1">JavaScript</span>
            </div>
            <div id="editor-area-javascript" class="editor flex-1 border-2 border-nightsky-light-dm"></div>
          </div>

        </div>

        <div class="flex flex-col w-full h-[80vh]">
          <div>
            <span class="border-none outline-none bg-nightsky-light-dm px-[3px] pb-1">Fenêtre de rendu</span>
          </div>
          <iframe id="output" class="h-full bg-slate-200 border-2 border-nightsky-light-dm" sandbox="allow-same-origin allow-scripts"></iframe>
        </div>
      </div>
    </div>
  </main>

  <script src="../ace-editor/ace.js" type="text/javascript" charset="utf-8"></script>
  <script src="../ace-editor/ext-language_tools.js"></script>

  <script type="module" src="../assets/js/playground.js"></script>

<?php $content = ob_get_clean(); ?>


<?php
ob_start();
include './view/inc/footer.php';
$footer = ob_get_clean();
?>

<?php require('./view/base.php'); ?>