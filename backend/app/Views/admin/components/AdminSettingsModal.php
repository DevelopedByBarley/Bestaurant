<div class="modal fade" id="adminSettingsModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header  text-white bg-orange-600">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Profil frissítése</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" enctype="multipart/form-data" action="/admin/update">
                    <?= $csrf->generate() ?>

                    <div class="form-group my-2">
                        <label for="exampleInputEmail1">Name</label>
                        <input name="name" type="text" value="<?= $admin['name'] ?? '' ?>" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter name " required validators='{
                            "name": "name",
                            "required": true,
                            "minLength": 12,
                            "maxLength": 50,
                            "noSpaces": true
                        }'>
                    </div>
                    <div class="form-outline">
                        <label class="form-label" for="form3Example3">Email address</label>
                        <input name="email" type="email" id="form3Example3" class="form-control" disabled value="<?= $admin['email'] ?? '' ?>" />
                    </div>

                    <div class="border p-2 py-3 my-4 rounded-4">

                        <div class="form-check mt-3 mb-4">
                            <input type="checkbox" class="form-check-input changePasswordCheckbox" id="changePasswordCheckbox">
                            <label class="form-check-label" for="changePasswordCheckbox">Jelszó változtatás</label>
                        </div>

                        <div class="form-group my-2">
                            <label for="exampleInputEmail1">Prev password</label>
                            <input type="text" id="prev_password" name="prev_password" class="form-control" disabled validators='{
                                "name": "prev_password",
                                "required": true
                            }' />
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

                    <div class="avatars">
                        <div class="row">
                            <label for="avatars" class="my-3">Avatar kiválasztása</label>
                            <?php foreach (AVATARS as $avatar) : ?>
                                <div class="col-2 d-flex align-items-center justify-content-center mb-2">
                                    <div class="form-check form-check-inline image-radio">
                                        <input required class="form-check-input" <?php echo $avatar === $admin['avatar'] ? 'checked' : '' ?> type="radio" name="settings_avatar_radio" id="settings_avatar_radio-<?php echo $avatar; ?>" value="<?php echo $avatar; ?>">
                                        <label class="form-check-label" for="settings_avatar_radio-<?php echo $avatar; ?>">
                                            <img src="/public/assets/images/avatars/<?php echo $avatar; ?>.png" class="h-45 w-45" alt="<?php echo ucfirst($avatar); ?>">
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button type="submit" class="btn bg-orange-500 hover-bg-orange-600">Elküld</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bezár</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>