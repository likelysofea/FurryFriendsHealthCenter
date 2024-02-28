window.onload = function(){
    if (window.location.pathname.includes('view_record.php')) {
        //Pet Information
        document.getElementById('petPicture').src = petImage;
        document.getElementById('recordID').value = recordID;
        document.getElementById('petName_record').value = petName;
        document.getElementById('species_record').value = species;
        document.getElementById('gender_record').value = gender;
        document.getElementById('age_record').value = age;
        document.getElementById('weight_record').value = weight;

        //Owner Information
        document.getElementById('icNo_record').value = icNo;
        document.getElementById('ownerName_record').value = ownerName;
        document.getElementById('phoneNo_record').value = phoneNo;
        document.getElementById('email_record').value = email;
        document.getElementById('address_record').value = address;

        // Attach the toggleEditMode function to the edit button using an event listener
        var editButton = document.getElementById('editButton');
        var cancelButton = document.getElementById('cancelButton');

        // Get references to the form and modal
        var vaccineModal = new bootstrap.Modal(document.getElementById('vaccineModal'));
        var vaccineForm = document.getElementById('vaccineForm');

        editButton.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent the default anchor behavior
            var isEditMode = editButton.innerText === 'Edit';
            
            if (isEditMode) {
                // Now in edit mode, change button text to 'Save'
                editButton.innerText = 'Save';
                cancelButton.style.display = '';
                // Make inputs editable
                toggleInputs(false);
            } else {
                // Now in save mode, change button text to 'Edit'
                editButton.innerText = 'Edit';
                cancelButton.style.display = 'none';
                // Save edits
                saveEdits();
            }
        });

        document.getElementById('cancelButton').addEventListener('click', function(event) {
            // Revert the form fields to their initial state
            resetFormFields();
        
            // Disable the form fields
            disableFormFields();
        
            // Optionally, change the 'Edit' button back to its original state
            var editButton = document.getElementById('editButton');
            editButton.innerText = 'Edit';
            cancelButton.style.display = 'none';
        });

        document.getElementById('addBtn').addEventListener('click', function() {
            vaccineForm.reset(); // Reset the form for new entry
            vaccineModal.show(); // Show the modal
        });

        document.getElementById('vaccineModal').addEventListener('shown.bs.modal', function () {

            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');  // get day and add a leading zero if needed
            var mm = String(today.getMonth() + 1).padStart(2, '0'); // get month (0-based) and add a leading zero if needed
            var yyyy = today.getFullYear();
            
            var formattedDate = yyyy + '-' + mm + '-' + dd;

            // Set the value of the date input
            document.getElementById('vaccinationDate').value = formattedDate;
            var nextVaccinationDate = document.getElementById('nextVaccinationDate');
            nextVaccinationDate.setAttribute('min', formattedDate);

            // Listen for change to the date input
            nextVaccinationDate.addEventListener('change', function() {
                var inputDate = new Date(this.value);
                var dayOfWeek = inputDate.getUTCDay();

                // Check if the day is Saturday (6) or Sunday (0)
                if(dayOfWeek === 6 || dayOfWeek === 0) {
                    // Show error message
                    document.getElementById('vaccinationDateFeedback').style.display = 'block';
                    this.classList.add('is-invalid');
                } else {
                    // Hide error message
                    document.getElementById('vaccinationDateFeedback').style.display = 'none';
                    this.classList.remove('is-invalid');
                }
            });

        });

        document.getElementById('editVaccinationModal').addEventListener('shown.bs.modal', function () {
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');  // get day and add a leading zero if needed
            var mm = String(today.getMonth() + 1).padStart(2, '0'); // get month (0-based) and add a leading zero if needed
            var yyyy = today.getFullYear();
            
            var formattedDate = yyyy + '-' + mm + '-' + dd;
        
            // Set the minimum value of the 'nextVaccinationDate' input
            var nextVaccinationDateInput = document.getElementById('nextVaccinationDateModal');
            nextVaccinationDateInput.setAttribute('min', formattedDate);
        
            // Listen for change to the 'nextVaccinationDate' input
            nextVaccinationDateInput.addEventListener('change', function() {
                var inputDate = new Date(this.value);
                var dayOfWeek = inputDate.getUTCDay();
        
                // Check if the day is Saturday (6) or Sunday (0)
                if(dayOfWeek === 6 || dayOfWeek === 0) {
                    // Show error message
                    document.getElementById('nextVaccinationDateFeedback').style.display = 'block';
                    this.classList.add('is-invalid');
                } else {
                    // Hide error message
                    document.getElementById('nextVaccinationDateFeedback').style.display = 'none';
                    this.classList.remove('is-invalid');
                }
            });
        });
        

        // Handle form submission
        $('#vaccineForm').on('submit', function(e) {
            e.preventDefault();
        
            var recordID = new URLSearchParams(window.location.search).get('id');
        
            $.ajax({
                url: '../Admin Pet Record Page/vaccination_form.php',
                type: 'POST',
                data: {
                    vaccineType: $('#vaccineType').val(),
                    vaccinationDate: $('#vaccinationDate').val(),
                    nextVaccinationDate: $('#nextVaccinationDate').val(),
                    recordID: recordID 
                },
                success: function(response) {
                    // Assuming response is plain text
                    alert(response); // Just alert the response directly
                    fetchVaccineRecords();
                    vaccineModal.hide();
                },
                error: function(xhr) {
                    alert('An error occurred: ' + xhr.status + ' ' + xhr.statusText);
                }
            });
        });

        $('#editVaccinationForm').on('submit', function(e) {
            e.preventDefault();
            
            var recordId = $('#recordIdModal').val();
            var newNextVaccinationDate = $('#nextVaccinationDateModal').val();
        
            // AJAX call to update the next vaccination date in the database
            $.ajax({
                url: '../Admin Pet Record Page/update_vaccination.php',
                type: 'POST',
                data: {
                    recordId: recordId,
                    nextVaccinationDate: newNextVaccinationDate
                },
                success: function(response) {
                    alert(response); 
                    $('#editVaccinationModal').modal('hide');
        
                    // Optionally, refresh the table to show the new date
                    fetchVaccineRecords();
                },
                error: function(xhr) {
                    alert('An error occurred: ' + xhr.status + ' ' + xhr.statusText);
                }
            });
        });

        document.getElementById('InvoiceBtn').addEventListener('click', function() {
            var icNo = document.getElementById('icNo_record').value;
            createInvoice(icNo);
        });
    }
    

};

