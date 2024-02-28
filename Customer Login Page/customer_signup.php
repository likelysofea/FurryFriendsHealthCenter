<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Signup Form</title>

        <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
        <script src="../Customer Login Page/customer_signup.js"></script>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
        <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet"> 
        <link href="../Customer Login Page/customer_login.css" rel="stylesheet">

    </head>
    <body>
        <div id="signupAlertMessage" class="alert alert-danger text-center" role="alert" style="display: none;">
            <!--Error message will appear here-->
        </div>
        <div class="vertical-center">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6 col-lg-4">
                        <div class="text-center">
                            <div class="mb-5">
                                <img src="../Profile Picture/Garfield-and-Odie.png" alt="Logo" class="mb-3" width="150px" height="160px">
                                <h3>Pet Portal Signup</h3>
                            </div>

                            <form id="customerSignupForm" method="post">

                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="nickname" name="nickname" placeholder="Your name" required>
                                    <label for="nickname">Nickname</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                                    <label for="email">Email address</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                    <label for="password">Password</label>
                                </div>

                                <div class="d-grid mt-4">
                                    <button type="submit" class="btn btn-primary">Sign up</button>
                                </div>
                                
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>