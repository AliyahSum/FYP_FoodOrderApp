<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Set Maximum Seats</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <style>
            body {
                padding-top: 120px;
                display: flex;
                flex-direction: column;
                align-items: center;
                background-color: #f8f9fa;
                justify-content: center;
            }
            
            .role-buttons {
                display: grid;
                grid-template-columns: repeat(5, 1fr); /* Five columns */
                gap: 70px; 
                max-width: 1000px; /* Maximum width of the grid */
                border-radius: 5px;
                margin-top: 80px;
            }
            
            .role-button1, .role-button2, .role-button3, .role-button4, .role-button5, 
            .role-button6, .role-button7, .role-button8, .role-button9, .role-button10, 
            .role-button11, .role-button12, .role-button13, .role-button14 {
                width: 200px;  
                height: 100px;
                font-size: 27px;
                text-align: center;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            }
            
            .switch {
                position: relative;
                display: inline-block;
                width: 60px;
                height: 34px;
                margin-top: 5px; /* Adjusted margin-top for switch */
            }
            
            .mb-1 {
                margin-top: -50px; /* Adjusted margin-bottom for the wrapper div */
            }
            
            .switch input {
                opacity: 0;
                width: 0;
                height: 0;
            }
            
            .slider {
                position: absolute;
                cursor: pointer;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: #ccc;
                transition: .4s;
            }
            
            .slider:before {
                position: absolute;
                content: "";
                height: 26px;
                width: 26px;
                left: 4px;
                bottom: 4px;
                background-color: white;
                transition: .4s;
            }
            
            input:checked + .slider {
                background-color: #44D368;
            }
            
            input:focus + .slider {
                box-shadow: 0 0 1px #44D368;
            }
            
            input:checked + .slider:before {
                transform: translateX(26px);
            }
            
            .slider.round {
                border-radius: 34px;
            }
            
            .slider.round:before {
                border-radius: 50%;
            }
            
            .btn-primary {
                background-color: #007bff;
                border-color: #007bff;
            }
            
            .btn-secondary {
                background-color: #6c757d;
                border-color: #6c757d;
            }
            
            .navbar {
                background-color: #343a40;
            }
            .navbar-brand {
                font-size: 40px;
            }

            .navbar-nav .nav-link {
                color: #ffffff;
            }
            .navbar-nav .nav-link:hover {
                color: #cccccc;
            }
            
            .backnav {
                margin-right: 10px;
            }
            
            .reset, .allmaxseat {
                margin-bottom: 20px;
            }
            
            .reset, .allmaxseat {
                display: flex;
                justify-content: center;
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            <div class="container">
                <a class="navbar-brand" href="#">Manage Tables</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="/FYP_FoodOrderApp/Login/admin.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/FYP_FoodOrderApp/Admin/tables/tables_admin.php">Tables</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/FYP_FoodOrderApp/Admin/user/viewUsers.php">Users</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/FYP_FoodOrderApp/Admin/menu/viewMenu.php">Menu</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/FYP_FoodOrderApp/Admin/orders/viewAllOrders.php">View All Orders</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/FYP_FoodOrderApp/Admin/reports/report.php">Reports</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="btn btn-outline-light backnav" href="/FYP_FoodOrderApp/Login/admin.php">Back</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-outline-light logOutNav" id="logoutButton" href="/FYP_FoodOrderApp/index.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container">
            <!-- Role buttons grid -->
            <div class="role-buttons">
                <?php for ($i = 1; $i <= 14; $i++): ?>
                    <div class="mb-1 text-center">
                        <button type="button" class="btn role-button<?php echo $i; ?> btn-secondary mb-2" data-bs-toggle="modal" data-bs-target="#setMaxSeatsModal" data-tablenum="<?php echo $i; ?>">
                            Table <?php echo $i; ?>
                        </button>
                        <label class="switch">
                            <input type="checkbox" class="toggle-availability" data-tablenum="<?php echo $i; ?>" checked>
                            <span class="slider round"></span>
                        </label>
                    </div>
                <?php endfor; ?>
            </div>
            <!-- Modal for individual tables -->
            <div class="modal fade" id="setMaxSeatsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Table <span id="tableNumTitle"></span></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="setMaxSeatsForm" action="/FYP_FoodOrderApp/Admin/tables/set_max_seats.php" method="post">
                                <input type="hidden" id="tableNum" name="tableNum">
                                <div class="mb-3">
                                    <label for="maxSeats" class="form-label">Set Maximum Seats</label>
                                    <input type="number" class="form-control" id="maxSeats" name="maxSeats" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Confirm</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal for setting max seats for all tables -->
            <div class="modal fade" id="setAllMaxSeatsModal" tabindex="-1" aria-labelledby="setAllMaxSeatsModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="setAllMaxSeatsModalLabel">Set Maximum Seats for All Tables</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="setAllMaxSeatsForm" action="/FYP_FoodOrderApp/Admin/tables/set_max_seats.php" method="post">
                                <input type="hidden" name="action" value="set_all_max_seats">
                                <div class="mb-3">
                                    <label for="allMaxSeats" class="form-label">Set Maximum Seats</label>
                                    <input type="number" class="form-control" id="allMaxSeats" name="allMaxSeats" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Confirm</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
<!-- Button to trigger modal for setting max seats for all tables -->
<div class="d-flex justify-content-center my-4">
    <button type="button" class="btn btn-warning mx-2" data-bs-toggle="modal" data-bs-target="#setAllMaxSeatsModal">
        Set Maximum Seats for All Tables
    </button>
    <button type="button" class="btn btn-danger mx-2 btn-reset">Reset All Tables</button>
</div>

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            $(document).ready(function () {
                // Function to update table button color based on toggle state
                function updateTableButtonColor(tableNum, isChecked) {
                    var button = $('.role-button' + tableNum);
                    if (isChecked) {
                        button.removeClass('btn-secondary').addClass('btn-primary');
                    } else {
                        button.removeClass('btn-primary').addClass('btn-secondary');
                    }
                }

                // Modal event handler for individual tables
                $('#setMaxSeatsModal').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget);
                    var tableNum = button.data('tablenum');
                    var modal = $(this);
                    modal.find('#tableNum').val(tableNum);
                    modal.find('#tableNumTitle').text(tableNum);
                });

                // Toggle button change event handler
                $('.toggle-availability').change(function () {
                    var checkbox = $(this);
                    var tableNum = checkbox.data('tablenum');
                    var isAvailable = checkbox.is(':checked') ? 1 : 0;
                    updateTableButtonColor(tableNum, checkbox.is(':checked'));
                    $.ajax({
                        url: '/FYP_FoodOrderApp/Admin/tables/toggle_availability.php',
                        type: 'post',
                        data: {tableNum: tableNum, isAvailable: isAvailable},
                        success: function (response) {
                            console.log(response);
                        }
                    });
                });

                // Initialize button colors based on initial toggle state
                $('.toggle-availability').each(function () {
                    var checkbox = $(this);
                    var tableNum = checkbox.data('tablenum');
                    var isChecked = checkbox.is(':checked');
                    updateTableButtonColor(tableNum, isChecked);
                });

                // Ensure all toggle buttons are initially set to 'on' and update table button colors
                $('.toggle-availability').prop('checked', true).each(function () {
                    var tableNum = $(this).data('tablenum');
                    updateTableButtonColor(tableNum, true);
                });

                document.getElementById('logoutButton').addEventListener('click', function (event) {
                    event.preventDefault();
                    if (confirm('Are you sure you want to logout?')) {
                        window.location.href = '/FYP_FoodOrderApp/index.php';
                    }
                });

                // Fetch initial table states from the server
                $.ajax({
                    url: 'get_table_states.php',
                    type: 'get',
                    dataType: 'json',
                    success: function (data) {
                        data.forEach(function (table) {
                            var tableNum = table.table_num;
                            var isChecked = table.isAvailable == 1;
                            $('.toggle-availability[data-tablenum="' + tableNum + '"]').prop('checked', isChecked);
                            updateTableButtonColor(tableNum, isChecked);
                        });
                    }
                });

                // Reset button click event handler
                $('.btn-reset').click(function () {
                    if (confirm('Are you sure you want to reset all the tables availability?')) {
                        $('.toggle-availability').each(function () {
                            var tableNum = $(this).data('tablenum');
                            $(this).prop('checked', true);
                            updateTableButtonColor(tableNum, true);
                            $.ajax({
                                url: '/FYP_FoodOrderApp/Admin/tables/toggle_availability.php',
                                type: 'post',
                                data: {tableNum: tableNum, isAvailable: 1},
                                success: function (response) {
                                    console.log(response);
                                },
                            });
                        });
                    }
                });

                // AJAX form submission for setting max seats for individual tables
                $('#setMaxSeatsForm').submit(function (event) {
                    event.preventDefault();
                    var formData = $(this).serialize();
                    $.ajax({
                        url: '/FYP_FoodOrderApp/Admin/tables/set_max_seats.php',
                        type: 'post',
                        data: formData,
                        success: function (response) {
                            alert(response);
                            $('#setMaxSeatsModal').modal('hide');
                        }
                    });
                });

                // AJAX form submission for setting max seats for all tables
                $('#setAllMaxSeatsForm').submit(function (event) {
                    event.preventDefault();
                    var allMaxSeats = $('#allMaxSeats').val();
                    $.ajax({
                        url: '/FYP_FoodOrderApp/Admin/tables/set_all_max_seats.php',
                        type: 'post',
                        data: {allMaxSeats: allMaxSeats},
                        success: function (response) {
                            alert(response);
                            $('#setAllMaxSeatsModal').modal('hide');
                        }
                    });
                });
            });
            
                        
                                            function refreshPage() {
            window.location.reload();
        }
        


        // Set the interval to refresh the page every 30 seconds (30000 milliseconds)
        setInterval(refreshPage, 30000); 
        </script>
    </body>
</html>