function toggleInputs(disabled) {
    // Select all inputs except for those that should not be editable
    var editableInputs = document.querySelectorAll('input:not(#recordID, #species_record, #icNo_record)');
    editableInputs.forEach(function(input) {
        input.disabled = disabled; // Set the disabled property
    });
    document.getElementById('gender_record').disabled = !document.getElementById('gender_record').disabled;
}

function saveEdits(){
    //Editable pet info
    var petName = document.getElementById('petName_record').value;
    var gender = document.getElementById('gender_record').value;
    var age = document.getElementById('age_record').value;
    var weight = document.getElementById('weight_record').value;

    //editable owner info
    var name = document.getElementById('ownerName_record').value;
    var phoneNo = document.getElementById('phoneNo_record').value;
    var email = document.getElementById('email_record').value;
    var address = document.getElementById('address_record').value;

    //AJAX call to send data to the server
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../Admin Pet Record Page/update_pet_record.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (this.readyState === XMLHttpRequest.DONE) {
            if (this.status === 200) {
                alert(this.responseText);

                disableFormFields();

                if (this.responseText === "Record updated successfully") {
                    toggleEditMode(); // Switch back to non-edit mode
                }

            } else {
                // Handle errors here
                console.error('AJAX request failed:', this.status, this.statusText);
            }
        }
    };
    xhr.send('recordID=' + recordID + '&petName=' + encodeURIComponent(petName) + '&gender=' + encodeURIComponent(gender) + '&age=' + encodeURIComponent(age) + '&weight=' + encodeURIComponent(weight) + '&name=' + encodeURIComponent(name) + '&phoneNo=' + encodeURIComponent(phoneNo) + '&email=' + encodeURIComponent(email) + '&address=' + encodeURIComponent(address));
}

function resetFormFields() {
    // Reset each field to its original value
    document.getElementById('petName_record').value = petName;
    document.getElementById('gender_record').value = gender;
    document.getElementById('age_record').value;
    document.getElementById('weight_record').value;

    document.getElementById('ownerName_record').value;
    document.getElementById('phoneNo_record').value;
    document.getElementById('email_record').value;
    document.getElementById('address_record').value;

}

function disableFormFields() {
    // Disable all input fields
    var inputs = document.querySelectorAll('input, select');
    inputs.forEach(function(input) {
        input.disabled = true;
    });
}

function fetchVaccineRecords() {
    var recordID = new URLSearchParams(window.location.search).get('id');

    $.ajax({
        url: '../Admin Pet Record Page/vaccination_fetch_data.php', 
        type: 'GET',
        data: { id: recordID }, // Pass the recordID as a parameter to the PHP script
        success: function(html) {
            // Use the new ID for the table body
            $('#vaccineRecordsBody').html(html);
        },
        error: function(xhr) {
            alert('An error occurred: ' + xhr.status + ' ' + xhr.statusText);
        }
    });
}

function fetchInvoiceRecords() {
    var icNo = $('#icNo_invoice').val();
    $.ajax({
        url: '../Admin Invoice Page/invoice_fetch_data.php', 
        type: 'GET',
        data: { icNo: icNo }, // Pass the recordID as a parameter to the PHP script
        success: function(html) {
            // Use the new ID for the table body
            $('#invoiceRecordsBody').html(html);
        },
        error: function(xhr) {
            alert('An error occurred: ' + xhr.status + ' ' + xhr.statusText);
        }
    });
}

$(document).ready(function() {
    
    fetchVaccineRecords();

    fetchInvoiceRecords();

    $('#vaccineRecordsBody').on('click', '.edit-btn', function() {
        var recordId = $(this).data('id');
        var currentNextVaccinationDate = $(this).data('next-vaccination-date');

        $('#nextVaccinationDateModal').val(currentNextVaccinationDate);
        $('#recordIdModal').val(recordId);

        $('#editVaccinationModal').modal('show');
    });

    // Event delegation to handle clicks on dynamically created delete buttons
    $('#vaccineRecordsBody').off('click', '.delete-btn').on('click', '.delete-btn', function(){
        var recordId = $(this).data('id');
        
        if (confirm('Are you sure you want to delete this record?')) {
            // Perform AJAX request to delete the record
            $.ajax({
                url: '../Admin Pet Record Page/delete_vaccination.php', // The server-side script to handle the deletion
                type: 'POST',
                data: { recordId: recordId },
                success: function(response) {
                    alert(response); // Alert the response from the server
                    fetchVaccineRecords(); // Refresh the list of records
                },
                error: function(xhr) {
                    alert('An error occurred: ' + xhr.status + ' ' + xhr.statusText);
                }
            });
        }
    });
    
});

function createInvoice(icNo) {
    var url = '../Admin Invoice Page/invoice.php?icNo=' + encodeURIComponent(icNo);
    window.location.href = url;
}

function goBackPetRecord() {
    window.history.back();
}


