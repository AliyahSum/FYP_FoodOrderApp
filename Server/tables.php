<?php
session_start();
    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "mydb";
    
    $link = mysqli_connect($host, $user, $password, $db) or die(mysqli_connect_error());

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['num_customers']) && isset($_POST['table_number'])) {
    $num_customers = $_POST['num_customers'];
    $table_number = $_POST['table_number'];

    $query = "SELECT max_cust FROM cust_table WHERE table_num = '$table_number'";
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    $row = mysqli_fetch_assoc($result);
    $max_cust = $row['max_cust'];

    if ($num_customers <= $max_cust) {
        $_SESSION['num_customers'] = $num_customers;
        $_SESSION['table_number'] = $table_number;

        if (!isset($_SESSION['table_sessions'][$table_number])) {
            $_SESSION['table_sessions'][$table_number] = 0;
        }

        $_SESSION['table_sessions'][$table_number] ++;

        $query = "UPDATE cust_table SET isAvailable = 0 WHERE table_num = '$table_number'";
        mysqli_query($link, $query) or die(mysqli_error($link));

        echo json_encode(['status' => 'success', 'session_num' => $_SESSION['table_sessions'][$table_number]]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Exceeds maximum number of customers for this table.']);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Serving</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <style>
            .container {
                display: flex;
                flex-direction: column;
                align-items: center;
            }
            .role-buttons {
                display: grid;
                grid-template-columns: repeat(5, 1fr);
                gap: 125px;
                justify-items: center;
                max-width: 2000px;
                border-radius: 5px;
                margin-top: 30px;
                padding-bottom: 30px;
                padding-left: 50px;
                padding-right: 50px;
            }
            .role-button {
                width: 190px;
                height: 100px;
                font-size: 27px;
                text-align: center;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                margin-left: -100px;
            }
            .navbar {
                margin-bottom: 20px;
                padding: 10px;
            }
            .text-light {
                padding-left: 30px;
                padding-top: 20px;
            }
            .backnav {
                margin-left: 25px;
            }
            .nav-item1 {
                margin-left: -100px;
            }
            h4 {
                font-size: 40px;
                text-align: center;
                color: #ffffff;
                padding: 10px;
                padding-left: 250px;
            }
            .continue-button, .finalize-button {
                width: 400px;
                font-size: 25px;
            }
            .mb-3 {
                margin-left: 30px;
            }
            @media screen and (max-width: 820px) {
                .role-buttons {
                    grid-template-columns: repeat(3, 1fr);
                    max-width: 820px;
                    margin-top: 100px;
                    gap: -150px;
                }
                .role-button {
                    width: 180px;
                    height: 100px;
                    margin-top: -15px;
                    margin-bottom: 10px;
                    margin-left: -90px;
                }
            }
            @media screen and (max-width: 1180px) {
                .role-buttons {
                    grid-template-columns: repeat(3.5, 1fr);
                    gap: 110px;
                    max-width: 1180px;
                    margin-top: 70px;
                }
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-expand-xxl bg-dark">
            <div class="container-fluid">
                <a class="btn btn-outline-light backnav" href="../Login/role.php">Back</a>
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item1">
                        <h4>Serving</h4>
                    </li>
                </ul>
                <div>
                    <p class="text-light"><span id="current-date"></span></p>
                </div>
                <div>
                    <p class="text-light"><span id="current-time"></span></p>
                </div>
            </div>
        </nav>

        <div class="container">
            <div class="role-buttons">
                <?php
                $query = "SELECT * FROM cust_table";
                $result = mysqli_query($link, $query) or die(mysqli_error($link));
                while ($row = mysqli_fetch_assoc($result)) {
                    $btnClass = $row['isAvailable'] ? 'btn-primary' : 'btn-secondary';
                    $sessionNum = isset($_SESSION['table_sessions'][$row['table_num']]) ? $_SESSION['table_sessions'][$row['table_num']] : 0;
                    echo "<div class='col-md-3 mb-1'>";
                    echo "<button class='btn $btnClass btn-lg role-button' data-table-number='{$row['table_num']}' data-session='$sessionNum'>Table {$row['table_num']}</button>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>

        <div class="modal fade" id="customerModal" tabindex="-1" role="dialog" aria-labelledby="customerModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="customerModalLabel"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="customerForm">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="num_customers">Enter number of customers:</label>
                                <input type="number" class="form-control" id="num_customers" name="num_customers" required>
                                <input type="hidden" id="table_number" name="table_number">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Confirm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="optionsModal" tabindex="-1" role="dialog" aria-labelledby="optionsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="optionsModalLabel"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <a href="OrderMenu.php" class="btn btn-warning mb-3 continue-button" id="continueButton">Continue Serving</a>
                        <a href="bill.php" class="btn btn-success mb-3 finalize-button" id="finalizeButton">Finalize Order</a>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script>
            function updateDateTime() {
                const currentDate = new Date().toLocaleDateString();
                const currentTime = new Date().toLocaleTimeString();
                document.getElementById('current-date').innerText = currentDate;
                document.getElementById('current-time').innerText = currentTime;
            }

            window.onload = function () {
                updateDateTime();
                setInterval(updateDateTime, 1000);
            };
            $(document).on('click', '.role-button', function () {
                var tableNumber = $(this).data('table-number');
                var sessionNumber = $(this).data('session');

                if ($(this).hasClass('btn-secondary')) {
                    $('#optionsModal').find('.modal-title').text('Table ' + tableNumber);
                    $('#optionsModal').find('.continue-button').attr('href', 'OrderMenu.php?table_number=' + tableNumber);
                    $('#optionsModal').find('.finalize-button').attr('href', 'bill.php?table_number=' + tableNumber);
                    $('#optionsModal').modal('show');
                } else {
                    $('#customerModal').find('.modal-title').text('Table ' + tableNumber);
                    $('#customerModal').find('#table_number').val(tableNumber);
                    $('#customerModal').modal('show');
                }
            });

            $('#customerForm').on('submit', function (event) {
                event.preventDefault();
                var formData = $(this).serialize();
                $.post('', formData, function (response) {
                    var result = JSON.parse(response);
                    if (result.status === 'success') {
                        $('#customerModal').modal('hide');
                        setTimeout(function () {
                            var tableNumber = $('#table_number').val();
                            var btn = $('.btn[data-table-number="' + tableNumber + '"]');
                            var sessionNumber = result.session_num;
                            btn.data('session', sessionNumber).removeClass('btn-primary').addClass('btn-secondary');
                            $('#optionsModal .modal-title').text('Table ' + tableNumber);
                            $('#optionsModal').modal('show');
                        }, 500);
                    } else if (result.status === 'error') {
                        alert(result.message);
                    } else {
                        alert('Error updating table. Please try again.');
                    }
                });
            });

            $(document).on('click', '.finalize-button', function () {
                var tableNumber = $('#table_number').val();
                window.location.href = 'bill.php?table_number=' + tableNumber;
            });
            
            
                        function refreshPage() {
                window.location.reload();
            }
            setInterval(refreshPage, 10000); 
        </script>
    </body>
</html>
