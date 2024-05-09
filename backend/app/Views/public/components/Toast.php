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





<style>
  #toast-root {
    position: fixed;
    right: 20px;
    top: 100px;
  }

  .toast {
    position: relative;
    animation: toastIn;
    animation-duration: .5s;
    cursor: pointer;
  }

  @keyframes toastIn {
    0% {
      transform: translateX(200%);
    }

    100% {
      transform: translateX(0);
    }
  }
</style>