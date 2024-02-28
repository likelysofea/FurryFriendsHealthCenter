$(document).ready(function(){

    function fetch_data(query = '', filter = ''){
        $.ajax({
            url: "../Admin Appointment Page/appointment_fetch_data.php",
            method: "POST",
            data: {query: query, filter: filter},
            success: function(data){
                $("#appointment-table tbody").html(data);
            }
        });
    }

    // Event listener for search input
    $('.search-input').keyup(function(){
        var search = $(this).val();
        var filter = $('#filter').val();
        fetch_data(search, filter);
    });

    // Event listener for filter dropdown
    $('#filter').change(function(){
        var filter = $(this).val();
        var search = $('.search-input').val();
        fetch_data(search, filter);
    });

    // Initially fetch all data without filter or search
    fetch_data();
    
});


