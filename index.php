<?php
include 'includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management System</title>
    <!-- Include DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Include Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include jQuery ContextMenu CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.contextMenu.min.css">
</head>

<body>
    <div class="container mt-5">
        <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
            <h2 class="mb-4">Users List</h2>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <label class='m-2'>
                    <input type="radio" name="sortCriteria" value="age" /> Sort by Age
                </label>
                <label class='m-2'>
                    <input type="radio" name="sortCriteria" value="gender" /> Sort by Gender (Male/Female)
                </label>
            </div>
            <div class='mb-2'>
                <a class="btn btn-primary btn-md" href="process/add-user.php">Add User</a>
            </div>
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
                        <!-- Update Button -->
                        <a name='Update' href="process/update-user.php?id=<?= $user['id'] ?>" class="mx-1 btn btn-primary btn-sm update-btn">
                            <i class="fa fa-exchange"></i>
                        </a>

                        <!-- Remove Button -->
                        <button name='Remove' class="mx-1 btn btn-danger btn-sm delete-btn" data-id="<?= $user['id'] ?>">
                            <i class="fa fa-trash-o"></i>
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

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include DataTables JS -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>
    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Include SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Include jQuery contextMenu plugin JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.contextMenu.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.ui.position.js"></script>

    <!-- JavaScript file for DataTable initialization -->
    <!-- <script src="datatable.js"></script> -->

    <!-- Context Menu -->
    <script src="context_menu.js"></script>

    <script>
        $(document).ready(function () {
            $('#users').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                pageLength: 50,
                lengthChange: true,
            });
        });

        // Prevent context menu from opening on the action buttons
        $('#users tbody').on('contextmenu', '.btn', function (e) {
            e.stopPropagation();
        });

        // Listen for sort criteria changes
        $("input[name='sortCriteria']").on('change', function () {
                var sortCriteria = $("input[name='sortCriteria']:checked").val();
                sortTable(sortCriteria);
            });

            // Function to sort the table
            function sortTable(criteria) {
                var rows = $('#users tbody tr').get();
                
                rows.sort(function(a, b) {
                    var ageA = $(a).find('td:eq(5)').text().trim(); // Age column index
                    var ageB = $(b).find('td:eq(5)').text().trim();

                    var genderA = $(a).find('td:eq(6)').text().trim(); // Gender column index
                    var genderB = $(b).find('td:eq(6)').text().trim();

                    // Filter out rows where age or gender is 'N/A'
                    if (ageA === 'N/A' || genderA === 'N/A') return 1;
                    if (ageB === 'N/A' || genderB === 'N/A') return -1;

                    if (criteria === 'age') {
                        // Compare by age
                        return parseInt(ageA) - parseInt(ageB);
                    } else if (criteria === 'gender') {
                        // Compare by gender
                        return genderA.localeCompare(genderB);
                    }
                });

                // Re-render the sorted rows in the table
                $.each(rows, function(index, row) {
                    $('#users tbody').append(row);
                });
            }
                
    </script>

</body>
</html>
