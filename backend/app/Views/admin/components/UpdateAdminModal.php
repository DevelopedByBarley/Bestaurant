<?php $levels = [1, 2, 3];
$csrf = $params['csrf'] ?? null;

?>

<div class="modal fade" id="updateAdminModal-<?= $current_admin['id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header  text-white bg-warning">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Profil frissítése</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" action="/admin/update">
                    <?= $csrf->generate() ?>

                    <div class="form-group my-2">
                        <input name="current_admin_id" type="hidden" value="<?= $current_admin['id'] ?? '' ?>" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter name " required>
                    </div>

                    <div class="form-group my-2">
                        <label for="exampleInputEmail1">Name</label>
                        <input name="name" type="text" value="<?= $current_admin['name'] ?? '' ?>" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter name " required validators='{
                            "name": "name",
                            "required": true,
                            "minLength": 12,
                            "maxLength": 50,
                            "noSpaces": true

                        }'>
                    </div>
                    <div class="form-outline">
                        <label class="form-label" for="form3Example3">Email address</label>
                        <input name="email" type="email" id="form3Example3" class="form-control" disabled value="<?= $current_admin['email'] ?? '' ?>" />
                    </div>

                    <div class="border p-2 py-3 my-4 rounded-4">

                        <div class="form-check mt-3 mb-4">
                            <input type="checkbox" class="form-check-input changePasswordCheckbox" id="changePasswordCheckbox">
                            <label class="form-check-label" for="changePasswordCheckbox">Jelszó változtatás</label>
                        </div>


                        <div class="form-group my-2">
                            <label for="exampleInputEmail1">Password</label>
                            <input type="text" id="password" name="password" class="password form-control" data-password-compare=1 disabled validators='{
        "name": "password",
        "required": true
    }' />
                            <button type="button" class="d-inline btn border pw-generator">Generate</button>
                        </div>

                        <div class="form-group my-2">
                            <label for="exampleInputEmail1">Repeat password</label>
                            <input type="text" id="repeat" name="repeat" class="form-control" disabled validators='{
        "name": "repeat",
        "comparePw": true
    }' />
                        </div>
                    </div>

                    <?php if ((int)$admin['level'] === 3) : ?>
                        <div class="form-group my-2">
                            <label for="exampleInputPassword1">Level</label>
                            <select class="form-select" name="level" aria-label="Default select example" required>
                                <option value="" selected disabled>Select admin level</option>
                                <?php foreach ($levels as $level) : ?>
                                    <option <?= (int)$current_admin['level'] === $level ? 'selected' : '' ?> value="<?= $level ?>"><?= $level ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    <?php endif ?>
                    <div class="avatars">
                        <div class="row">
                            <label for="avatars" class="my-3">Avatar kiválasztása</label>
                            <?php foreach (AVATARS as $avatar) : ?>
                                <div class="col-2 d-flex align-items-center justify-content-center mb-2">
                                    <div class="form-check form-check-inline image-radio">
                                        <input required class="form-check-input" <?php echo $avatar === $current_admin['avatar'] ? 'checked' : '' ?> type="radio" name="settings_avatar_radio" id="settings_avatar_radio-<?= $current_admin['id'] ?>-<?php echo $avatar; ?>" value="<?php echo $avatar; ?>">
                                        <label class="form-check-label" for="settings_avatar_radio-<?= $current_admin['id'] ?>-<?php echo $avatar; ?>">
                                            <img src="/public/assets/images/avatars/<?php echo $avatar; ?>.png" class="h-45 w-45" alt="<?php echo ucfirst($avatar); ?>">
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button type="submit" class="btn btn-warning text-white">Frissít</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bezár</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>