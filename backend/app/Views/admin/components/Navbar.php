<?php $csfr = $params['csfr'] ?? null;
$count_of_reservations = $params['count_of_reservations'] ?? null;
?>


<nav class="navbar navbar-expand-lg navbar-dark bg-secondary fixed-top pr-font" id="navbar">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0 mt-2 mt-xl-0 p-1">
        <li class="nav-item">
          <a href="/admin/reservations" class="nav-link btn btn-primary text-white">
            Foglalások <span class="badge badge-light bg-danger"><?= $count_of_reservations !== 0 ? $count_of_reservations : ''?></span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="/admin/capacities">Kapacitás</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="/admin/opening-hours">Nyitvatartás</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="/admin/holidays">Ünnepek</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="/admin/admin-list">Adminok</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="/admin/menu">Étlap</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="/admin/drinks">Itallap</a>
        </li>
      </ul>
      <?php if (isset($_SESSION['adminId'])) : ?>
        <div class="btn-group">
          <form action="/admin/logout" method="POST">
            <?= $csfr->generate() ?>

            <button class="btn btn-danger" type="submit">Kijelentkezés</button>
          </form>
        </div>
      <?php endif ?>
    </div>
  </div>
</nav>