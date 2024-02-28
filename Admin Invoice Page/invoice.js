function goBackInvoice() {
    window.history.back();
}

document.addEventListener('DOMContentLoaded', (event) => {
    if (window.location.pathname.includes('invoice.php')) {
        // Initialize the date
        var invoiceDateInput = document.getElementById('invoiceDate');
        if (invoiceDateInput) {
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0');
            var yyyy = today.getFullYear();
            invoiceDateInput.value = `${yyyy}-${mm}-${dd}`;
        }

        // Add new line item
        var addButton = document.getElementById('addLineItem');
        if (addButton) {
            addButton.addEventListener('click', addLineItem);
        }

        // Update total amount
        updateTotalAmount(); // Initial update

        // Event delegation for dynamically added remove buttons
        var lineItemsTable = document.getElementById('lineItems');
        if (lineItemsTable) {
            lineItemsTable.addEventListener('click', function(event) {
                if (event.target.classList.contains('remove-line-item')) {
                    removeLineItem(event.target);
                }
            });
        }

        $('#printBtn').hide();

        document.getElementById('saveBtn').addEventListener('click', function(event) {
            event.preventDefault(); // Prevent the default form submission
            
            var formData = new FormData();

            formData.append('invoiceNo', document.getElementById('invoiceNo').value);
            formData.append('date', document.getElementById('invoiceDate').value);
            formData.append('generatedBy', document.getElementById('generatedBy').value);
            formData.append('billTo', document.getElementById('billTo').value);
            formData.append('paymentMethod', document.getElementById('paymentMethod').value);

            var descriptions = document.querySelectorAll('input[name="description[]"]');
            var amounts = document.querySelectorAll('input[name="amount[]"]');

            descriptions.forEach((desc, index) => {
                formData.append('descriptions[]', desc.value);
                formData.append('amounts[]', amounts[index].value);
            });

            formData.append('totalAmount', document.getElementById('totalAmount').textContent);

            $.ajax({
                type: 'POST',
                url: '../Admin Invoice Page/save_invoice.php', 
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    // Display the success message in the modal
                    $('#responseModal .modal-body').text(response);
                    $('#responseModal').modal('show');
                    $('#saveBtn').hide();
                    $('#printBtn').show();
                },
                error: function(xhr, status, error) {
                    // Display the error message in the modal
                    $('#responseModal .modal-body').text(`Error: ${error}`);
                    $('#responseModal').modal('show');
                }
            });
        });
        
    }
});

function addLineItem() {
    var newRow = lineItems.insertRow();
    var newCell1 = newRow.insertCell(0);
    var newCell2 = newRow.insertCell(1);
    var newCell3 = newRow.insertCell(2);

    newCell1.innerHTML = '<input type="text" class="form-control" name="description[]" required>';
    newCell2.innerHTML = '<input type="text" class="form-control line-item-amount" name="amount[]" oninput="updateTotalAmount()" required>';
    newCell3.innerHTML = '<button type="button" class="btn btn-danger remove-line-item"><i class="bi bi-trash3"></i></button>';

    // Add click event listener for the remove button in the new row
    newCell3.children[0].addEventListener('click', function() {
        removeLineItem(this);
    });
}

function removeLineItem(button) {
    button.closest('tr').remove();
    updateTotalAmount(); // Update the total when an item is removed
}

function updateTotalAmount() {
    // Get all the amount input fields
    var amountInputs = document.querySelectorAll('.line-item-amount');
    var total = 0;

    // Calculate the sum of all amounts
    amountInputs.forEach(function(input) {
        // Convert input value to a number and add it to the total
        var amount = parseFloat(input.value) || 0;
        total += amount;
    });

    document.getElementById('totalAmount').textContent = total.toFixed(2);

    // Make sure to attach the updateTotalAmount to the line item amounts
    document.querySelectorAll('.line-item-amount').forEach(function(input) {
        input.oninput = updateTotalAmount;
    });
}

