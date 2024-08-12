<?php
$toast = $_SESSION["toast"] ?? null;
?>

<div class="container">
  <div class="row">
    <div class="col-12">
      <div id="toast-root">
        <?php if (isset($toast)) : ?>
          <div id="toast-data" data-toast='{
            "content": {
              "title": "<?= $toast['title'] ?? null ?>",
              "message": "<?= $toast['message'] ?? null ?>",
              "time": "<?= $toast['time'] ?? null ?>"
            },
            "style": {
              "textColor": "<?= $toast['color'] ?? null ?>",
              "background": "<?= $toast['bg'] ?? null ?>"
            }
          }'>
          </div>
        <?php endif   ?>

      </div>
    </div>
  </div>
</div>


<?php
if (isset($toast)) {
  // Az alert session lejárt, töröljük
  unset($_SESSION['toast']);
}
?>



