<?php
include '../includes/db.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $query = "SELECT * FROM users WHERE id = $user_id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $user = mysqli_fetch_assoc($result);
        $full_name = $user['full_name'];
        $email = $user['email'];
        $phone_number = $user['phone_number'];
        $country = $user['country'];
    } else {
        echo "User not found.";
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Update Form</title>

    <!-- TODO: Use a local copy of Bootstrap for production environments to improve page load performance -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- TODO: Ensure to validate the integrity of external JS libraries using Subresource Integrity (SRI) in production -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/build/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/nprogress@0.2.0/nprogress.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body class="bg-light">
    <div class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-lg" style="width: 100%; max-width: 600px;">
            <div class="card-body">
                <h1 class="text-center text-primary mb-4">Update Form</h1>
                <form id="user_form_update" method="POST">
                    <div class="mb-3">
                        <label for="full_name" class="form-label">
                            Full Name
                        </label>
                        <input
                            type="text"
                            class="form-control"
                            id="full_name"
                            name="full_name"
                            value="<?= $full_name ?>"
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
                            value="<?= $email ?>"
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
                            value="<?= $phone_number ?>"
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
                            value="<?= $country ?>"
                            required>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button
                            type="submit"
                            class="btn btn-primary w-50"
                            id="submit_button"
                            data-user-id="<?= $user_id ?>">
                            Submit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // TODO: Add validation for specific input types like phone number and email format
            $("#user_form_update").validate({
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
                let data = $("#user_form_update").serialize();
                let userId = $("#submit_button").data('user-id');
                data += "&user_id=" + userId;

                $.ajax({
                    type: 'POST',
                    url: '../actions/update.php',
                    data: data,
                    dataType: 'json',
                    success: function(data) {
                        NProgress.done();
                        if (data.status === 200) {
                            toastr.options = {
                                positionClass: 'toast-top-right'
                            };
                            toastr.success(data.message);
                            Swal.fire({
                                title: 'Success',
                                text: 'User updated successfully!',
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
                        Swal.fire({
                            title: '',
                            text: 'An error occurred, please try again.',
                            icon: 'error',
                            showConfirmButton: false,
                            timer: 1500,
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