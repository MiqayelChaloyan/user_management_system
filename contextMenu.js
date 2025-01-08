$(document).ready(function () {
    let selectedRow;
    let globalPermissions = null;

    // Fetch global permissions
    $.ajax({
        type: 'POST',
        url: 'process/get-user-permissions.php',
        dataType: 'json',
        success: function (permissionData) {
            if (permissionData.status === 200 && permissionData.permissions) {
                globalPermissions = permissionData.permissions;
                console.log('Global Permissions:', globalPermissions); // Debugging
            } else {
                Swal.fire('Error', permissionData.message || 'Failed to fetch permissions.', 'error');
            }
        },
        error: function () {
            Swal.fire('Error', 'Failed to fetch global permissions.', 'error');
        }
    });

    // Initialize context menu
    $.contextMenu({
        selector: '#users tbody tr, #users_report tbody tr',
        callback: function (key) {
            if (!globalPermissions) {
                Swal.fire('Error', 'Permissions not loaded.', 'error');
                return;
            }

            const userId = selectedRow?.data('id');
            if (!userId) {
                Swal.fire('Error', 'Invalid row data.', 'error');
                return;
            }

            if (key === 'update') {
                if (globalPermissions.can_update === 'Yes') {
                    window.location.href = "process/update-user.php?id=" + userId;
                } else {
                    Swal.fire({
                        title: 'Warning',
                        text: 'No update permission.',
                        icon: 'warning',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                }
            } else if (key === 'remove') {
                if (globalPermissions.can_remove === 'Yes') {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to undo this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: 'POST',
                                url: 'process/delete-user.php',
                                data: { id: userId },
                                dataType: 'json',
                                success: function (data) {
                                    if (data.status === 200) {
                                        Swal.fire({
                                            title: 'Deleted!',
                                            text: data.message,
                                            icon: 'success',
                                            showConfirmButton: false,
                                            timer: 1500,
                                            timerProgressBar: true
                                        }).then(() => {
                                            selectedRow.remove(); // Remove the row from the table
                                        });
                                    } else {
                                        Swal.fire('Error!', data.message, 'error');
                                    }
                                },
                                error: function (xhr, status, error) {
                                    Swal.fire('Error!', 'There was an error deleting the user.', 'error');
                                }
                            });
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Warning',
                        text: 'No remove permission.',
                        icon: 'warning',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                }
            }
        },
        items: {
            "update": { name: "Update", icon: "edit" },
            "remove": { name: "Remove", icon: "delete" }
        }
    });

    // Store selected row for context menu (works for both #users and #users_report)
    $('#users tbody, #users_report tbody').on('contextmenu', 'tr', function () {
        selectedRow = $(this);
    });

    // Update and Remove Button Actions with Permissions (works for both #users and #users_report)
    $('#users tbody, #users_report tbody').on('click', '.update-btn', function (event) {
        event.preventDefault();

        if (!globalPermissions) {
            Swal.fire('Error', 'Permissions not loaded.', 'error');
            return;
        }

        const userId = $(this).closest('tr').data('id'); // tr > td > x-class
        if (globalPermissions.can_update === 'Yes') {
            window.location.href = "process/update-user.php?id=" + userId;
        } else {
            Swal.fire({
                title: 'Warning',
                text: 'No update permission.',
                icon: 'warning',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        }
    });

    $('#users tbody').on('click', '.delete-btn', function (event) {
        event.preventDefault();

        if (!globalPermissions) {
            Swal.fire('Error', 'Permissions not loaded.', 'error');
            return;
        }

        $(document).on('click', '.delete-user', function () {
            
            const userId = $(this).data('id'); // Get the user ID from the button's data attribute
            const selectedRow = $(this).closest('tr'); // Find the closest <tr> to the clicked button

            // Locate the nested child table if it exists
            const selectedDetailsRow = selectedRow.next('tr').find('#details-user');

            // Check global permissions
            if (globalPermissions.can_remove === 'Yes') {
                // Show confirmation dialog using SweetAlert2
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to undo this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Send AJAX request to delete the user
                        $.ajax({
                            type: 'POST',
                            url: 'process/delete-user.php',
                            data: { id: userId },
                            dataType: 'json',
                            success: function (data) {
                                if (data.status === 200) {
                                    // Show success message and remove the row
                                    Swal.fire({
                                        title: 'Deleted!',
                                        text: data.message,
                                        icon: 'success',
                                        showConfirmButton: false,
                                        timer: 1500,
                                        timerProgressBar: true
                                    }).then(() => {
                                        // Check if child table exists and remove it
                                        if (selectedDetailsRow.length) {
                                            selectedDetailsRow.remove();
                                        }
                                        // Remove the parent row
                                        selectedRow.remove();
                                    });
                                } else {
                                    // Show error message from server
                                    Swal.fire('Error!', data.message, 'error');
                                }
                            },
                            error: function (xhr, status, error) {
                                // Handle AJAX errors
                                Swal.fire('Error!', 'There was an error deleting the user.', 'error');
                            }
                        });
                    }
                });
            } else {
                // Show warning if no permission to remove
                Swal.fire({
                    title: 'Warning',
                    text: 'No remove permission.',
                    icon: 'warning',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            }
        });
    });
});
