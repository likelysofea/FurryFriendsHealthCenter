function goBackViewAppointment() {
    window.history.back();
}

window.onload = function() {
    // Get the status element
    var statusElement = document.getElementById('status');
    
    // Set the text value
    statusElement.value = statusName;

    // Set color based on status
    switch(statusName) {
        case 'Confirmed':
            statusElement.style.color = '#28a745';
            document.getElementById('confirm').style.display = 'none';
            document.getElementById('cancel').style.display = 'none';
            break;
        case 'Cancelled':
            statusElement.style.color = '#dc3545';
            document.getElementById('confirm').style.display = 'none';
            document.getElementById('cancel').style.display = 'none';
            break;
        case 'Pending':
            statusElement.style.color = '#ffc107';
            break;
    }

    // Make text bold
    statusElement.style.fontWeight = 'bold';

    // Assign other values
    document.getElementById('appointmentID').value = appointmentID;
    document.getElementById('date').value = dateFormatted;
    document.getElementById('time').value = time;
    document.getElementById('name').value = name;
    document.getElementById('phoneNo').value = phoneNo;
    document.getElementById('email').value = email;
    document.getElementById('species').value = species;
    document.getElementById('reason').textContent = reason;

};

$(document).ready(function() {
    $('#confirm').click(function() {
        var appointmentId = document.getElementById('appointmentID').value;
        var name = document.getElementById('name').value;
        var email = document.getElementById('email').value;
        var date = document.getElementById('date').value;
        var time = document.getElementById('time').value;

        $.ajax({
            type: 'POST',
            url: '../Admin Appointment Page/status_confirm.php', // The PHP file that will update the status in your database
            data: { id: appointmentId, status: 2, name: name, email: email, time: time, date: date },
            success: function(response) {
                console.log(response);
                // Handle the response from the server
                try {
                    var data = JSON.parse(response);
                    // Check if the update was successful
                    if (data && data.success) {
                        // Update the status on the page
                        var statusElement = document.getElementById('status');
                        statusElement.value = 'Confirmed';
                        statusElement.style.color = '#28a745'; // Green color for confirmed status
                        statusElement.style.fontWeight = 'bold';

                        // Hide the 'Confirm' and 'Cancel' buttons since the appointment is now confirmed
                        document.getElementById('confirm').style.display = 'none';
                        document.getElementById('cancel').style.display = 'none';

                        // Optionally, display the success message from the server
                        alert(data.message);

                    } else {
                        // If data.success is not true, or any other status is received
                        alert('Update was not successful: ' + data.message);
                    }
                } catch (e) {
                    console.error("Parsing error:", e);
                    console.error("Response was:", response);
                    alert('There was a problem with the response from the server.');
                }
            }
        });
    });

    $('#cancel').click(function() {
        var appointmentId = document.getElementById('appointmentID').value;
        var name = document.getElementById('name').value;
        var email = document.getElementById('email').value;
        var date = document.getElementById('date').value;
        var time = document.getElementById('time').value;

        $.ajax({
            type: 'POST',
            url: '../Admin Appointment Page/status_cancel.php', // The PHP file that will update the status in your database
            data: { id: appointmentId, status: 3, name: name, email: email, time: time, date: date },
            success: function(response) {
                console.log(response);
                // Handle the response from the server
                try {
                    var data = JSON.parse(response);
                    // Check if the update was successful
                    if (data && data.success) {
                        // Update the status on the page
                        var statusElement = document.getElementById('status');
                        statusElement.value = 'Cancelled';
                        statusElement.style.color = '#dc3545'; 
                        statusElement.style.fontWeight = 'bold';

                        document.getElementById('confirm').style.display = 'none';
                        document.getElementById('cancel').style.display = 'none';

                        // Optionally, display the success message from the server
                        alert(data.message);

                    } else {
                        // If data.success is not true, or any other status is received
                        alert('Update was not successful: ' + data.message);
                    }
                } catch (e) {
                    console.error("Parsing error:", e);
                    console.error("Response was:", response);
                    alert('There was a problem with the response from the server.');
                }
            }
        });
    });
});
