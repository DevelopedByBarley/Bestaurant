<header id="admin-dashboard-header">


    <div class="section bg-gray-600 dark-bg-gray-900 gray-50">
        <div class="container" id="header">
            <div class="row">
                <div class="col-12 p-5">
                    <h1 class="text-6xl"><span class="mx-3 d-none d-lg-inline"><i class="fa-solid fa-sliders"></i></span>Dashboard</h1>
                    <p> Example dashboard overview and content summary</p>
                </div> <!-- End col -->
            </div> <!-- End row -->
        </div>
    </div>

    <div class="container-fluid mb-5" style="margin-top: -2.5rem;">
        <div class="row gap-3 d-flex align-items-center justify-content-center">
            <div class="col-12  col-md-8 col-lg-4 col-xl-3 min-h-400 max-h-400 border bg-gray-50 dark-bg-gray-900 shadow-lg rounded d-flex align-items-center justify-content-center flex-column text-center">
                <div>
                    <h1>Welcome to SB Admin!</h1>
                    <p>
                        Browse our fully designed UI toolkit! Browse our prebuilt app pages, components, and utilites, and be sure to look at our full documentation!
                    </p>
                </div>
            </div>
            <div class="col-12 col-md-5 col-lg-4 col-xl-3 min-h-400 max-h-400 border bg-gray-50 dark-bg-gray-900 shadow-lg rounded overflow-y-scroll overflow-x-hidden">
                <div class="bg-gray-100 dark-bg-gray-800">
                    <h4 class="py-3">Aktivitások</h4>
                </div>
                <div>
                    <!-- Section: Timeline -->
                    <?php if (isset($admin_activities) && !empty($admin_activities)) : ?>
                        <section class="py-2">
                            <ul class="timeline">
                                <?php foreach ($admin_activities as $activity) : ?>
                                    <li class="timeline-item mb-3 d-flex gap-3">
                                        <img src="/public/assets/images/avatars/<?= $activity['avatar'] ?>.png" alt="" style="width: 30px; height: 30px" class="rounded-circle" />
                                        <div>
                                            <p class="text-muted m-0 fw-bold"><?= $activity['created_at'] ?></p>
                                            <h6 class="m-0 fw-light"><span class="green-500 fw-bold"><?= $activity['admin_name'] ?> </span><?= $activity['content'] ?></h6>
                                        </div>
                                    </li>
                                <?php endforeach ?>

                            </ul>
                        </section>
                    <?php else : ?>
                        <h1>No activities here..</h1>
                    <?php endif ?>
                    <!-- Section: Timeline -->
                </div>
            </div>
            <div class="col-12 col-md-5 col-lg-4 col-xl-3 min-h-400 border bg-gray-50 dark-bg-gray-900 shadow-lg rounded">
                <div class="w-100">
                    <div class="bg-gray-100 dark-bg-gray-800">
                        <h4 class="py-3">Összes értékelés: <?= count($feedbacks)?></h4>
                    </div>
                    <?php foreach ($feedbackPercentages as $key => $percentage) : ?>
                        <div class="mt-3">
                            <label class="text-xl"><?= $smileys[$key] ?></label>
                            <div class="progress">
                                <div class="progress-bar <?= $barColors[$key] ?> opacity-75" role="progressbar" style="width: <?= $percentage ?>%" aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100"><?= $percentage ?>%</div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>




</header>