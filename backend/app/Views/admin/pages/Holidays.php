<?php
$opening_hours = $params['opening_hours'] ?? null;
?>

<?php
$data = $params['data'] ?? [];
$holidays = $data['data'];
$totalPages = (int)$data['numOfPage'] ?? 1; // összes oldalszám
$currentDate = $_GET['date'] ?? null;
$default_capacity = $params['default_capacity'] ?? 0;
$csfr = $params['csfr'] ?? null
?>

<div class="container mt-5">


  <div class="row">
    <div class="col-12">
      <div class="mt-5 p-2 bg-light rounded text-dark">
        <h1>Ünnepnapok és kivételek</h1>
        <p><em>Nyitvatartási idő mellett lehetősége van ünnepnapokat és kivételeket hozzáadni, amiben zárva tarthatja az éttermet vagy hozzáadhat egyéb kivételes nyitvatartási időket</em></p>
        <b>A leírást a felhasználók üzenetként fogják látni az adott napon.</b>
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
                <th scope="col">Dátum</th>
                <th scope="col">Ünnepnap</th>
                <th scope="col">Nyitás</th>
                <th scope="col">Zárás</th>
                <th scope="col">Leírás</th>
                <th scope="col">Létrehozás dátuma</th>
                <th scope="col">Műveletek</th>
              </tr>
            </thead>
            <tbody>

              <?php foreach ($holidays as $index => $holiday) : ?>
                <tr>
                  <th scope="row"><?= (int)$index + 1 ?></th>
                  <td><?= $holiday['date'] ?? 'Hiba' ?></td>
                  <td><?= $holiday['isHoliday'] ? 'Igen' : 'Nem' ?? 'Hiba' ?></td>
                  <td><?= $holiday['open'] ?? 'Zárva' ?></td>
                  <td><?= $holiday['close'] ?? 'Zárva' ?></td>
                  <td><?= $holiday['description'] ?? 'Hiba' ?></td>
                  <td><?= $holiday['created_at'] ?? 'Hiba' ?></td>
                  <td>
                    <div class="btn-group gap-2">
                      <div class="btn btn-danger">Törlés</div>
                      <div class="btn btn-warning text-white">Frissítés</div>
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