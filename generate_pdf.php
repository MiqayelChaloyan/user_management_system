<?php
require_once './dompdf/autoload.inc.php';
include 'includes/db.php';

use Dompdf\Dompdf;

$dompdf = new Dompdf();

// TODO: Start of HTML table with embedded CSS
$html = '
<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        td {
            background-color: #fff;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .details-control {
            width: 20px; /* Adjust width if necessary */
        }
    </style>
</head>
<body>

<h2>User Data</h2>

<table id="users" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Region</th>
            <th>City</th>
            <th>Age</th>
            <th>Gender</th>
        </tr>
    </thead>
    <tbody>';

// TODO: MySQL query to get user data
$query = "SELECT u.id, u.full_name, u.email, u.phone_number, u.region, u.city, d.age, d.gender 
          FROM users u
          LEFT JOIN user_details d ON u.id = d.user_id";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0):
    while ($row = mysqli_fetch_assoc($result)):
        $html .= '<tr data-id="' . $row['id'] . '">
                      <td>' . $row['id'] . '</td>
                      <td>' . $row['full_name'] . '</td>
                      <td>' . $row['email'] . '</td>
                      <td>' . $row['phone_number'] . '</td>
                      <td>' . $row['region'] . '</td>
                      <td>' . $row['city'] . '</td>
                      <td>' . ($row['age'] ?? 'N/A') . '</td>
                      <td>' . ($row['gender'] ?? 'N/A') . '</td>
                  </tr>';
    endwhile;
endif;

// TODO: End of HTML table
$html .= '</tbody></table>
</body>
</html>';

$dompdf->loadHtml($html);  // TODO: Load the HTML content into Dompdf
$dompdf->setPaper('A4', 'portrait'); // TODO: Set paper size and orientation

$dompdf->render(); // TODO: Render the PDF

$pdf = $dompdf->output(); // TODO: Get the output as PDF
file_put_contents("newfilegen.pdf", $pdf); // TODO: Save the generated PDF to file
