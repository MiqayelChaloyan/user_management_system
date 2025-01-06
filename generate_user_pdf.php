<?php
require_once './dompdf/autoload.inc.php';
include 'includes/db.php';

use Dompdf\Dompdf;

$dompdf = new Dompdf();

// Get the user_id from the query string (e.g., `generate_user_pdf.php?user_id=1`)
if (isset($_GET['user_id'])) {
    $userId = $_GET['user_id'];

    // Query for user details
    $query = "SELECT u.id, u.full_name, u.email, u.phone_number, u.country, d.age, d.gender
              FROM users u
              LEFT JOIN user_details d ON u.id = d.user_id
              WHERE u.id = $userId";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

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
                    <tr><th>Full Name:</th><td>{$row['full_name']}</td></tr>
                    <tr><th>Email:</th><td>{$row['email']}</td></tr>
                    <tr><th>Phone:</th><td>{$row['phone_number']}</td></tr>
                    <tr><th>Country:</th><td>{$row['country']}</td></tr>
                    <tr><th>Age:</th><td>{$row['age']}</td></tr>
                    <tr><th>Gender:</th><td>{$row['gender']}</td></tr>
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
