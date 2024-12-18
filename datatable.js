$(document).ready(function () {
    $('#users').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        pageLength: 50 
    });
});
