<?php
$admin = $params['admin'];
$admin_activities = $params['admin_activities'] ?? [];
$visitors = $params['visitors']  ?? [];
$admin_list = $params['admin_list']  ?? [];
$users = $params['users']  ?? [];
$registrationsChartData = $params['registrationsChartData'] ?? json_encode([]);
$feedbacks = $params['feedbacks'] ?? [];
$feedbackPercentages = $params['feedbackPercentages'] ?? null;

$smileys = [
  1 => '😞',
  2 => '😐',
  3 => '🙂',
  4 => '😊',
  5 => '😄',
];

$barColors = [
  1 => 'bg-red-500',
  2 => 'bg-orange-500',
  3 => 'bg-purple-500',
  4 => 'bg-teal-500',
  5 => 'bg-yellow-500',
];


?>

<?php include('app/Views/admin/components/Header.php') ?>


<main id="admin-dashboard">
  <div class="container-fluid mb-5">
    <div class="row gap-3 d-flex align-items-center justify-content-center">
      <div class="col-12 col-md-5 col-lg-3 col-xl-2 min-h-200 border bg-cyan-500 hover-bg-cyan-700 dark-bg-cyan-700 dark-bg-hover-cyan-600 transition-ease-in-out-300  d-flex align-items-center justify-content-between gray-50 shadow-lg rounded">
        <div>
          <h1><?= $users ? count($users) : 0 ?></h1>
          <p>Regisztráció</p>
        </div>
        <div>
          <i class="fa-solid fa-user-plus text-7xl"></i>
        </div>
      </div>
      <div class="col-12 col-md-5 col-lg-3 col-xl-2 min-h-200 border bg-amber-500 hover-bg-amber-700 dark-bg-amber-700 dark-bg-hover-amber-600 transition-ease-in-out-300  d-flex align-items-center justify-content-between gray-50 shadow-lg rounded">
        <div>
          <h1><?= $visitors ? count($visitors) : 0 ?></h1>
          <p>Látogató</p>
        </div>
        <div>
          <i class="fa-solid fa-chart-simple text-7xl"></i>
        </div>
      </div>
      <div class="col-12 col-md-5 col-lg-3 col-xl-2 min-h-200 border bg-indigo-500 hover-bg-indigo-700 dark-bg-indigo-700 dark-bg-hover-indigo-600 transition-ease-in-out-300 d-flex align-items-center justify-content-between gray-50 shadow-lg rounded">
        <div>
          <h1><?= $feedbacks ? count($feedbacks) : 0 ?></h1>
          <p>Értékelés</p>
        </div>
        <div>
          <i class="fa-solid fa-thumbs-up text-7xl"></i>
        </div>
      </div>
      <div class="col-12 col-md-5 col-lg-3 col-xl-2 min-h-200 border bg-rose-500 hover-bg-rose-700 dark-bg-rose-700 dark-bg-hover-rose-600 transition-ease-in-out-300  d-flex align-items-center justify-content-between gray-50 shadow-lg rounded">
        <div>
          <h1><?= $admin_list ? count($admin_list) : 0 ?></h1>
          <p>Admin</p>
        </div>
        <div>
          <i class="fa-solid fa-user-plus text-7xl"></i>
        </div>
      </div>
    </div>
  </div>


  <div class="container-fluid mb-5 my-5">
    <div class="row gap-3 d-flex align-items-center justify-content-center">

      <div class="col-12 col-md-6 col-lg-6 col-xl-5 min-h-400 border bg-gray-50 dark-bg-gray-900 shadow-lg rounded-4 d-flex align-items-center justify-content-center justify-content-xl-start">
        <div class="admin-settings min-h-300 h-100 d-flex flex-column flex-xl-row align-items-center justify-content-center mx-3">
          <span>
            <img src="/public/assets/images/avatars/<?= $admin['avatar'] ?>.png" alt="" style="width: 200px; height: 200px" class="rounded-circle mx-3" />
          </span>
          <div>
            <p class="mb-0"><span class="fw-bolder text-3xl"><?= $admin['name'] ?></span> <span>(Level <?= $admin['level'] ?>)</span></p>
            <p><?= $admin['email'] ?></p>
            <a href="/admin/settings" class="btn bg-purple-600 hover-bg-purple-700 px-4">
              <span><i class="fa-solid fa-gears text-2xl gray-50"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php if (USER_SERVICE_PERM && SAVING_VISITOR_PERM) : ?>
    <div class="container-fluid px-5 mt-8">
      <div id="registrations-chart-data" data-registrations="<?= htmlspecialchars($registrationsChartData) ?>"></div>
      <div id="visitors-data" data-visitors="<?= htmlspecialchars(json_encode($visitors)) ?>"></div>
      <div class="row">
        <div class="col-12 col-md-10 mx-auto mb-3 col-xl-6  my-5" id="">
          <h4>Registrations chart in line</h4>
          <canvas id="myChart"></canvas>
        </div>

        <div class="col-12 col-md-10 mx-auto mb-3 col-xl-4  my-5">
          <h4>Registrations chart in donut</h4>
          <canvas id="myChart_2"></canvas>
        </div>
      </div>
    </div>
  <?php endif ?>


  <div class="container-fluid px-5">
    <div class="row mt-5 mb-7">
      <div class="col-12 px-5">
        <h1>Progress 2</h1>
        <label>Progress 1</label>
        <div class="progress">
          <div class="progress-bar bg-primary opacity-75" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <label>Progress 2</label>
        <div class="progress">
          <div class="progress-bar bg-secondary opacity-75" role="progressbar" style="width: 30%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <label>Progress 3</label>
        <div class="progress">
          <div class="progress-bar bg-danger opacity-75" role="progressbar" style="width: 40%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <label>Progress 4</label>
        <div class="progress">
          <div class="progress-bar bg-success opacity-75" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <label>Progress 5</label>
        <div class="progress">
          <div class="progress-bar bg-info opacity-75" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <label>Progress 6</label>
        <div class="progress">
          <div class="progress-bar bg-warning opacity-75" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <label>Progress 7</label>
        <div class="progress">
          <div class="progress-bar bg-danger opacity-75" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
      </div>
    </div>
  </div>




  <?php include 'app/Views/admin/pages/Table.php' ?>


</main>