<?php
$meta_tags = $params['meta_tags'] ?? [];
$title = isset($params['title']) ? '- ' . $params['title'] : '';
?>


<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/public/bootstrap/css/bootstrap.css" />
  <link rel="stylesheet" href="/public/css/index.css?v=<?= time() ?>">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <title><?php echo htmlspecialchars(APP_NAME); ?> <?php echo $title ?></title>

  <?php echo isset($meta_tags['description']) ? '<meta name="description" content="' . htmlspecialchars($meta_tags['description']) . '">' : ''; ?>
  <?php echo isset($meta_tags['keywords']) ? '<meta name="keywords" content="' . htmlspecialchars($meta_tags['keywords']) . '">' : ''; ?>
  <?php echo isset($meta_tags['robots']) ? '<meta name="robots" content="' . htmlspecialchars($meta_tags['robots']) . '">' : ''; ?>
  <?php echo isset($meta_tags['og:title']) ? '<meta property="og:title" content="' . htmlspecialchars($meta_tags['og:title']) . '">' : ''; ?>
  <?php echo isset($meta_tags['og:description']) ? '<meta property="og:description" content="' . htmlspecialchars($meta_tags['og:description']) . '">' : ''; ?>
  <?php echo isset($meta_tags['og:image']) ? '<meta property="og:image" content="' . htmlspecialchars($meta_tags['og:image']) . '">' : ''; ?>
  <?php echo isset($meta_tags['og:url']) ? '<meta property="og:url" content="' . htmlspecialchars($meta_tags['og:url']) . '">' : ''; ?>
  <?php echo isset($meta_tags['twitter:card']) ? '<meta name="twitter:card" content="' . htmlspecialchars($meta_tags['twitter:card']) . '">' : ''; ?>
  <?php echo isset($meta_tags['twitter:title']) ? '<meta name="twitter:title" content="' . htmlspecialchars($meta_tags['twitter:title']) . '">' : ''; ?>
  <?php echo isset($meta_tags['twitter:description']) ? '<meta name="twitter:description" content="' . htmlspecialchars($meta_tags['twitter:description']) . '">' : ''; ?>
  <?php echo isset($meta_tags['twitter:image']) ? '<meta name="twitter:image" content="' . htmlspecialchars($meta_tags['twitter:image']) . '">' : ''; ?>
  <?php echo isset($meta_tags['canonical']) ? '<link rel="canonical" href="' . htmlspecialchars($meta_tags['canonical']) . '">' : ''; ?>
  <?php echo isset($meta_tags['hreflang_hu']) ? '<link rel="alternate" hreflang="hu" href="' . htmlspecialchars($meta_tags['hreflang_hu']) . '">' : ''; ?>
  <?php echo isset($meta_tags['hreflang_en']) ? '<link rel="alternate" hreflang="en" href="' . htmlspecialchars($meta_tags['hreflang_en']) . '">' : ''; ?>
</head>

<body class="bg-gray-50 dark-bg-gray-800">
  <?php include 'app/Views/public/components/Alert.php' ?>
  <?php include('app/Views/admin/components/Navbar.php') ?>


  <?= $params["content"] ?>

  <script src="/public/bootstrap/js/bootstrap.bundle.js"></script>
  <script type="module" src="/public/js/getCookie.js?v=<?= time() ?>"></script>
  <script type="module" src="/public/js/uuid.js?v=<?= time() ?>"></script>
  <script src="/public/js/colorTheme.js?v=<?= time() ?>"></script>


  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script type="module" src="/public/js/charts.js?v=<?= time() ?>"></script>

  <?php if (PW_GENERATOR_PERM) : ?><script type="module" src="/public/js/pwGenerator.js?v=<?= time() ?>"></script><?php endif ?>
  <?php if (ADMIN_SERVICE_PERM) : ?><script type="module" src="/public/js/adminSettings.js?v=<?= time() ?>"></script><?php endif ?>
  <?php if (VALIDATORS_PERM) : ?><script type="module" src="/public/js/validators.js?v=<?= time() ?>"></script><?php endif ?>
  <?php if (TOAST_PERM) : ?>
    <?php include 'app/Views/public/components/Toast.php' ?>
    <script src="/public/js/toast.js?v=<?= time() ?>"></script>
  <?php endif ?>
  <?php if (IMG_LOADER_PERM) : ?><script src="/public/js/imgLoader.js?v=<?= time() ?>"></script><?php endif ?>
  <?php if (SKELETON_PERM) : ?>
    <?php include 'app/Views/templates/skeletons/card.skeleton.php' ?>
    <script src="/public/js/skeleton.js?v=<?= time() ?>"></script>
  <?php endif ?>

</body>

</html>