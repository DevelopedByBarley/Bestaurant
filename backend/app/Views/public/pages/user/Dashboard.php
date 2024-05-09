

<?php
  $user = $params['user'] ?? null;
?>

<!-- Add Bootstrap container -->
<div class="container vh-100 d-flex align-items-center justify-content-center mt-8 mt-xxl-0">
  <div class="row">
    <div class="col-12 shadow p-xxl-5">
      <!-- Add profile header -->
        <div class="row">
          <div class="col-md-3">
            <img src="<?= isset($user['fileName']) ? '/public/assets/uploads/images/' . $user['fileName'] : 'https://via.placeholder.com/150' ?>" class="profile-image img-fluid my-3">
          </div>
          <div class="col-md-9 d-flex flex-column justify-content-end">
            <h1>John Doe</h1>
            <p>Email: <?= $user['email'] ?? 'error'?></p>
          </div>
        </div>

      <!-- Add profile info -->
      <div class="profile-info">
        <div class="row">
          <div class="col-md-3">
            <h5>Personal Info</h5>
            <ul class="list-unstyled">
              <li>Gender: Male</li>
              <li>Age: 30</li>
              <li>Location: New York, NY</li>
            </ul>
          </div>
          <div class="col-md-9 mt-4">
            <h5>About Me</h5>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed auctor, magna a ullamcorper laoreet,
              velit nisi varius nisl, eget congue dui ligula eget nisi. Sed auctor, magna a ullamcorper laoreet,
              velit nisi varius nisl, eget congue dui ligula eget nisi.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

