<div class="container-fluid">
    <div class="row">
        <div class="col-12  dark-bg-gray-900">
            <div class="px-4 py-5 px-md-5 text-center text-lg-start ">
                <div class="container">
                    <div class="text-center">
                        <h3>Registration Form example</h3>
                    </div>
                    <div class="row gx-lg-5 align-items-center">
                        <div class="col-lg-6 mb-5 mb-lg-0">
                            <h1 class="my-5 display-3 fw-bold ls-tight">
                                This is a template <br />
                                <span class="purple-500">for form example</span>
                            </h1>
                            <p>
                                Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                Eveniet, itaque accusantium odio, soluta, corrupti aliquam
                                quibusdam tempora at cupiditate quis eum maiores libero
                                veritatis? Dicta facilis sint aliquid ipsum atque?
                            </p>
                        </div>

                        <div class="col-lg-6 mb-5 mb-lg-0">
                            <div class="shadow">
                                <div class="card-body py-5 px-md-5">
                                    <form>
                                        <!-- 2 column grid layout with text inputs for the first and last names -->
                                        <div class="row">
                                            <div class="col-md-6 mb-4">
                                                <div class="form-outline">
                                                    <label class="form-label" for="form3Example1">First name</label>
                                                    <input name="first_name" type="text" id="form3Example1" class="form-control" validators='{
                                                        "name": "first_name",
                                                        "required": true,
                                                        "minLength": 12,
                                                        "maxLength": 50
                                                    }' />
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-4">
                                                <div class="form-outline">
                                                    <label class="form-label" for="form3Example2">Last name</label>
                                                    <input name="last_name" type="text" id="form3Example2" class="form-control" validators='{
                                                        "name": "last_name",
                                                        "required": true,
                                                        "minLength": 12,
                                                        "maxLength": 50
                                                    }' />
                                                </div>
                                            </div>

                                        </div>

                                        <!-- Email input -->
                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="form3Example3">Email address</label>
                                            <input name="email" type="email" id="form3Example3" class="form-control" validators='{
                                                "name": "email",
                                                "required": true,
                                                "email": true,
                                                 "minLength": 12,
                                                "maxLength": 50
                                            }' />
                                        </div>

                                        <!-- Password input -->
                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="form3Example4">Password</label>
                                            <input name="password" type="password" id="form3Example4" class="form-control" validators='{
                                                "name": "password",
                                                "required": true,
                                                 "password": true
                                            }' />
                                        </div>

                                        <!-- Checkbox -->
                                        <div class="form-check d-flex justify-content-center mb-4">
                                            <input class="form-check-input me-2" type="checkbox" value="" id="form2Example33" checked />
                                            <label class="form-check-label" for="form2Example33">
                                                Subscribe to our newsletter
                                            </label>
                                        </div>

                                        <!-- Submit button -->
                                        <div class="text-center">
                                            <button type="submit" class="btn bg-purple-500 hover-bg-purple-600 text-white btn-block mb-4">
                                                Sign up
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<section class="pr-font dark-bg-gray-900">
    <div class="container py-5">
        <div class="text-center">
            <h3>Login Form example</h3>
        </div>
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card  dark-bg-gray-900 shadow border-0" style="border-radius: 1rem;">
                    <div class="card-body p-5 text-center">
                        <form action="/user/login" method="POST">
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