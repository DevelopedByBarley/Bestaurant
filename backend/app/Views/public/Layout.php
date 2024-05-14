<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/public/css/index.css?v=<?= time() ?>">
  <title>Document</title>
</head>

<body class="pr-bg">
  <?php if (COOKIE_MODAL_PERM === 1) : ?>
    <?php include 'app/Views/public/components/Cookie.php' ?>
  <?php endif ?>


  <?= $params["content"] ?>


</body>

</html>