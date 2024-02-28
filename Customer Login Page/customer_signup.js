document.addEventListener('DOMContentLoaded', function() {
    var signupForm = document.getElementById('customerSignupForm');

    signupForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        var nickname = document.getElementById('nickname').value;
        var email = document.getElementById('email').value;
        var password = document.getElementById('password').value;

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../Customer Login Page/customer_signup_handler.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var response = this.responseText.trim();

                if (response === "Email is already registered") {
                    // Display the error message
                    document.getElementById('signupAlertMessage').style.display = 'block';
                    document.getElementById('signupAlertMessage').innerHTML = '<strong>' + response + '</strong>';
                } else {
                    // Handle signup success (e.g., redirecting to a login page or showing a success message)
                    window.location.href = '../Customer Login Page/customer_login.php'; // Replace with your path
                }
            }
        };

        xhr.send('nickname=' + encodeURIComponent(nickname) + '&email=' + encodeURIComponent(email) + '&password=' + encodeURIComponent(password));
    });
});
