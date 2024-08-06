<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Role Page</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                min-height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
                background-color: #f8f9fa;
            }
            .role-buttons {
                display: grid;
                grid-template-columns: repeat(1, 1fr);
                justify-items: center;
                max-width: 600px;
                border-radius: 8px;
                position: absolute;
                top: 40%;
            }
            .role-button {
                width: 200%;
                flex: 1;
                padding: 20px;
                font-size: 30px;
                text-align: center;
                transition: all 0.3s ease;
            }
            .role-button:hover {
                background-color: #0056b3;
                color: #fff;
                transform: scale(1.05);
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            }
            .role-button:active {
                transform: scale(0.95);
            }
            .navbar-brand {
                font-size: 40px;
            }
            #logoutButton {
                position: absolute;
                right: 2%;
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            <div class="container position-relative d-flex justify-content-center">
                <div class="navbar-brand">Choose a Role</div>
            </div>
            <button class="btn btn-outline-light" id="logoutButton">
                <i class="bi bi-arrow-left"></i> Logout
            </button>
        </nav>
        <div class="role-buttons">
            <button class="btn btn-primary role-button" onclick="navigateToKitchen()">Kitchen</button><br><br>
            <button class="btn btn-primary role-button" onclick="navigateToWaiter()">Waiter</button>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
        <script>
            function navigateToKitchen() {
                window.location.href = "../Kitchen/foodCategory.php";
            }
            function navigateToWaiter() {
                window.location.href = "../Server/tables.php";
            }
            document.getElementById('logoutButton').addEventListener('click', function(event) {
                event.preventDefault();
                if (confirm('Are you sure you want to logout?')) {
                    window.location.href = '../index.php';
                }
            });
        </script>
    </body>
</html>
