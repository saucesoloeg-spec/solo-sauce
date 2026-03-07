@extends('layouts.auth')

@section('content')
<div class="container position-sticky z-index-sticky top-0">
    <div class="row">
        <div class="col-12">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg blur border-radius-sm top-0 z-index-3 shadow position-absolute my-3 py-2 start-0 end-0 mx-4">
                <div class="container-fluid px-1">
                    <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon mt-2">
                            <span class="navbar-toggler-bar bar1"></span>
                            <span class="navbar-toggler-bar bar2"></span>
                            <span class="navbar-toggler-bar bar3"></span>
                        </span>
                    </button>
                    <div class="collapse navbar-collapse" id="navigation">
                        <ul class="navbar-nav mx-auto ms-xl-auto">
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center me-2 " href="{{ route('admin.get.login') }}">
                                    <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="opacity-6 me-1">
                                    <path fill-rule="evenodd" d="M15.75 1.5a6.75 6.75 0 00-6.651 7.906c.067.39-.032.717-.221.906l-6.5 6.499a3 3 0 00-.878 2.121v2.818c0 .414.336.75.75.75H6a.75.75 0 00.75-.75v-1.5h1.5A.75.75 0 009 19.5V18h1.5a.75.75 0 00.53-.22l2.658-2.658c.19-.189.517-.288.906-.22A6.75 6.75 0 1015.75 1.5zm0 3a.75.75 0 000 1.5A2.25 2.25 0 0118 8.25a.75.75 0 001.5 0 3.75 3.75 0 00-3.75-3.75z" clip-rule="evenodd" />
                                    </svg>
                                    Sign In
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- End Navbar -->
        </div>
    </div>
</div>
<main class="main-content  mt-0">
    <section>
        <div class="page-header min-vh-100">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="position-absolute w-40 top-0 start-0 h-100 d-md-block d-none">
                            <div class="oblique-image position-absolute d-flex fixed-top ms-auto h-100 z-index-0 bg-cover me-n8" style="background-image:url('../assets/img/image-sign-up.jpg')">
                                <div class="my-auto text-start max-width-350 ms-7">
                                    <h1 class="mt-3 text-white font-weight-bolder">Reset Your Password !</h1>
                                </div>
                                <div class="text-start position-absolute fixed-bottom ms-7">
                                    <h6 class="text-white text-sm mb-5">Copyright © 2024 Corporate Solo Sauce System.</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4 d-flex flex-column mx-auto">
                        <div class="card card-plain mt-6">
                            <div class="card-header pb-0 text-left bg-transparent">
                                <h3 class="font-weight-black text-dark display-6">Reset Password</h3>
                                <p class="mb-0">Enter your new password, and Don't forget it again !</p>
                            </div>
                            <div class="card-body">
                                <form role="form" action="{{ route('reset.password') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="token" value="{{ request()->route('token') }}">
                                    <label>Email Address</label>
                                    <div class="mb-3">
                                        <input type="email" name="email" class="form-control" placeholder="Enter your email address" aria-label="Email" aria-describedby="email-addon" required>
                                    </div>
                                    <label>Password</label>
                                    <div class="mb-3">
                                        <input type="password" name="password" class="form-control" placeholder="Create a password" aria-label="Password" aria-describedby="password-addon" required>
                                    </div>
                                    <label>Confirm Password</label>
                                    <div class="mb-3">
                                        <input type="password" name="password_confirmation" class="form-control" placeholder="Create a password" aria-label="Password" aria-describedby="password-addon" required>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-dark w-100 mt-4 mb-3"> Reset </button>
                                        <!-- <button type="button" class="btn btn-white btn-icon w-100 mb-3">
                                            <span class="btn-inner--icon me-1">
                                            <img class="w-5" src="../assets/img/logos/google-logo.svg" alt="google-logo" />
                                            </span>
                                            <span class="btn-inner--text">Sign up with Google</span>
                                        </button> -->
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                <p class="mb-4 text-xs mx-auto">
                                    Already have an account?
                                    <a href="javascript:;" class="text-dark font-weight-bold">Sign in</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<!--   Core JS Files   -->
<script src="../assets/js/core/popper.min.js"></script>
<script src="../assets/js/core/bootstrap.min.js"></script>
<script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
<script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>
<script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
        var options = {
        damping: '0.5'
        }
        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
</script>
<!-- Github buttons -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<!-- Control Center for Corporate UI Dashboard: parallax effects, scripts for the example pages etc -->
<script src="../assets/js/corporate-ui-dashboard.min.js?v=1.0.0"></script>
@stop