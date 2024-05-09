<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/public/bootstrap/css/bootstrap.css" />
  <link rel="stylesheet" href="/public/css/index.css?v=<?= time() ?>">
  <title>Document</title>
</head>

<body class="pr-bg">
  <?php if (COOKIE_MODAL_PERM === 1) : ?>
    <?php include 'app/Views/public/components/Cookie.php' ?>
  <?php endif ?>


  <?= $params["content"] ?>


  <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
  <script src="/public/bootstrap/js/bootstrap.bundle.js"></script>
  <!--   <script src="/public/js/getCookie.js"></script>-->

</body>

</html>