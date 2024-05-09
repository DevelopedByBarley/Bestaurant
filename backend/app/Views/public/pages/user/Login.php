<?php $csfr = $params['csfr'] ?? null ?>

<div class="container vh-100">
  <div class="row h-100 d-flex align-items-center justify-content-center flex-column">
    <div class="col-12 col-lg-6">
      <div class="card border-0 p-xl-5 rounded-lg">
        <div class="title mb-4">
          <h1 class="text-center pr-font">User Login</h1>
        </div>
        <form action="/user/login" method="POST">
          <div>
            <label for="exampleInputEmail1" class="form-label">Email address</label>
            <input type="email" name="email" data-validator="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
          </div>
          <div class="mb-3">
            <label for="exampleInputPassword1" class="form-label">Password</label>
            <input type="password" name="password" class="form-control" data-validator="password" id="exampleInputPassword1">
          </div>
          <?= $csfr->generate() ?>
          <button type="submit" class="btn btn-primary">Submit</button>
        </form>
      </div>
    </div>
  </div>
</div>
</div>