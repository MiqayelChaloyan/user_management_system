<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" rel="stylesheet" />

  <link rel="stylesheet" type="text/css" href="css/report.css?v=2.0">

</head>

<body>

  <!-- Fullscreen Modal -->
  <div class="modal fade" id="fullscreenModal" tabindex="-1" role="dialog" aria-labelledby="fullscreenModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="fullscreenModalLabel">Report</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="scroll-content">

            <!-- Gender Filter Dropdown -->
            <div class="mb-3 custom-width">
              <label for="genderFilter">Filter by Gender:</label>
              <select id="genderFilter" class="form-control">
                <option value="">All</option>
                <option value="Female">Female</option>
                <option value="Male">Male</option>
              </select>
            </div>

            <!-- Table -->
            <table id="users_report" class="table table-bordered table-striped">
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
                    <tr data-id="<?= $user['id'] ?>" data-gender="<?= $details['gender'] ?? '' ?>">
                      <td><?= $user['id'] ?></td>
                      <td><?= $user['full_name'] ?></td>
                      <td><?= $user['email'] ?></td>
                      <td><?= $user['phone_number'] ?></td>
                      <td><?= $user['country'] ?></td>
                      <td><?= $details['age'] ?? 'N/A' ?></td>
                      <td class="gender"><?= $details['gender'] ?? 'N/A' ?></td>
                    </tr>
                <?php
                  endwhile;
                endif;
                ?>
              </tbody>
            </table>

          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS and dependencies -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <!-- JavaScript for DataTable File export -->
  <script type='text/javascript' src='https://cdn.datatables.net/2.1.8/js/dataTables.js'></script>
  <script type='text/javascript' src='https://cdn.datatables.net/buttons/3.2.0/js/dataTables.buttons.js'></script>
  <script type='text/javascript' src='https://cdn.datatables.net/buttons/3.2.0/js/buttons.dataTables.js'></script>
  <script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js'></script>
  <script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js'></script>
  <script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js'></script>
  <script type='text/javascript' src='https://cdn.datatables.net/buttons/3.2.0/js/buttons.html5.min.js'></script>
  <script type='text/javascript' src='https://cdn.datatables.net/buttons/3.2.0/js/buttons.print.min.js'></script>

  <!-- Custom JS -->
  <script type='text/javascript' src='https://code.jquery.com/jquery-3.7.1.js'></script>
  <script type='text/javascript' src='https://cdn.datatables.net/2.1.8/js/dataTables.js'></script>

  <!-- TODO: Context Menu -->
  <script src="contextMenu.js"></script>

  <script>
    $(document).ready(function() {
      const table = $('#users_report').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        pageLength: 50,
        lengthChange: true,
        dom: 'Bfrtip',
        buttons: [{
            extend: 'copy',
            className: 'btn copy',
            text: '<i class="fa fa-copy"></i>',
            exportOptions: {
              modifier: {
                search: 'applied',
              },
              columns: ':not(:first-child)'
            }
          },
          {
            extend: 'csv',
            className: 'btn csv',
            text: '<i class="fa fa-file-csv"></i>',
            exportOptions: {
              modifier: {
                search: 'applied',
              },
              columns: ':not(:first-child)'
            }
          },
          {
            extend: 'excel',
            className: 'btn excel',
            text: '<i class="fa fa-file-excel"></i>',
            exportOptions: {
              modifier: {
                search: 'applied',
              },
              columns: ':not(:first-child)'
            }
          },
          {
            extend: 'pdf',
            className: 'btn pdf',
            text: '<i class="fa fa-file-pdf"></i>',
            exportOptions: {
              modifier: {
                search: 'applied',
              },
              columns: ':not(:first-child)'
            }
          },
          {
            extend: 'print',
            className: 'btn print',
            text: '<i class="fa fa-print"></i>',
            exportOptions: {
              modifier: {
                search: 'applied',
              },
              columns: ':not(:first-child)'
            }
          }
        ]
      });

      // Gender filter logic
      $('#genderFilter').on('change', function() {
        var selectedGender = $(this).val();

        if (selectedGender) {
          table.column(6).search('^' + selectedGender + '$', true, false).draw();
        } else {
          table.column(6).search('').draw();
        }
      });
    });
  </script>

</body>

</html>