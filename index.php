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
    <link rel="stylesheet" type="text/css" href="css/index.css">
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

        <div id="position">
            <!-- <label><input type="checkbox" name="pos" value="age"> Age</label> -->
            <!-- <label><input type="checkbox" name="pos" value="male"> Male</label>
            <label><input type="checkbox" name="pos" value="female"> Female</label> -->
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
                    <th>Country</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT u.id, u.full_name, u.email, u.phone_number, u.country, d.age, d.gender 
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
                    <td><?php echo $row['country'] ?></td>
                    <td><?php echo $row['age'] ?? 'N/A' ?></td>
                    <td><?php echo $row['gender'] ?? 'N/A' ?></td>
                    <td>
                        <a href="process/update-user.php?id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">
                            <i class="fa fa-edit"></i>
                        </a>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="<?= $row['id'] ?>">
                            <i class="fa fa-trash"></i>
                        </button>
                        <a href="generate_user_pdf.php?user_id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm">
                        <i class="fas fa-file-pdf"></i>
                        </a>
                        </td>
                </tr>
                <?php endwhile; endif; ?>
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
            function fetchChildRowData(userId, callback) {
                $.ajax({
                    url: './process/get-user-details.php',
                    method: 'GET',
                    data: { user_id: userId },
                    success: function(response) {
                        callback(response);
                    },
                    error: function() {
                        callback('Error fetching data');
                    }
                });
            }

            function format(d) {
                // TODO: Handle undefined or null values gracefully using ternary operator
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
                            <td>${new Date(d.dob).toLocaleString() || 'N/A'}</td>
                            <td>${d.age || 'N/A'}</td>
                            <td>${d.gender || 'N/A'}</td>
                            <td>${d.info || 'N/A'}</td>
                            <td>${d.job || 'N/A'}</td>
                        </tbody>
                    </table>`;
            }

            $(document).ready(function () {
                const table = $('#users').DataTable({
                    stateSave: true,  // Add this line
    dom: 'Bfrtip',
    paging: true,
    searching: true,
    ordering: true,
    info: true,
    pageLength: 50,
    lengthChange: true,
                    buttons: [
                        {
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
                    order: [[1, 'asc']],
                })

                $('#users tbody').on('click', 'td:first-child', function () {
    var tr = $(this).closest('tr');
    var row = table.row(tr);
    var userId = tr.data('id'); // Get the user ID

    if (row.child.isShown()) {
        row.child.hide();
        tr.removeClass('shown');
    } else {
        // Fetch additional data from the database via AJAX
        fetchChildRowData(userId, function(data) {
            row.child(format(data)).show();
            tr.addClass('shown');
        });
    }
});



                // TODO: Prevent context menu from opening on the action buttons
                $('#users tbody').on('contextmenu', '.btn', function (e) {
                    e.stopPropagation();
                }); 




        document.getElementById('generatePdfBtn').addEventListener('click', function() {
            var userId = getUserId(); // This should return the actual user ID
            this.href = 'generate_user_pdf.php?user_id=' + userId;
        });

        $('#generatePdfBtn').on('click', function () {
        // Trigger the AJAX request to generate the PDF
        $.ajax({
            url: 'generate_pdf.php',
            method: 'POST',  // Using POST to pass data securely
            data: { generate: true },  // Send a flag to generate the PDF
            success: function (response) {
                // If the PDF is generated successfully, handle the response (e.g., download)
                // Assuming the response contains the file URL or direct output
                window.location.href = response; // Redirect to the generated PDF
            },
            error: function () {
                alert('Error generating the PDF');
            }
        });
    });
            });
    </script>
</body>
</html>