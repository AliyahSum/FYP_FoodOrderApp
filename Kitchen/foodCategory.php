<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom styles */
        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f8f9fa;
        }
        .category-buttons {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            justify-items: center;
            max-width: 600px;
            border-radius: 8px;
            position: absolute;
            top: 35%;
        }
        .category-button {
            width: 200%;
            flex: 1;
            padding: 10px;
            font-size: 30px;
            text-align: center;
            transition: all 0.3s ease;
        }
        .category-button:hover {
            background-color: #0056b3;
            color: #fff;
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        .category-button:active {
            transform: scale(0.95);
        }
        .navbar-brand {
            font-size: 40px;
        }
        .backnav {
            position: absolute;
            left: 0%;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container position-relative d-flex justify-content-center">
            <button class="btn btn-outline-light backnav" onclick="navigateToBack()">
                <i class="bi bi-arrow-left"></i> Back
            </button>
            <div class="navbar-brand">Choose a Station</div>
        </div>
    </nav>
    
    <div class="category-buttons">
        <!-- Drinks Button -->
        <button class="btn btn-primary category-button" onclick="navigateToDrinks()">Drinks</button></br></br>
        <!-- Hot Button -->
        <button class="btn btn-primary category-button" onclick="navigateToHot()">Hot</button></br></br>
        <!-- Desserts Button -->
        <button class="btn btn-primary category-button" onclick="navigateToDessert()">Desserts</button>
    </div>

    <!-- Bootstrap JS and dependencies (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    
    <script>
        function navigateToHot() {
            window.location.href = "/FYP_FoodOrderApp/Kitchen/hot.php";
        }
        
        function navigateToDrinks() {
            window.location.href = "/FYP_FoodOrderApp/Kitchen/drinks.php";
        }
        
        function navigateToDessert() {
            window.location.href = "/FYP_FoodOrderApp/Kitchen/dessert.php";
        }
        
        function navigateToBack() {
            window.location.href = "/FYP_FoodOrderApp/Login/role.php";
        }
    </script>
</body>
</html>
