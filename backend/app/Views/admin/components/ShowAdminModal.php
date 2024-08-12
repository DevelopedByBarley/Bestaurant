<div class="modal fade" id="showAdminModal-<?= $current_admin['id'] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <di class="modal-content">
            <div class="modal-header  text-white bg-sky-500 ">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Admin Profil</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex flex-column align-items-center text-center p-3">
                    <img class="rounded-circle mt-5 mb-3" width="150px" src="/public/assets/images/avatars/<?= $current_admin['avatar'] ?>.png">
                    <div class="mt-2"><span class="fw-bold text-xl"><?= $current_admin['name'] ?></span> <span>(Level <?= $level ?>)</span></div><span><?= $current_admin['email'] ?></span><span> </span>
                    <div class="mt-1 badge bg-sky-500  p-2 mt-2">
                        (<?= $current_admin['created_at'] ?>)
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Bezár</button>
            </div>
    </div>
</div>