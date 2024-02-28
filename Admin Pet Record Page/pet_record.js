$(document).ready(function(){

    function fetchPetRecords(searchTerm = '', speciesFilter = ''){
        $.ajax({
            url: "../Admin Pet Record Page/pet_record_fetch_data.php",
            method: "POST",
            data: {query: searchTerm, filter: speciesFilter},
            success: function(data){
                $("#pet-record-table tbody").html(data);
            }
        });
    }

    // Event listener for pet search input
    $('.pet_record_search').keyup(function(){
        var searchTerm = $(this).val();
        var speciesFilter = $('#pet_record_filter').val();
        fetchPetRecords(searchTerm, speciesFilter);
    });

    // Event listener for species filter dropdown
    $('#pet_record_filter').change(function(){
        var speciesFilter = $(this).val();
        var searchTerm = $('.pet_record_search').val();
        fetchPetRecords(searchTerm, speciesFilter);
    });

    // Initially fetch all pet records without filter or search
    fetchPetRecords();

    
});
