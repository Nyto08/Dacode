<nav class="flex flex-col lg:flex-row gap-y-4 justify-between items-center px-6 py-3 bg-nightsky-regular-dm">
  <a href="<?= APP_ROOT ?>/accueil" class="flex flex-row items-center gap-3">
    <img src="../assets/img/logo.svg" alt="logo pedacode" width="38">
    <span class="text-xl font-semibold">DaCode</span>
  </a>
  <ul class="flex flex-row flex-wrap justify-center items-center gap-x-6 gap-y-2">
    <li><a href="<?= APP_ROOT ?>/accueil">Accueil</a></li>
    <li><a href="<?= APP_ROOT ?>/monParcours">Portfolio</a></li>
    <li><a href="<?= APP_ROOT ?>/playground">Playground</a></li>
  </ul>

  <div class="relative">
    <a class="font-bold"
    href="<?= !isset($_SESSION['is-logged']) ? APP_ROOT . '/login' : '#' ?>"
    onclick="toggleProfileMenu()">
      <i class="fa-<?= isset($_SESSION['is-logged']) ? 'solid' : 'regular' ?> fa-user<?= (isset($_SESSION['is-logged']) && $_SESSION['role'] === 'admin') ? '-pen' : '' ?> fa-xl mr-2">
      </i>
      <?= isset($_SESSION['is-logged']) ? $_SESSION['pseudo'] : 'Se connecter' ?>
    </a>

    <!-- N'affiche pas le menu déroulant du user si il n'est pas connecté -->
    <?php if (isset($_SESSION['is-logged'])): ?>  
    <div id="profile-menu" class="absolute hidden overflow-auto lg:right-[-1.5rem] top-[2.25rem] whitespace-nowrap divide-y divide-nightsky-light-dm rounded-x-0 bg-nightsky-regular-dm shadow-lg font-sans border-x-2 border-b-2 border-solid border-nightsky-light-dm" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
      <div role="none">
        <!-- Active: "bg-gray-100 text-gray-900", Not Active: "text-gray-700" -->
        <a href="<?= APP_ROOT ?>/my-account" class="block px-4 py-2" role="menuitem" tabindex="-1" id="menu-item-0">Mon compte</a>
        
      </div>
      <div role="none">
        <a href="<?= APP_ROOT ?>/logout" class="block px-4 py-2" role="menuitem" tabindex="-1" id="menu-item-6">Se déconnecter</a>
      </div>
    </div>
    <?php endif; ?>
    
  </div>

</nav>

<script>
  function toggleProfileMenu() {
    $profileMenu = document.getElementById('profile-menu');

    if ($profileMenu.classList.contains('hidden')) {
        $profileMenu.classList.remove('hidden');
    } else {
        $profileMenu.classList.add('hidden');
    }
  }
</script>