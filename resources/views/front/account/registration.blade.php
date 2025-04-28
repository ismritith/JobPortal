@extends('front.layouts.app')

@section('main')
<section class="section-5 bg-[#7b0bf3]">
    <div class="container my-5">
        <div class="py-lg-2">&nbsp;</div>

        @if(Session::has('success'))
        <div class="alert alert-success">
            <p class="mb-0 pb-0">{{ Session::get('success') }}</p>
        </div>
        @endif
        
        @if(Session::has('error'))
        <div class="alert alert-danger">
            <p class="mb-0 pb-0">{{ Session::get('error') }}</p>
        </div>
        @endif

        <div class="row d-flex justify-content-center">
            <div class="col-md-5">
                <div class="card shadow border-0 p-5">
                    <h1 class="h3">Register</h1>
                    <form id="registrationForm" action="{{ route('account.processRegistration') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="mb-2">Name*</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter Name">
                            <p></p>
                        </div> 

                        <div class="mb-3">
                            <label for="email" class="mb-2">Email*</label>
                            <input type="text" name="email" id="email" class="form-control" placeholder="Enter Email">
                            <p></p>
                        </div> 

                        <div class="mb-3">
                            <label for="password" class="mb-2">Password*</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Enter Password">
                            <p></p>
                        </div> 

                        <div class="mb-3">
                            <label for="confirm_password" class="mb-2">Confirm Password*</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Please confirm Password">
                            <p></p>
                        </div> 

                        <button type="submit" class="btn btn-primary mt-2">Register</button>
                    </form>
                    
                    <div class="mt-4 text-center">
                        <p>Have an account? <a href="{{ route('account.login') }}">Login</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('customJs')
<script src="{{ asset('assets/js/jquery.js') }}"></script>
<script>
    $(document).ready(function() {
        // Registration form submission
        $('#registrationForm').submit(function(e) {
            e.preventDefault();

            $.ajax({
                url: 'http://localhost:8000/account/process-register',
                type: 'post',
                data: $(this).serializeArray(),
                dataType: 'json',
                success: function(response) {
                    // Clear previous errors
                    $(".is-invalid").removeClass("is-invalid");
                    $(".invalid-feedback").html("");

                    if (response.status === false) {
                        var errors = response.errors;
                        
                        if (errors.name) {
                            $("#name").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.name);
                        }
                        
                        if (errors.email) {
                            $("#email").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.email);
                        }
                        
                        if (errors.password) {
                            $("#password").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.password);
                        }
                        
                        if (errors.confirm_password) {
                            $("#confirm_password").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.confirm_password);
                        }
                    } else {
                        if (response.message) {
                            if ($('#registrationSuccessAlert').length === 0) {
                                $('<div id="registrationSuccessAlert" class="alert alert-success mt-3">' + 
                                  response.message + '</div>').insertBefore('#registrationForm');
                            } else {
                                $('#registrationSuccessAlert').text(response.message).show();
                            }

                            setTimeout(function() {
                                window.location.href = "http://localhost:8000/account/login";
                            }, 2000);
                        } else {
                            window.location.href = "http://localhost:8000/account/login";
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.log("Error:", error);
                    alert("An error occurred during registration. Please try again.");
                }
            });
        });

        // Profile Picture Form submission
        $("#profilePicForm").submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                url: "http://localhost:8000/account/update-profile-pic",
                type: 'post',
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.status === false) {
                        var errors = response.errors;
                        if (errors.image) {
                            $('#image-errors').html(errors.image);
                        }
                    } else {
                        window.location.href = "http://localhost:8000/account/register";
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", error);
                }
            });
        });

        // CSRF Token setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
</script>

