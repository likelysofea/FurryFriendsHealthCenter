document.addEventListener('DOMContentLoaded', function() {


    var loginForm = document.getElementById('customerLoginForm');

    loginForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission

        var email = document.getElementById('email').value;
        var password = document.getElementById('password').value;

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../Customer Login Page/customer_login_handler.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var response = this.responseText;
                console.log(response);
                if (response === "Invalid password" || response === "The email address is not registered") {
                    // Display the error message
                    document.getElementById('loginAlertMessage').style.display = 'block';
                    document.getElementById('loginAlertMessage').innerHTML = '<strong>' + response + '</strong>';
                } else {
                    window.location.href = '../Customer Homepage/homepage.php';
                }
            }
        };

        xhr.send('email=' + encodeURIComponent(email) + '&password=' + encodeURIComponent(password));
    });

    window.addEventListener('pageshow', (event) => {
        if (event.persisted) {
            window.location.reload();
        }
    });
});
