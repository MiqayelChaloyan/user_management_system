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
        SELECT u.id, u.full_name, u.email, u.phone_number, u.region, u.city, d.age, d.gender, d.job, d.dob, d.info
        FROM users u
        LEFT JOIN user_details d ON u.id = d.user_id
        WHERE u.id = $userId
    ";

    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);

    // Format the date (dob) into a more readable format (e.g., dd-mm-yyyy)
    $formattedDob = date("m-d-Y", strtotime($row['dob']));

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
                    <tr><th>Region:</th><td>" . (!empty($row['region']) ? $row['region'] : 'N/A') . "</td></tr>
                    <tr><th>City:</th><td>" . (!empty($row['city']) ? $row['city'] : 'N/A') . "</td></tr>
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
    $dompdf->setPaper('A4', 'portrait'); // Set paper size and orientation

    // Render PDF (first pass)
    $dompdf->render();

    // Create the folder if it doesn't exist
    $folderPath = 'user_pdfs'; // Specify the folder name
    if (!file_exists($folderPath)) {
        mkdir($folderPath, 0777, true); // Create the folder with appropriate permissions
    }

    // Save the PDF to the folder
    $pdfFilePath = $folderPath . "/user_$userId.pdf";
    $pdf = $dompdf->output(); // Get the output as PDF
    file_put_contents($pdfFilePath, $pdf); // Save the generated PDF to the folder

    // Now force the download of the saved PDF file
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . basename($pdfFilePath) . '"');
    readfile($pdfFilePath); // Output the file for download

    exit(); // Ensure no further output is sent
}
