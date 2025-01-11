<?php
include 'includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management System</title>

    <!-- TODO: Include DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">

    <!-- TODO: Include Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- TODO: Include jQuery ContextMenu CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.contextMenu.min.css">

    <!-- TODO: Css for DataTable File export -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css">

    <!-- TODO: Uncomment if needed -->
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.dataTables.css"> -->

    <!-- TODO: Include Font Awesome for icons -->
    <link rel="stylesheet" type="text/css" href="css/index.css?v=2.0">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="container mt-5">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <h2 class="mb-4">Users List</h2>
            <div class='mb-2'>
                <a class="btn btn-primary btn-md" href="process/add-user.php">Add User</a>
                <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#fullscreenModal">
                    Report
                </button>
            </div>
        </div>

        <?php include 'report.php'; ?>

        <div class="filter-section">
            <label>
                <input type="radio" name="sortCriteria" value="age" id="sortByAge" /> Sort by Age
            </label>

            <div class="filter-gender d-flex align-items-center">
                <label for="gender" class="mr-3 mt-2">Gender:</label>
                <div class="form-check form-check-inline">
                    <input type="radio" id="gender-female" name="gender" value="female" class="form-check-input">
                    <label class="form-check-label" for="gender-female">Female</label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="radio" id="gender-male" name="gender" value="male" class="form-check-input">
                    <label class="form-check-label" for="gender-male">Male</label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="radio" id="gender-all" name="gender" value="" class="form-check-input" checked>
                    <label class="form-check-label" for="gender-all">All</label>
                </div>
            </div>

        </div>
        <a href="generate_pdf.php" class="btn btn-success btn-md" id="generatePdfBtn">Generate PDF</a>

        <table id="users" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th></th>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Region</th>
                    <th>City</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT u.id, u.full_name, u.email, u.phone_number, u.region, u.city, d.age, d.gender
                          FROM users u
                          LEFT JOIN user_details d ON u.id = d.user_id";
                $result = mysqli_query($conn, $query);

                if ($result && mysqli_num_rows($result) > 0):
                    while ($row = mysqli_fetch_assoc($result)):
                ?>
                        <tr data-id="<?php echo $row['id'] ?>">
                            <td class="details-control"></td>
                            <td><?php echo $row['id'] ?></td>
                            <td><?php echo $row['full_name'] ?></td>
                            <td><?php echo $row['email'] ?></td>
                            <td><?php echo $row['phone_number'] ?></td>
                            <td><?php echo $row['region'] ?></td>
                            <td><?php echo $row['city'] ?></td>
                            <td><?php echo $row['age'] ?? 'N/A' ?></td>
                            <td><?php echo $row['gender'] ?? 'N/A' ?></td>
                            <td>
                                <a href="process/update-user.php?id=<?php echo $row['id'] ?>" class="btn btn-primary btn-sm">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <button class="btn btn-danger btn-sm delete-btn delete-user" data-id="<?php echo $row['id'] ?>">
                                    <i class="fa fa-trash"></i>
                                </button>
                                <button id="generate-pdf" onclick="downloadPdf(this)" data-user-id="<?php echo $row['id']; ?>" class="btn btn-success btn-sm">
                                    <i class="fas fa-file-pdf"></i>
                                </button>
                            </td>
                        </tr>
                <?php endwhile;
                endif; ?>
            </tbody>
        </table>
    </div>

    <!-- TODO: Context Menu -->
    <script src="contextMenu.js"></script>

    <!-- TODO: Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- TODO: Include DataTables JS -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>
    <!-- TODO: Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- TODO: Include SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- TODO: Include jQuery contextMenu plugin JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.contextMenu.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.ui.position.js"></script>

    <!-- TODO: JavaScript for DataTable File export -->
    <script type='text/javascript' src='https://cdn.datatables.net/2.1.8/js/dataTables.js'></script>
    <script type='text/javascript' src='https://cdn.datatables.net/buttons/3.2.0/js/dataTables.buttons.js'></script>
    <script type='text/javascript' src='https://cdn.datatables.net/buttons/3.2.0/js/buttons.dataTables.js'></script>
    <script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js'></script>
    <script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js'></script>
    <script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js'></script>
    <script type='text/javascript' src='https://cdn.datatables.net/buttons/3.2.0/js/buttons.html5.min.js'></script>
    <script type='text/javascript' src='https://cdn.datatables.net/buttons/3.2.0/js/buttons.print.min.js'></script>

    <script>
        function downloadPdf(button) {
            const userId = button.getAttribute('data-user-id');

            $.ajax({
                url: `generate_user_pdf.php`,
                method: 'GET',
                data: {
                    user_id: userId
                },
                success: function(response) {
                    try {
                        const data = JSON.parse(response);

                        if (data.status && data.file_path) {
                            Swal.fire({
                                title: 'User PDF',
                                html: `<iframe src="${data.file_path}" width="100%" height="400px" frameborder="0"></iframe>`,
                                width: '80%',
                                confirmButtonText: 'Close',
                            });
                        } else {
                            throw new Error(data.message || 'An error occurred.');
                        }
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: error.message,
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to fetch the PDF file path.',
                    });
                }
            });
        };


        $(document).ready(function() {
            // Fetch data for the child row
            function fetchChildRowData(userId, callback) {
                $.ajax({
                    url: './process/get-user-details.php',
                    method: 'GET',
                    data: {
                        user_id: userId
                    },
                    success: function(response) {
                        callback(response);
                    },
                    error: function() {
                        callback('Error fetching data');
                    }
                });
            };

            const table = $('#users').DataTable({
                stateSave: true,
                dom: 'Bfrtip',
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                pageLength: 50,
                lengthChange: true,
                columns: [{
                        className: 'dt-control',
                        orderable: false,
                        data: null,
                        defaultContent: ''
                    },
                    {
                        data: 'id'
                    },
                    {
                        data: 'full_name'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'phone_number'
                    },
                    {
                        data: 'region'
                    },
                    {
                        data: 'city'
                    },
                    {
                        data: 'age'
                    },
                    {
                        data: 'gender'
                    },
                    {
                        data: 'action'
                    }
                ],
                rowCallback: function(row, data) {
                    $('td.dt-control', row).on('click', function() {
                        const tr = $(this).closest('tr');
                        const row = $('#users').DataTable().row(tr);
                        if (row.child.isShown()) {
                            row.child.hide();
                            tr.removeClass('shown');
                        } else {
                            // Fetch user details for the clicked row
                            fetchChildRowData(data.id, function(response) {
                                // Format the child row with the fetched data
                                const childRowHtml = format(response); // You can modify the format function to handle the response
                                row.child(childRowHtml).show();
                                tr.addClass('shown');
                            });
                        }
                    });
                },
                buttons: [{
                        extend: 'copy',
                        className: 'btn copy',
                        text: '<i class="fa fa-copy"></i>',
                        exportOptions: {
                            columns: ':not(:first-child):not(:last-child)'
                        }
                    },
                    {
                        extend: 'csv',
                        className: 'btn csv',
                        text: '<i class="fa fa-file-csv"></i>',
                        exportOptions: {
                            columns: ':not(:first-child):not(:last-child)'
                        }
                    },
                    {
                        extend: 'excel',
                        className: 'btn excel',
                        text: '<i class="fa fa-file-excel"></i>',
                        exportOptions: {
                            columns: ':not(:first-child):not(:last-child)'
                        }
                    },
                    {
                        extend: 'pdf',
                        className: 'btn pdf',
                        text: '<i class="fa fa-file-pdf"></i>',
                        exportOptions: {
                            columns: ':not(:first-child):not(:last-child)'
                        }
                    },
                    {
                        extend: 'print',
                        className: 'btn print',
                        text: '<i class="fa fa-print"></i>',
                        exportOptions: {
                            columns: ':not(:first-child):not(:last-child)'
                        }
                    }
                ],
                order: [
                    [6, 'asc'],
                    [7, 'desc']
                ]
            });

            // Sort by Age when the checkbox is checked
            $('#sortByAge').on('change', function() {
                if ($(this).prop('checked')) {
                    table.order([6, 'asc']).draw();
                }
            });

            // Gender filter logic
            $('input[name="gender"]').on('change', function() {
                const selectedGender = $(this).val();
                table.column(7).search(selectedGender).draw();
                table.order([7, 'desc']).draw();
            });

            function format(d) {
                // Check if dob exists and parse it
                let date = d.dob ? new Date(d.dob) : null;
                let formattedDate = date ? `${date.getMonth() + 1}-${date.getDate()}-${date.getFullYear()}` : 'N/A';

                return `
                        <table id='details-user' cellpadding="5" cellspacing="0" border="0" style="padding-left:50px; width: 100%">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Age</th>
                                    <th>Gender</th>
                                    <th>Info</th>
                                    <th>Job</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>${formattedDate}</td>
                                    <td>${d.age || 'N/A'}</td>
                                    <td>${d.gender || 'N/A'}</td>
                                    <td>${d.info || 'N/A'}</td>
                                    <td>${d.job || 'N/A'}</td>
                                </tr>
                            </tbody>
                        </table>
                    `;
            }


            // TODO: Prevent context menu from opening on the action buttons
            $('#users tbody').on('contextmenu', '.btn', function(e) {
                e.stopPropagation();
            });


            document.getElementById('generatePdfBtn').addEventListener('click', function() {
                let userId = getUserId();
                this.href = 'generate_user_pdf.php?user_id=' + userId;
            });


            $('#generatePdfBtn').on('click', function() {
                // Trigger the AJAX request to generate the PDF
                $.ajax({
                    url: 'generate_pdf.php',
                    method: 'POST',
                    data: {
                        generate: true
                    },
                    success: function(response) {
                        window.location.href = response; // Redirect to the generated PDF
                    },
                    error: function() {
                        alert('Error generating the PDF');
                    }
                });
            });
        });
    </script>
</body>

</html>