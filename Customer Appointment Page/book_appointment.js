function goBackBookAppointment() {
    window.history.back();
}

document.addEventListener("DOMContentLoaded", function() {
    if (window.location.pathname.includes('book_appointment.php')) {
        // Set the min attribute to today's date
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');  // get day and add a leading zero if needed
        var mm = String(today.getMonth() + 1).padStart(2, '0'); // get month (0-based) and add a leading zero if needed
        var yyyy = today.getFullYear();
        
        var formattedDate = yyyy + '-' + mm + '-' + dd;

        // Set the min attribute of the input
        var datePicker = document.getElementById('myDatepicker');
        datePicker.setAttribute('min', formattedDate);

        // Listen for change to the date input
        datePicker.addEventListener('change', function() {
            var inputDate = new Date(this.value);
            var dayOfWeek = inputDate.getUTCDay();

            // Check if the day is Saturday (6) or Sunday (0)
            if(dayOfWeek === 6 || dayOfWeek === 0) {
                // Show error message
                document.getElementById('dateInvalidFeedback').style.display = 'block';
                this.classList.add('is-invalid');
            } else {
                // Hide error message
                document.getElementById('dateInvalidFeedback').style.display = 'none';
                this.classList.remove('is-invalid');
            }
        });

        document.getElementById('phone').addEventListener('input', function() {
            var phonePattern =/^(\+?6?01)[02-46-9]-*[0-9]{7}$|^(\+?6?01)[1]-*[0-9]{8}$/;
            if(!phonePattern.test(this.value)) {
                document.getElementById('phoneInvalidFeedback').style.display = 'block';
                this.classList.add('is-invalid');
            } else {
                document.getElementById('phoneInvalidFeedback').style.display = 'none';
                this.classList.remove('is-invalid');
            }
        });
    }

});