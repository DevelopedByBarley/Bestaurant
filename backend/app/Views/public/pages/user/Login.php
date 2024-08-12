<?php $csrf = $params['csrf'] ?? null ?>


<section class="vh-100 gradient-custom   pr-font">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5 shadow">
        <div class="card   border-0" style="border-radius: 1rem;">
          <div class="card-body p-5 text-center">
            <form action="/user/login" method="POST">
              <?= $csrf->generate() ?>

              <div class="mb-md-5 mt-md-4 pb-5">

                <h2 class="fw-bold mb-2 text-uppercase">USER</h2>
                <p class="-50 mb-5">Please enter your login and password!</p>

                <div class="form-outline form-white mb-4">
                  <input type="email" name="email" data-validator="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                  <label class="form-label" for="typeEmailX">Email</label>
                </div>

                <div class="form-outline form-white mb-4">
                  <input type="password" name="password" class="form-control" data-validator="password" id="exampleInputPassword1">
                  <label class="form-label" for="typePasswordX">Password</label>
                </div>

                <button class="btn btn-outline-light btn-lg px-5" type="submit">Login</button>

              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>