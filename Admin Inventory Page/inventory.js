$(document).ready(function() {

    function fetchInventory(searchTerm = '', categoryFilter = '') {
        $.ajax({
            url: "../Admin Inventory Page/inventory_fetch_data.php",
            method: "POST",
            data: {query: searchTerm, filter: categoryFilter},
            success: function(data) {
                $("#inventory-table tbody").html(data);
            }
        });
    }

    // Event listener for inventory search input
    $('.inventory_search').keyup(function() {
        var searchTerm = $(this).val();
        var categoryFilter = $('#inventory_filter').val();
        fetchInventory(searchTerm, categoryFilter);
    });

    // Event listener for category filter dropdown
    $('#inventory_filter').change(function() {
        var categoryFilter = $(this).val();
        var searchTerm = $('.inventory_search').val();
        fetchInventory(searchTerm, categoryFilter);
    });

    // Initially fetch all inventory records without filter or search
    fetchInventory();

    // Decrement quantity
    $(document).on('click', '.btn-decrement', function() {
        var input = $(this).closest('.quantity-counter').find('.quantity-input');
        var value = parseInt(input.val(), 10);
        if (value > 1) {
            updateQuantity(input, value - 1);
        }
    });

    // Increment quantity
    $(document).on('click', '.btn-increment', function() {
        var input = $(this).closest('.quantity-counter').find('.quantity-input');
        var value = parseInt(input.val(), 10);
        updateQuantity(input, value + 1);
    });

    // Update quantity in database
    function updateQuantity(input, newQuantity) {
        var container = input.closest('.quantity-counter');
        var itemCode = container.data('item-code');
        var reorderThreshold = parseInt(container.data('reorder-threshold'), 10); // Adjust if you use a different method to identify items
        
        input.val(newQuantity); // Update the input value

        if (newQuantity <= reorderThreshold) {
            // Change color to bold red
            input.addClass('text-danger fw-bold');

            // Prepare and show the modal
            var modalBody = $('#restockAlertModal').find('.modal-body');
            modalBody.html('Item <strong>' + itemCode + '</strong> needs to be restocked. Only <strong>' + newQuantity + '</strong> left in stock!');
            $('#restockAlertModal').modal('show');
        } else {
            // Remove the red bold color if quantity is above threshold
            input.removeClass('text-danger fw-bold');
        }

        $.ajax({
            url: '../Admin Inventory Page/update_quantity.php', // Update with the path to your server-side script
            method: 'POST',
            data: {
                itemCode: itemCode,
                newQuantity: newQuantity
            },
            success: function(response) {
                // Handle success
                alert('Quantity updated to ' + newQuantity + '!');
            },
            error: function(xhr, status, error) {
                // Handle error
                alert('Error: ' + error);
            }
        });
        
    }
    

});
