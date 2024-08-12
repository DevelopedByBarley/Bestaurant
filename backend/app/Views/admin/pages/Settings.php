<?php
$admin = $params['admin'] ?? null;
$data = $params['data'] ?? [];
$admin_list = $data['pages'] ?? [];
$level = $admin['level'] ?? null;
?>

<div class="container-fluid">
    <div class="row dark-bg-gray-900 min-h-95 py-5">
        <div class="col-12 shadow dark-bg-gray-800 py-5 <?= (int)$level === 3 ? 'col-lg-3 offset-xl-2' : '' ?>">
            <div class="d-flex flex-column align-items-center text-center p-3 pt-5">
                <img class="rounded-circle mt-5" width="150px" src="/public/assets/images/avatars/<?= $admin['avatar'] ?>.png">
                <div class="mt-2"><span class="fw-bold text-xl"><?= $admin['name'] ?></span> <span>(Level <?= $level ?>)</span></div><span><?= $admin['email'] ?></span><span> </span>
                <div class="mt-1 badge bg-sky-500  p-2 mt-2">
                    (<?= $admin['created_at'] ?>)
                </div>
            </div>

            <div class="d-flex gap-3 align-items-center justify-content-center">
                <button data-bs-toggle="modal" data-bs-target="#adminSettingsModal" class="btn bg-orange-500 hover-bg-orange-600 text-white  profile-button border-0 py-2" type="button">Update Profile</button>
                <button <?= (int)$admin['level'] < 3 ? 'disabled' : '' ?> data-bs-toggle="modal" data-bs-target="#addAdminModal" class="btn bg-purple-500 hover-bg-purple-600 text-white  profile-button border-0  py-2" type="button"><span class="px-3 p-1 add-experience"><i class="fa fa-plus"></i>&nbsp;Admin</span></button>
            </div>

        </div>
        <?php if ((int)$level === 3) : ?>
            <?php if (!empty($admin_list)) : ?>
                <div class="col-12 col-lg-8 col-xl-7">
                    <div class="d-flex p-5 my-5 align-items-start justify-content-center flex-column gap-3 table-responsive">
                        <table class="table align-middle mb-0 rounded shadow ">
                            <thead class="bg-teal-500">
                                <tr>
                                    <th>Name</th>
                                    <th>Level</th>
                                    <th>Created at</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($admin_list as $current_admin) : ?>
                                    <?php if ($current_admin['id'] !== $admin['id']) : ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="/public/assets/images/avatars/<?= $current_admin['avatar'] ??  'https://mdbootstrap.com/img/new/avatars/6.jpg' ?>.png" alt="" style="width: 45px; height: 45px" class="rounded-circle" />
                                                    <div class="ms-3">
                                                        <p class="fw-bold mb-1"><?= htmlspecialchars($current_admin['name']) ?></p>
                                                        <p class="text-muted mb-0"><?= htmlspecialchars($current_admin['email']) ?></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge rounded-pill d-inline
                                    <?php
                                        switch ((int)$current_admin['level']) {
                                            case 1:
                                                echo 'bg-cyan-500';
                                                break;
                                            case 2:
                                                echo 'bg-orange-500';
                                                break;
                                            case 3:
                                                echo 'bg-red-500';
                                                break;
                                            default:
                                                echo 'bg-sky-500';
                                        }
                                    ?> ">
                                                    Level <?= htmlspecialchars($current_admin['level']) ?>
                                                </span>
                                            </td>
                                            <td><?= htmlspecialchars($current_admin['created_at']) ?></td>
                                            <td>
                                                <div class="btn-group gap-2">
                                                    <button type="button" class="btn btn-rounded btn-sm fw-bold bg-sky-500 text-white" data-bs-toggle="modal" data-bs-target="#showAdminModal-<?= $current_admin['id'] ?>">Show</button>
                                                    <?php if ((int)$current_admin['level'] !== 3) : ?>
                                                        <button type="button" class="btn btn-rounded btn-sm fw-bold bg-yellow-500 text-white" data-bs-toggle="modal" data-bs-target="#updateAdminModal-<?= $current_admin['id'] ?>">Edit</button>
                                                        <button type="button" class="btn btn-rounded btn-sm fw-bold bg-red-500 text-white" data-bs-toggle="modal" data-bs-target="#deleteAdminModal-<?= $current_admin['id'] ?>">Delete</button>
                                                    <?php endif ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif ?>
                                <?php endforeach; ?>
                            </tbody>
                            <?php include 'app/Views/public/components/Pagination.php' ?>
                        </table>
                    </div>
                </div>
            <?php endif ?>

        <?php endif ?>

    </div>
</div>

<?php foreach ($admin_list as $current_admin) : ?>
    <?php include 'app/Views/admin/components/showAdminModal.php' ?>
    <?php include 'app/Views/admin/components/updateAdminModal.php' ?>
    <?php include 'app/Views/admin/components/DeleteAdminModal.php' ?>
<?php endforeach ?>


<?php include 'app/Views/admin/components/AdminSettingsModal.php' ?>
<?php include 'app/Views/admin/components/AddAdminModal.php' ?>