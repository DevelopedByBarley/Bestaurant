<?php $csfr = $params['csfr'] ?? null?>

<div class="container-fluid vh-100">
  <div class="row d-flex justify-content-center align-items-center h-100">
    <div class="col-lg-6 col-md-8">
      <div class="card shadow-lg border-0 p-xl-5 rounded-lg">
        <div class="card-header bg-white">
          <h3 class="text-center font-weight-light my-4">Admin Login</h3>
        </div>
        <div class="card-body">
          <form action="/admin/login" method="POST">
            <?= $csfr->generate() ?>
            <div class="form-group">
              <label class="small mb-1" for="inputEmailAddress">Email Address</label>
              <input class="form-control py-2" id="inputEmailAddress" type="text" placeholder="Enter email address" name="name">
            </div>
            <div class="form-group">
              <label class="small mb-1" for="inputPassword">Password</label>
              <input class="form-control py-2" id="inputPassword" type="password" placeholder="Enter password" name="password">
            </div>
            <div class="form-group d-flex align-items-center justify-content-between mt-4 mb-0">
              <button type="submit" class="btn btn-outline-dark">Login</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>