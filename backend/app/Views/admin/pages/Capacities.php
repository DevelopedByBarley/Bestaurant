<?php
$data = $params['data'] ?? [];
$capacities = $data['data'];
$totalPages = (int)$data['numOfPage'] ?? 1; // összes oldalszám
$currentDate = $_GET['date'] ?? null;
$default_capacity = $params['default_capacity'] ?? 0;
$csfr = $params['csfr'] ?? null
?>

<div class="container mt-5">


  <div class="row">
    <div class="col-12">
      <div class="mt-5 p-2 bg-light rounded text-dark">
        <h1>Kapacitások</h1>
        <p><em>Egy napra beállítható kapacitás , avagy hány embert tud befogadni az adott napra az étterem</em></p>
      </div>
    </div>
  </div>
  <div class="row mt-5">
    <div class="col-12">
      <div class="d-flex gap-3">
        <div class="form-group d-flex flex-column w-25">
          <label for="exampleInputPassword1">Alap kapacitás</label>
          <input type="number" name="default_capacity" min='1' max='20' class="form-control" value="<?= $default_capacity ?? 0 ?>">
        </div>

        <form action="#" method="POST" class="d-flex align-items-end">
          <?php $csfr->generate() ?>
          <button type="submit" class="btn btn-danger text-white">Alapérték frisssításe</button>
        </form>
      </div>
      <small><em>Ez a kapacitás az alapérték, ha csak nem kivételt ad hozzá!</em></small>
    </div>
  </div>

  <div class="row">
    <div class="col-12">
      <div class="mt-4 p-2  rounded text-dark">
        <h1 >Kivételek</h1>
        <div class="table-responsive">
          <table class="table table-striped table-bordered">
            <thead class="bg-dark text-light">
              <tr>
                <th scope="col">#</th>
                <th scope="col">Dátum</th>
                <th scope="col">Kapacitás</th>
                <th scope="col">Létrehozva</th>
                <th scope="col">Műveletek</th>
              </tr>
            </thead>
            <tbody>

              <?php foreach ($capacities as $index => $capacity) : ?>
                <tr>
                  <th scope="row"><?= (int)$index + 1 ?></th>
                  <td><?= $capacity['date'] ?? 'Hiba' ?></td>
                  <td>
                    <form action="#" class="d-flex gap-5">
                      <?php $csfr->generate() ?>
                      <input type="number" name="capacity" min="1" max="20" class="w-100" value="<?= $capacity['capacity'] ?? 'Hiba' ?>" id="">
                      <button type="submit" class="btn btn-warning text-white">
                        Frissít
                      </button>
                    </form>
                  </td>
                  <td><?= $capacity['created_at'] ?? 'Hiba' ?></td>
                  <td class="d-flex justify-content-start">
                    <div class="btn-group gap-2">

                      <form action="#">
                        <?php $csfr->generate() ?>
                        <button type="submit" class="btn btn-danger">
                          Törlés
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              <?php endforeach ?>

            </tbody>
          </table>
        </div>
        <?php include 'app/Views/public/components/Pagination.php' ?>

      </div>
    </div>
  </div>
</div>