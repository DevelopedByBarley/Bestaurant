<?php $csrf = $params['csrf'] ?? null ?>

<section class="vh-100 gradient-custom pr-font">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5 shadow">
        <div class="card  border-0" style="border-radius: 1rem;">
          <div class="card-body p-5 text-center ">
            <form action="/user/register" method="POST" enctype="multipart/form-data">
              <?= $csrf->generate() ?>

              <div class="mb-md-5 mt-md-4 pb-5">

                <h2 class="fw-bold mb-2 text-uppercase">USER REGISTER</h2>
                <p class="mb-5">Please enter your login and password!</p>

                <div class="form-outline form-white mb-4">
                  <input type="email" name="email" class="form-control" id="exampleInputEmail1" validators='{
                    "name": "email",
                    "required": true,
                    "email": true,
                    "minLength": 12,
                    "maxLength": 50
                    }' aria-describedby="emailHelp">
                  <label class="form-label" for="typeEmailX">Email</label>
                </div>

                <div class="form-outline form-white mb-4">
                  <input type="password" name="password" validators='{
                    "name": "password",
                    "required": true,
                    "password": true
                    }' class="form-control" id="exampleInputPassword1">
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