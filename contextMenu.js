$(document).ready(function() {
    let selectedRow;

    // Initialize context menu for rows
    $.contextMenu({
        selector: '#users tbody tr',
        callback: function(key) {
            const userId = selectedRow.data('id');
            if (!userId) {
                Swal.fire('Error', 'Invalid row data', 'error');
                return;
            }

            if (key === 'update') {
                // Redirect to update page
                window.location.href = "process/update-user.php?id=" + userId;
            } else if (key === 'remove') {
                // Handle remove action
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
                            success: function(data) {
                                if (data.status === 200) {
                                    Swal.fire({
                                        title: 'Deleted!',
                                        text: data.message,
                                        icon: 'success',
                                        showConfirmButton: false,
                                        timer: 1500,
                                        timerProgressBar: true
                                    }).then(() => {
                                        selectedRow.remove(); // Remove row from the table
                                    });
                                } else {
                                    Swal.fire('Error!', data.message, 'error');
                                }
                            },
                            error: function(xhr, status, error) {
                                Swal.fire('Error!', 'There was an error deleting the user.', 'error');
                            }
                        });
                    }
                });
            }
        },
        items: {
            "update": {name: "Update", icon: "fa-exchange"},
            "remove": {name: "Remove", icon: "fa-trash-o"},
        },
        // position: function(opt, x, y) {
        //     // Ensure context menu stays within the viewport
        //     const menuHeight = $('#contextMenu').outerHeight();
        //     const menuWidth = $('#contextMenu').outerWidth();
        //     let top = y;
        //     let left = x;

        //     // Prevent overflow
        //     if (top + menuHeight > $(window).height()) {
        //         top = $(window).height() - menuHeight - 10;
        //     }
        //     if (left + menuWidth > $(window).width()) {
        //         left = $(window).width() - menuWidth - 10;
        //     }

        //     return { top: top, left: left };
        // }
    });

    // Close context menu when clicking anywhere else
    $(document).on('click', function(e) {
        $('#contextMenu').hide();
    });

    // Close context menu on Escape key press
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            $('#contextMenu').hide();
        }
    });

    // Store the selected row when context menu is triggered
    $('#users tbody tr').on('contextmenu', function(e) {
        e.preventDefault();
        selectedRow = $(this);  // Set the selected row
    });
});
