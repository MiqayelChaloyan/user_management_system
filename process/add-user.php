<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Add Form</title>

    <!-- Bootstrap CSS (TODO: Ensure you are using the correct version and check if any update is needed) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery (TODO: Check if jQuery is still needed for your project, or if you can replace it with vanilla JavaScript) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- jQuery Validation Plugin (TODO: Ensure you are using the latest version and confirm that you need it for validation) -->
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <!-- Toastr (TODO: Verify if this toast notification library is necessary or if you want to use a different one) -->
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>
    <!-- NProgress (TODO: Check if you need NProgress for loading progress bars, or use an alternative if necessary) -->
    <script src="https://cdn.jsdelivr.net/npm/nprogress@0.2.0/nprogress.min.js"></script>
    <!-- SweetAlert2 (TODO: Make sure SweetAlert2 is the preferred method for alerts, or consider alternatives) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-light">
    <div class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-lg" style="width: 100%; max-width: 600px;">
            <div class="card-body">
                <h1 class="text-center text-primary mb-4">Add Form</h1>
                <form id="user_form" action="process/add-user.php" method="POST">
                    <div class="mb-3">
                        <label for="full_name" class="form-label">
                            Full Name
                        </label>
                        <input
                            type="text"
                            class="form-control"
                            id="full_name"
                            name="full_name"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            Email
                        </label>
                        <input
                            type="email"
                            class="form-control"
                            id="email"
                            name="email"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="phone_number" class="form-label">
                            Phone Number
                        </label>
                        <input
                            type="text"
                            class="form-control"
                            id="phone_number"
                            name="phone_number"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="country" class="form-label">
                            Country
                        </label>
                        <input
                            type="text"
                            class="form-control"
                            id="country"
                            name="country"
                            required>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary w-50" id="submit_button">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // TODO: Update validation rules if more fields are added in the future
            $("#user_form").validate({
                rules: {
                    full_name: {
                        required: true,
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    phone_number: {
                        required: true,
                    },
                    country: {
                        required: true,
                    }
                },
                submitHandler: submitForm
            });

            function submitForm() {
                NProgress.start();
                let data = $("#user_form").serialize();
                $.ajax({
                    type: 'POST',
                    url: '../actions/create.php',
                    data: data,
                    dataType: 'json',
                    success: function(data, textStatus, jqXHR) {
                        NProgress.done();

                        if (jqXHR.status === 200 && data.status === 200) {
                            toastr.options = {
                                positionClass: 'toast-top-right'
                            };
                            toastr.success(data.message);
                            Swal.fire({
                                title: 'Success',
                                text: data.message,
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                setTimeout(function() {
                                    window.location.href = "../index.php";
                                }, 1500);
                            });
                        } else {
                            Swal.fire({
                                title: '',
                                text: data.message,
                                icon: 'error',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                setTimeout(function() {
                                    window.location.href = "../index.php";
                                }, 1500);
                            });
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        NProgress.done();
                        let errorMessage = 'An error occurred, please try again.';
                        if (jqXHR.status === 500) {
                            errorMessage = 'Server error occurred. Please try again later.';
                        } else if (jqXHR.status === 400) {
                            errorMessage = 'Bad request. Please check the form fields.';
                        }

                        Swal.fire({
                            title: '',
                            text: errorMessage,
                            icon: 'error',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            setTimeout(function() {
                                window.location.href = "../index.php";
                            }, 1500);
                        });
                    }
                });
                return false;
            }
        });
    </script>
</body>

</html>