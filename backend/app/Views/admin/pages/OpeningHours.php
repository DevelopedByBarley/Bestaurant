<?php
$opening_hours = $params['opening_hours'] ?? null;
?>

<div class="container mt-5">


  <div class="row">
    <div class="col-12">
      <div class="mt-5 p-2 bg-light rounded text-dark">
        <h1>Nyitvatartási idő</h1>
        <p><em>Változtathatja az étterem nyitvatartási idejét. Ezáltal a felhasználó ezen időkereteken belül foglalhat asztalt kapacitás alapján.</em></p>
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
                <th scope="col">Nap</th>
                <th scope="col">Nyitás</th>
                <th scope="col">Zárás</th>
                <th scope="col">Műveletek</th>
              </tr>
            </thead>
            <tbody>

              <?php foreach ($opening_hours as $index => $opening) : ?>
                <tr>
                  <th scope="row"><?= (int)$index + 1 ?></th>
                  <td><?= $opening['day'] ?? 'Hiba' ?></td>
                  <td><?= $opening['open'] ?? 'Hiba' ?></td>
                  <td><?= $opening['close'] ?? 'Hiba' ?></td>
                  <td class="d-flex justify-content-start">
                    <div class="btn-group">
                      <div class="btn btn-warning text-white">Módosítás</div>
                    </div>
                  </td>
                </tr>
              <?php endforeach ?>

            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>