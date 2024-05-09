<?php
$data = $params['data'] ?? [];
$admins = $data['data'];
$totalPages = (int)$data['numOfPage'] ?? 1; // összes oldalszám
$currentDate = $_GET['date'] ?? null;
$default_capacity = $params['default_capacity'] ?? 0;
$csfr = $params['csfr'] ?? null ?>

<div class="container mt-5">


  <div class="row">
    <div class="col-12">
      <div class="mt-5 p-2 bg-light rounded text-dark">
        <h1>Adminok listája</h1>
        <p><em>Áttekintheti vagy kezelheti az adminok listáját ha rendelkezik megfelelő jogosultságokkal!</em></p>
      </div>
    </div>
  </div>


  <div class="row">
    <div class="col-12">
      <div class="mt-4 p-2  rounded text-dark">
        <div class="table-responsive">
          <table class="table table-striped table-bordered">
            <thead class="bg-dark text-light">
              <tr>
                <th scope="col">#</th>
                <th scope="col">Id</th>
                <th scope="col">Név</th>
                <th scope="col">Szint</th>
                <th scope="col">Létrehozva</th>
                <th scope="col">Műveletek</th>
              </tr>
            </thead>
            <tbody>

              <?php foreach ($admins as $index => $admin) : ?>
                <tr>
                  <th scope="row"><?= (int)$index + 1 ?></th>
                  <td><?= $admin['adminId'] ?? 'Hiba' ?></td>
                  <td><?= $admin['name'] ?? 'Hiba' ?></td>
                  <td><?= $admin['level'] ?? 'Hiba' ?></td>
                  <td><?= $admin['created_at'] ?? 'Hiba' ?></td>
                  <td class="d-flex justify-content-start">
                    <div class="btn-group">
                      <div class="btn btn-danger text-white">Törlés</div>
                    </div>
                  </td>
                </tr>
              <?php endforeach ?>

            </tbody>
          </table>
          <?php include 'app/Views/public/components/Pagination.php' ?>
        </div>
      </div>
    </div>
  </div>
</div>