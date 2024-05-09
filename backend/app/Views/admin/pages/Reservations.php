<?php
$data = $params['data'] ?? [];
$reservations = $data['data'];
$totalPages = (int)$data['numOfPage'] ?? 1; // összes oldalszám
$currentDate = $_GET['date'] ?? null;
?>

<div class="container mt-5">
  <div class="row">
    <div class="col-12">
      <?php if ($totalPages === 0) : ?>
        <div class="mt-5 p-5 bg-light rounded text-dark">
          <h1>Nincs egyetlen foglalás sem erre a napra!</h1>
        </div>
      <?php else : ?>
        <div class="row">
          <div class="col-12">
            <div class="mt-4 p-2 text-center bg-light rounded text-dark">
              <h1><?= !isset($currentDate) ? 'Mai foglalások' : "Foglalások <br> <span class='text-info'>$currentDate</span>" ?></h1>
            </div>
          </div>
        </div>
        <div class="row mt-5">
          <div class="col-12 d-flex align-items-center justify-content-center">
            </div>
          </div>
          <div class="table-responsive">
            <table class="table table-striped table-bordered">
              <thead class="bg-dark text-light">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Dátum</th>
                  <th scope="col">Start</th>
                  <th scope="col">Vége</th>
                  <th scope="col">Vendégszám</th>
                  <th scope="col">Órák</th>
                  <th scope="col">Feladás dátuma</th>
                  <th scope="col">Elfogadva</th>
                  <th scope="col">Műveletek</th>
                </tr>
              </thead>
              <tbody>
                
                <?php foreach ($reservations as $index => $reservation) : ?>
                  <tr>
                    <th scope="row"><?= $index + 1 ?></th>
                    <td><?= $reservation['date'] ?? 'Hiba' ?></td>
                    <td><?= $reservation['start'] ?? 'Hiba' ?></td>
                    <td><?= $reservation['end'] ?? 'Hiba' ?></td>
                    <td><?= $reservation['numOfGuests'] ?? 'Hiba' ?></td>
                    <td><?= ($reservation['intervalValue'] / 60 / 60) ?? 'Hiba' ?></td>
                    <td><?= $reservation['createdAt'] ?? 'Hiba' ?></td>
                    <td class="px-5"><?= $reservation['isAccepted'] === 1 ? '<button class="btn btn-primary p-2 rounded">Elfogadva</button>' : '<button class="btn-secondary p-2 rounded">Függőben</button>' ?? 'Hiba' ?></td>
                    <td class="d-flex justify-content-end">
                      <div class="btn-group gap-2">
                        <div class="btn btn-info text-white">
                          Nap megtekintése
                        </div>
                        <div class="btn btn-danger">
                          Törlés
                        </div>
                      </div>
                    </td>
                  </tr>
                  <?php endforeach ?>
                  
                </tbody>
              </table>
              <?php include 'app/Views/public/components/Pagination.php' ?>
            </div>
      <?php endif ?>
    </div>
  </div>
</div>