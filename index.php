<?php
// Include the database connection
include 'includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Managment System</title>
    <!-- Include DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Include Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-contextmenu/2.7.1/jquery.contextMenu.min.css">

</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Users List</h2>
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
                <tr data-id="<?= $user['id'] ?>">
                    <td><?= $user['id'] ?></td>
                    <td><?= $user['full_name'] ?></td>
                    <td><?= $user['email'] ?></td>
                    <td><?= $user['phone_number'] ?></td>
                    <td><?= $user['country'] ?></td>
                    <td><?= $details['age'] ?? 'N/A' ?></td>
                    <td><?= $details['gender'] ?? 'N/A' ?></td>
                </tr>
            <?php 
                endwhile;
            endif; 
            ?>
        </tbody>
    </table>

        <a class="btn btn-primary btn-md" href="process/add-user.php">Add User</a>
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
    <script src="datatable.js"></script>

    <!-- Context Menu -->
    <script src="contextMenu.js"></script>

</body>
</html>
