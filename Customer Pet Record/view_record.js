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

        fetchVaccineRecords();
        fetchInvoiceRecords(); 
    }
};

function fetchVaccineRecords() {
    var recordID = new URLSearchParams(window.location.search).get('id');

    $.ajax({
        url: '../Customer Pet Record/vaccination_fetch_data.php', 
        type: 'GET',
        data: { id: recordID }, // Pass the recordID as a parameter to the PHP script
        success: function(html) {
            $('#vaccineRecordsBody').html(html);
        },
        error: function(xhr) {
            alert('An error occurred: ' + xhr.status + ' ' + xhr.statusText);
        }
    });
}

function fetchInvoiceRecords() {
    var icNo = $('#icNo_invoice').val();
    var email = $('#email_invoice').val();
    $.ajax({
        url: '../Customer Pet Record/invoice_fetch_data.php', 
        type: 'GET',
        data: { email: email, icNo: icNo },
        success: function(html) {
            $('#invoiceRecordsBody').html(html);
        },
        error: function(xhr) {
            alert('An error occurred: ' + xhr.status + ' ' + xhr.statusText);
        }
    });
}
