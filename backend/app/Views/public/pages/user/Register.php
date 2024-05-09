<?php $csfr = $params['csfr'] ?? null ?>


<div class="container vh-100">
  <div class="row h-100 d-flex align-items-center justify-content-center flex-column">
    <div class="col-12 col-lg-6 p-xxl-5">
      <div class="title mb-4">
        <h1 class="pr-font text-center">User Register</h1>
      </div>
      <form action="/user/register" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label">Email address</label>
          <input type="email" name="email" class="form-control" id="exampleInputEmail1" data-validators='{
          "name": "email",
          "required": true,
          "email": true,
          "minLength": 12,
          "maxLength": 50
          }' aria-describedby="emailHelp">
        </div>
        <div class="mb-3">
          <label for="exampleInputPassword1" class="form-label">Password</label>
          <input type="password" name="password" data-validators='{
          "name": "password",
          "required": true,
          "password": true
          }' class="form-control" id="exampleInputPassword1">
        </div>
        <div class="mb-3">
          <label class="form-label">File upload</label>
          <input class="form-control form-control-sm" type="file" name="file">
        </div>




        <?= $csfr->generate() ?>



        <div class="mb-3 form-check">
          <input type="checkbox" class="form-check-input" id="exampleCheck1">
          <label class="form-check-label" for="exampleCheck1">Check me out</label>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
      </form>
    </div>
  </div>
</div>
</div>
