<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom styles (optional) */
        body {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f8f9fa;
        }
        .report-buttons {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            justify-items: center;
            max-width: 500px;
            border-radius: 8px;
            position: absolute;
            top: 20%;
        }
        .report-button {
            width: 130%;
            flex: 1;
            padding: 10px;
            font-size: 30px;
            text-align: center;
            transition: all 0.3s ease;
        }
        .report-button:hover {
            background-color: #0056b3;
            color: #fff;
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        .report-button:active {
            transform: scale(0.95);
        }
        .navbar-brand {
            font-size: 40px;
        }
        .backnav {
            position: absolute;
            right: 0%;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container position-relative d-flex justify-content-center">
            <button class="btn btn-outline-light backnav" onclick="navigateToBack()">
                <i class="bi bi-arrow-left"></i> Back
            </button>
            <div class="navbar-brand">Choose a Report to View</div>
        </div>
    </nav>
    
    <div class="report-buttons">
        <button class="btn btn-primary report-button" onclick="navigateToReport1()">Total Sales throughout the Month/Year</button></br></br>
        <button class="btn btn-primary report-button" onclick="navigateToReport2()">Top Staffs by Orders Served</button></br></br>
        <button class="btn btn-primary report-button" onclick="navigateToReport3()">Top 10 Sold Menu Items</button></br></br>
        <button class="btn btn-primary report-button" onclick="navigateToReport4()">Top 10 Menu Item by Sales</button></br></br>
        <button class="btn btn-primary report-button" onclick="navigateToReport5()">Most Preferred Payment Method</button>
    </div>

    <!-- Bootstrap JS and dependencies (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    
    <script>
        function navigateToReport1() {
            window.location.href = "/FYP_FoodOrderApp/Admin/reports/report1Month.php";
        }
        
        function navigateToReport2() {
            window.location.href = "/FYP_FoodOrderApp/Admin/reports/report2.php";
        }
        
        function navigateToReport3() {
            window.location.href = "/FYP_FoodOrderApp/Admin/reports/report3.php";
        }
        
        function navigateToReport4() {
            window.location.href = "/FYP_FoodOrderApp/Admin/reports/report4.php";
        }
        
        function navigateToReport5() {
            window.location.href = "/FYP_FoodOrderApp/Admin/reports/report5.php";
        }
        
        function navigateToBack() {
            window.location.href = "/FYP_FoodOrderApp/Login/admin.php";
        }
    </script>
</body>
</html>
