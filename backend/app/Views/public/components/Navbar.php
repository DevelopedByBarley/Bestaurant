<?php $csfr = $params['csfr'] ?? null ?>


<nav class="navbar navbar-expand-lg navbar-light sc-bg fixed-top pr-font" id="navbar">
  <div class="container-fluid">
    <a class="navbar-brand text-white" href="/">Brand</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link text-white" href="/">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" href="/admin">Admin</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Dropdown
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item text-dark">Hello</a></li>
            <li>
              <hr class="dropdown-divider">
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link disabled text-white" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
        </li>
      </ul>
      <?php if (isset($_SESSION['userId'])) : ?>
        <div class="btn-group">
          <form action="/user/logout" method="POST">
            <?= $csfr->generate() ?>

            <button class="btn btn-danger" type="submit">Logout</button>
          </form>
        </div>
      <?php else : ?>
          <a href="/user/register" class="btn btn-outline-success m-1" type="submit">Register</a>
          <a href="/user/login" class="btn btn-outline-info m-1" type="submit">Login</a>
      <?php endif ?>
    </div>
  </div>
</nav>