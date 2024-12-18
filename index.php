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
    <link rel="stylesheet" type="text/css" href="css/index.css?v=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>

<body>
    <div class="container mt-5">
        <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
            <h2 class="mb-4">Users List</h2>
            <div class='mb-2'>
                <!-- TODO: Add User Button -->
                <a class="btn btn-primary btn-md" href="process/add-user.php">Add User</a>
                <!-- TODO: Report Modal Button -->
                <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#fullscreenModal">
                    Report
                </button>
            </div>
        </div>

        <!-- TODO: Include report PHP file -->
        <?php include 'report.php' ?>

        <div id="position">
            <!-- TODO: Add radio buttons for Age and Gender filter -->
            <input type="radio" name="pos" value="Age" id="age">Age
            <input type="radio" name="gender" id="male" value="Male"> Male
            <input type="radio" name="gender" id="female" value="Female"> Female
        </div>

        <table id="users" class="table table-bordered table-striped">
            <thead>
                <tr>
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
                $userQuery = "SELECT id, full_name, email, phone_number, country FROM users";
                $userResult = mysqli_query($conn, $userQuery);

                if ($userResult && mysqli_num_rows($userResult) > 0):
                    while ($user = mysqli_fetch_assoc($userResult)):
                        $detailsQuery = "SELECT age, gender FROM user_details WHERE user_id = " . $user['id'];
                        $detailsResult = mysqli_query($conn, $detailsQuery);
                        $details = mysqli_fetch_assoc($detailsResult);
                ?>
                <tr data-id="<?php echo $user['id'] ?>">
                    <td><?= $user['id'] ?></td>
                    <td><?= $user['full_name'] ?></td>
                    <td><?= $user['email'] ?></td>
                    <td><?= $user['phone_number'] ?></td>
                    <td><?= $user['country'] ?></td>
                    <td><?= $details['age'] ?? 'N/A' ?></td>
                    <td><?= $details['gender'] ?? 'N/A' ?></td>
                    <td>
                        <!-- TODO: Add Update Button with correct link -->
                        <a name='Update' href="process/update-user.php?id=<?php $user['id'] ?>" class="mx-1 btn btn-primary btn-sm update-btn">
                            <i class="fa fa-exchange"></i>
                        </a>

                        <!-- TODO: Add Remove Button with delete logic -->
                        <button name='Remove' class="mx-1 btn btn-danger btn-sm delete-btn" data-id="<?php $user['id'] ?>">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <?php
                    endwhile;
                endif;
                ?>
            </tbody>
        </table>
    </div>

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

    <!-- TODO: Context Menu -->
    <script src="contextMenu.js"></script>

    <script>
        $(document).ready(function () {
            const table = $('#users').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                pageLength: 50,
                lengthChange: true,
                dom: 'Bfrtip',
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
                ]
            });

            // TODO: Handle changes in the radio buttons (Age, Male, Female)
            $('input:radio[name="pos"]').on('change', function () {
                var selectedValue = $(this).val();

                if (selectedValue === 'Age') {
                    $('#min-age, #max-age').show(); 
                    table.column(6).search('').draw(); // Clear gender filter when Age is selected

                    table.order([5, 'asc']).draw(); // Sort by Age column
                } else {
                    $('#min-age, #max-age').hide(); 
                    table.column(5).search('').draw(); // Clear age filter

                    table.column(6).search(selectedValue, true, false).draw(); // Gender filter
                }
            });

            // TODO: Gender filter logic for radio buttons
            $('input:radio[name="gender"]').on('change', function() {
            var selectedGender = $(this).val();

            if (selectedGender) {
                table.column(6).search('^' + selectedGender + '$', true, false).draw();
            } else {
                table.column(6).search('').draw();
            }
            });

            // TODO: Prevent context menu from opening on the action buttons
            $('#users tbody').on('contextmenu', '.btn', function (e) {
                e.stopPropagation();
            }); 
        });
    </script>

</body>
</html>
