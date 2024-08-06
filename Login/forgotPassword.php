<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Forgot Password</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <style>
            body {
                min-height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
                background-color: #f8f9fa;
            }
            .navbar-brand {
                font-size: 40px;
            }
            .backnav {
                position: absolute;
                left: 0%;
            }
            .form-container {
                max-width: 400px;
                margin: auto;
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            <div class="container position-relative d-flex justify-content-center">
                <button class="btn btn-outline-light backnav" onclick="navigateToBack()">
                    <i class="bi bi-arrow-left"></i> Back
                </button>
                <div class="navbar-brand">Forgot Password</div>
            </div>
        </nav>

        <div class="container form-container">
            <h1>Forgot Password</h1>
            <form method="post" action="../Login/doForgotPassword.php">
                <div class="mb-3">
                    <label for="staffid" class="form-label">Staff ID</label>
                    <input type="text" class="form-control" id="staffid" name="staffid" required>
                </div>
                <div class="mb-3">
                    <label for="fullname" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="fullname" name="fullname" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <input type="submit" name="send" class="btn btn-primary"></input>
            </form>
        </div>
    </body>
</html>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
<script>
    function navigateToBack() {
        window.location.href = "../index.php";
    }
</script>
    
    