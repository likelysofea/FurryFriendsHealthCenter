window.onload = function(){
    if (window.location.pathname.includes('view_inventory.php')){

        document.getElementById('editBtn').addEventListener('click', toggleEditSave);
        
        document.getElementById('saveBtn').addEventListener('click', function() {
            var itemCode = document.getElementById('itemCodeField').value;
            var itemName = document.getElementById('itemNameField').value;
            var description = document.getElementById('itemDescriptionField').value;
            var category = document.getElementById('itemCategoryField').value;
            var quantity = document.getElementById('itemQuantityField').value;
            var expiryDate = document.getElementById('expiryDateField').value;
            var reorderThreshold = document.getElementById('reorderThresholdField').value;

            var xhr = new XMLHttpRequest();

            //AJAX call to send data to the server
            xhr.open('POST', '../Admin Inventory Page/update_inventory.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (this.readyState === XMLHttpRequest.DONE) {
                    if (this.status === 200) {
                        alert(this.responseText);

                        if (this.responseText === "Record updated successfully") {
                            // Switch back to non-edit mode
                            goBack();
                        }

                    } else {
                        // Handle errors here
                        console.error('AJAX request failed:', this.status, this.statusText);
                    }
                }
            };
            xhr.send('itemCode=' + encodeURIComponent(itemCode) + '&itemName=' + encodeURIComponent(itemName) + '&description=' + encodeURIComponent(description) + '&category=' + encodeURIComponent(category) + '&quantity=' + encodeURIComponent(quantity) + '&expiryDate=' + encodeURIComponent(expiryDate) + '&reorderThreshold=' + encodeURIComponent(reorderThreshold));
        });

        document.getElementById('deleteBtn').addEventListener('click', function(){
            var itemCode = document.getElementById('itemCodeField').value;
            if (confirm('Are you sure you want to delete this record?')) {
                // Perform AJAX request to delete the record
                $.ajax({
                    url: '../Admin Inventory Page/delete_inventory.php', // The server-side script to handle the deletion
                    type: 'POST',
                    data: { itemCode: itemCode },
                    success: function(response) {
                        alert(response); // Alert the response from the server
                        if (response.indexOf("successfully") !== -1) { // Assuming the successful response contains the word "successfully"
                            window.location.href = 'inventory.php'; // Redirect to inventory page
                        }
                    },
                    error: function(xhr) {
                        alert('An error occurred: ' + xhr.status + ' ' + xhr.statusText);
                    }
                });
            }
        });
    }
}

function goBack() {
    if (!document.getElementById('itemNameField').disabled){
        document.getElementById('itemNameField').disabled = true;
        document.getElementById('itemDescriptionField').disabled = true;
        document.getElementById('itemQuantityField').disabled = true;
        document.getElementById('expiryDateField').disabled = true;
        document.getElementById('reorderThresholdField').disabled = true;

        document.getElementById('saveBtn').style.display = 'none'; 
        document.getElementById('editBtn').style.display = 'inline-block'; 
        document.getElementById('deleteBtn').style.display = 'inline-block';
    }else{
        window.history.back();
    }
}

function toggleEditSave() {
    var itemNameField = document.getElementById('itemNameField');
    var itemDescriptionField = document.getElementById('itemDescriptionField');
    var itemQuantityField = document.getElementById('itemQuantityField');
    var expiryDateField = document.getElementById('expiryDateField');
    var reorderThresholdField = document.getElementById('reorderThresholdField');

    itemNameField.disabled = !itemNameField.disabled;
    itemDescriptionField.disabled = !itemDescriptionField.disabled;
    itemQuantityField.disabled = !itemQuantityField.disabled;
    expiryDateField.disabled = !expiryDateField.disabled;
    reorderThresholdField.disabled = !reorderThresholdField.disabled;

    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0');
    var yyyy = today.getFullYear();
    var formattedDate = yyyy + '-' + mm + '-' + dd;

    document.getElementById('expiryDateField').setAttribute('min', formattedDate);

    document.getElementById('editBtn').style.display = 'none';
    document.getElementById('deleteBtn').style.display = 'none';
    document.getElementById('saveBtn').style.display = 'inline-block';

}
