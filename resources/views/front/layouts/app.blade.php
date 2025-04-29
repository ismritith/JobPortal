<!DOCTYPE html>
<html class="no-js" lang="en-AU">
<head>
	<meta charset="UTF-8">
	<title>Elevate Job | Find Best Jobs</title>

	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1, user-scalable=no">
	<meta name="HandheldFriendly" content="True">
	<meta name="pinterest" content="nopin">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<!-- Stylesheets -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">

	<!-- Favicon -->
	<link rel="shortcut icon" type="image/x-icon" href="#">

</head>
<body data-instant-intensity="mousedown">

<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow py-3">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">Elevate Workforce Solutions</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-0 ms-sm-0 me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="{{ route('home') }}">Home</a>
                    </li>	
    
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="{{ route('jobs') }}">Find Jobs</a>
                    </li>	
                    
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="jobs.html">Quick Apply </a>
                    </li>	
    
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="jobs.html">Live Chat Support</a>
                    </li>	
    
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="jobs.html">Blog/Insights </a>
                    </li>	
                </ul>		
                
                @if (!Auth::check())
                    <a class="btn btn-outline-primary me-2" href="{{ route('account.login') }}" type="submit">Login</a>			
                @else
                    @if (Auth::user()->role == 'admin')
                        <a class="btn btn-outline-primary me-2" href="{{ route('admin.dashboard') }}" type="submit">Admin</a>				
                    @endif
                    <a class="btn btn-outline-primary me-2" href="{{ route('account.profile') }}" type="submit">Account</a>			
                @endif
    
                <a class="btn btn-primary" href="post-job.html" type="submit">Post a Job</a>
            </div>
        </div>
    </nav>
</header>


@yield('main')

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title pb-0" id="exampleModalLabel">Change Profile Picture</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="profilePicForm" name="profilePicForm" action="" method="post">
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Profile Image</label>
                <input type="file" class="form-control" id="image"  name="image">
				<p class="text-danger" id="image-error"></p>
            </div>
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary mx-3">Update</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
            
        </form>
      </div>
    </div>
  </div>
</div>

{{-- <footer class="bg-dark py-3 bg-2">
<div class="container">
    <p class="text-center text-white pt-3 fw-bold fs-6">© 2025 ELEVATE JOB, all right reserved</p>
</div>
</footer>  --}}

<!-- Footer Section -->
<footer class="bg-dark text-white pt-5 pb-4">
    <div class="container">
        <div class="row">
            <!-- Column 1: Logo & About -->
            <div class="col-md-3 mb-4">
                <h5 class="text-uppercase mb-4">ELEVATE</h5>
                <p>Connecting talent with opportunity since 2020. Our mission is to help people find their dream careers.</p>
                <div class="social-icons mt-3">
                    <a href="#" class="text-white me-2"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-white me-2"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-white me-2"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-instagram"></i></a>
                </div>
            </div>

            <!-- Column 2: Quick Links -->
            <div class="col-md-3 mb-4">
                <h5 class="text-uppercase mb-4">Quick Links</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Home</a></li>
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">About Us</a></li>
                    <li class="mb-2">
                        <a href="#" class="text-white text-decoration-none">Services</a>
                        <ul class="list-unstyled ps-3 mt-1">
                            <li class="mb-1"><a href="#" class="text-white-50 text-decoration-none">Career Counseling</a></li>
                            <li class="mb-1"><a href="#" class="text-white-50 text-decoration-none">Resume Review</a></li>
                            <li><a href="#" class="text-white-50 text-decoration-none">Interview Prep</a></li>
                        </ul>
                    </li>
                    <li><a href="#" class="text-white text-decoration-none">Contact</a></li>
                </ul>
            </div>

            <!-- Column 3: Resources -->
            <div class="col-md-3 mb-4">
                <h5 class="text-uppercase mb-4">Resources</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="#" class="text-white text-decoration-none">Blog</a></li>
                    <li class="mb-2">
                        <a href="#" class="text-white text-decoration-none">Career Guides</a>
                        <ul class="list-unstyled ps-3 mt-1">
                            <li class="mb-1"><a href="#" class="text-white-50 text-decoration-none">Tech Careers</a></li>
                            <li class="mb-1"><a href="#" class="text-white-50 text-decoration-none">Healthcare</a></li>
                            <li><a href="#" class="text-white-50 text-decoration-none">Business</a></li>
                        </ul>
                    </li>
                    <li><a href="#" class="text-white text-decoration-none">FAQs</a></li>
                </ul>
            </div>

            <!-- Column 4: Contact -->
            <div class="col-md-3 mb-4">
                <h5 class="text-uppercase mb-4">Contact Us</h5>
                <address>
                    <p class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> 17-Birauta, Pokhara </p>
                    <p class="mb-2"><i class="fas fa-phone me-2"></i> (123) 456-7890</p>
                    <p class="mb-2"><i class="fas fa-envelope me-2"></i> info@elevate.com</p>
                </address>
                <div class="newsletter mt-3">
                    <h6 class="text-uppercase mb-2">Newsletter</h6>
                    <form>
                        <div class="input-group">
                            <input type="email" class="form-control form-control-sm" placeholder="Your email">
                            <button class="btn btn-primary btn-sm" type="submit">Subscribe</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <hr class="my-4 bg-secondary">

        <div class="row">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-0">&copy; 2025 Elevate Workforce Solutions. All rights reserved.</p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <ul class="list-inline mb-0">
                    <li class="list-inline-item"><a href="#" class="text-white text-decoration-none">Privacy Policy | Terms & Conditions</a></li>
                    <li class="list-inline-item mx-2"></li>
                    <li class="list"></li>
<script src="{{ asset('assets/js/jquery.js') }}"></script>
<script>console.log('app layout');</script>
<!-- Removed the wrong query-3.6.9.0.min.js file -->
<script src="{{ asset('assets/js/bootstrap.bundle.5.1.3.min.js') }}"></script>
<script src="{{ asset('assets/js/instantpages.5.1.0.min.js') }}"></script>
<script src="{{ asset('assets/js/lazyload.17.6.0.min.js') }}"></script>
<script src="{{ asset('assets/js/custom.js') }}"></script>


{{-- @yield('customJs') --}}
<script>
    // Ensure CSRF token is included for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Fix the submit handler
    $("#profilePicForm").submit(function(e) {
        e.preventDefault(); // Prevent default form submission

        var formData = new FormData(this);

        $.ajax({
            url: "{{ route('account.updateProfilePic') }}",
            type: 'post',
            data: formData,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.status === false) {
                    var errors = response.errors;
                    if (errors.image) {
                        $('#image-errors').html(errors.image); // Add quotes and fix selector
                    }
                } else {
                    window.location.href = "{{ url()->current() }}"; // Typo fixed (locarion ➜ location)
                    // return response()->json([
                    //   'status' => true,
                    //   'message' => 'Profile updated successfully.']);
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error);
            }
        });

        
    });
</script>



@yield('customJs')
</body>
</html>
