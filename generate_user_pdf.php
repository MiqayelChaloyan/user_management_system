<?php
require_once './dompdf/autoload.inc.php';
include 'includes/db.php';

use Dompdf\Dompdf;

$dompdf = new Dompdf();

// Get the user_id from the query string (e.g., `generate_user_pdf.php?user_id=1`)
if (isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];

    // Query for user details
    $query = "
        SELECT u.id, u.full_name, u.email, u.phone_number, u.country, d.age, d.gender, d.job, d.dob, d.info
        FROM users u
        LEFT JOIN user_details d ON u.id = d.user_id
        WHERE u.id = $userId
    ";

    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    // Format the date (dob) into a more readable format (e.g., dd-mm-yyyy)
    $formattedDob = date("d-m-Y", strtotime($row['dob']));

    // HTML structure with added styles
    $html = "
        <html>
            <head>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        margin: 20px;
                        padding: 0;
                        color: #333;
                    }
                    h1 {
                        text-align: center;
                        color: #2c3e50;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin: 20px 0;
                    }
                    table th, table td {
                        padding: 10px;
                        text-align: left;
                        border: 1px solid #ddd;
                    }
                    table th {
                        background-color: #f4f4f4;
                        color: #2c3e50;
                    }
                    table tr:nth-child(even) {
                        background-color: #f9f9f9;
                    }
                    table tr:hover {
                        background-color: #f1f1f1;
                    }
                </style>
            </head>
            <body>
                <h1>User Details</h1>
                <table>
                    <tr><th>Full Name:</th><td>" . (!empty($row['full_name']) ? $row['full_name'] : 'N/A') . "</td></tr>
                    <tr><th>Email:</th><td>" . (!empty($row['email']) ? $row['email'] : 'N/A') . "</td></tr>
                    <tr><th>Phone:</th><td>" . (!empty($row['phone_number']) ? $row['phone_number'] : 'N/A') . "</td></tr>
                    <tr><th>Country:</th><td>" . (!empty($row['country']) ? $row['country'] : 'N/A') . "</td></tr>
                    <tr><th>Age:</th><td>" . (!empty($row['age']) ? $row['age'] : 'N/A') . "</td></tr>
                    <tr><th>Gender:</th><td>" . (!empty($row['gender']) ? $row['gender'] : 'N/A') . "</td></tr>
                    <tr><th>Job:</th><td>" . (!empty($row['job']) ? $row['job'] : 'N/A') . "</td></tr>
                    <tr><th>Date of Birth:</th><td>" . (!empty($formattedDob) ? $formattedDob : 'N/A') . "</td></tr>
                    <tr><th>Info:</th><td>" . (!empty($row['info']) ? $row['info'] : 'N/A') . "</td></tr>
                </table>
            </body>
        </html>
    ";

    // Load HTML into Dompdf
    $dompdf->loadHtml($html);

    // Render PDF (first pass)
    $dompdf->render();

    // Stream the generated PDF to the browser
    $dompdf->stream("user_$userId.pdf", array("Attachment" => 0));
}
?>
