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
        $region = $user['region'];
        $city = $user['city'];
        $address = $user['address'];
        $file = $user['file'];
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
                <form id="user_form_update" method="POST" enctype="multipart/form-data">

                    <div class="mb-3">
                        <label for="region" class="form-label">Region</label>
                        <select class="form-select" id="region" name="region">
                            <?php ?>
                            <option value=<?= $region ?> selected><?= $region ?></option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="city" class="form-label">City</label>
                        <select class="form-select" id="city" name="city">
                            <option value=<?= $city ?> selected><?= $city ?></option>
                        </select>
                    </div>

                    <div class="mb-3" id="address-field" style="display: <?= ($city === 'Vanadzor') ? 'block' : 'none'; ?>;">
                        <label for="address" class="form-label">Address</label>
                        <input
                            type="text"
                            class="form-control"
                            id="address"
                            value="<?= $address ?>"
                            name="address">
                    </div>

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
                        <label for="uploadfile" class="form-label">
                            Image
                        </label>
                        <div class="d-flex align-items-center gap-3">
                            <input
                                type="file"
                                class="form-control"
                                id="image"
                                name="image"
                                value="<?= $file ?>"
                                accept="image/*">
                            <?php if (!empty($file)): ?>
                                <img
                                    src="../uploads/<?= $file ?>"
                                    alt="User Photo"
                                    style="width: 50px; height: 50px;">
                            <?php else: ?>
                                No Image
                            <?php endif; ?>
                        </div>
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
            document.getElementById('city').addEventListener('change', function() {
                const city = this.value;
                const addressField = document.getElementById('address-field');
                const addressInput = document.getElementById('address');

                if (city === 'Vanadzor') {
                    addressField.style.display = 'block';
                } else {
                    addressField.style.display = 'none';
                }
            });

            // TODO: Fetch and populate the regions dropdown
            $.ajax({
                url: './fetch-cities.php',
                type: 'POST',
                success: function(response) {
                    try {
                        const data = JSON.parse(response);
                        if (data.status === 200) {
                            let regionOptions = '<option value="" disabled selected>Select Region</option>';
                            data.regions.forEach(region => {
                                regionOptions += `<option value="${region.region}" ${region.region === '<?= $region ?>' ? 'selected' : ''}>${region.region}</option>`;
                            });
                            $('#region').html(regionOptions);
                        } else {
                            $('#region').html('<option value="" disabled>No regions available</option>');
                        }
                    } catch (e) {
                        console.error('Invalid JSON response for regions', e);
                        $('#region').html('<option value="" disabled>Error loading regions</option>');
                    }
                },
                error: function() {
                    console.error('Error fetching regions');
                    $('#region').html('<option value="" disabled>Error loading regions</option>');
                }
            });


            // TODO: Fetch and populate the cities dropdown based on selected region
            $('#region').on('change', function() {
                const regionName = $(this).val();

                if (regionName) {
                    $('#city').prop('disabled', false).html('<option value="" disabled>Loading...</option>');
                    $.ajax({
                        url: './fetch-cities.php',
                        type: 'POST',
                        data: {
                            region: regionName
                        },
                        success: function(response) {
                            try {
                                const data = JSON.parse(response);
                                if (data.status === 200) {
                                    let cityOptions = '<option value="" disabled selected>Select City</option>';
                                    data.cities.forEach(city => {
                                        cityOptions += `<option value="${city.city}" ${city.city === '<?= $city ?>' ? 'selected' : ''}>${city.city}</option>`;
                                    });
                                    $('#city').html(cityOptions);
                                } else {
                                    $('#city').html('<option value="" disabled>No cities found</option>');
                                }
                            } catch (e) {
                                console.error('Invalid JSON response for cities', e);
                                $('#city').html('<option value="" disabled>Error loading cities</option>');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', status, error);
                            $('#city').html('<option value="" disabled>Error loading cities</option>');
                        }
                    });
                }
            });


            // TODO: Pre-populate cities based on the selected region when page loads
            const selectedRegion = '<?= $region ?>';
            if (selectedRegion) {
                $('#region').val(selectedRegion).trigger('change');
            }


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
                    region: {
                        required: false,
                    },
                    city: {
                        required: false,
                    }
                },
                submitHandler: submitForm
            });

            function submitForm() {
                NProgress.start();

                let form = $("#user_form_update")[0]; // TODO: Get the form element
                let formData = new FormData(form); // TODO: Create FormData object

                // TODO: Get the userId value from the form's attribute
                let userId = $("#submit_button").data('user-id');

                // TODO: Append user_id to the FormData object using append() method
                formData.append("user_id", userId);

                $.ajax({
                    type: 'POST',
                    url: '../actions/update.php',
                    data: formData,
                    contentType: false, // TODO: Prevent jQuery from overriding content type
                    processData: false, // TODO: Prevent jQuery from converting FormData to string
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
                                // setTimeout(function() {
                                //     window.location.href = "../index.php";
                                // }, 1500);
                            });
                        } else {
                            Swal.fire({
                                title: '',
                                text: data.message,
                                icon: 'error',
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                // setTimeout(function() {
                                //     window.location.href = "../index.php";
                                // }, 1500);
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