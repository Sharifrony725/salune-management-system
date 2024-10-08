<?php
session_start();

if (!isset($_SESSION['user_name'])) {
    // If not logged in, redirect to login page
    header("Location: c_login.php");
    exit();
}

$email = $_SESSION['user_email'];
// Database connection
$host = 'localhost';
$db   = 'slms';
$user = 'root';
$pass = '';

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

        $sql = "SELECT MAX(fullname) as fullname, email,status, SUM(total) as total_sum, MAX(date_created) as last_date
        FROM appointment_list 
        WHERE status = '1' AND email= '$email' 
        GROUP BY email";
        $result = $conn->query($sql);
                 
    ?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salon Management Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #343a40;
            color: #ffffff;
        }

        .sidebar {
            background-color: #5a3921;
            height: 100vh;
            padding: 20px;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
        }

        .sidebar h2 {
            color: #fff;
            font-size: 20px;
            margin-bottom: 40px;
        }

        .sidebar a {
            color: #fff;
            padding: 10px 15px;
            display: block;
            margin-bottom: 10px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .sidebar a:hover {
            background-color: #6c757d;
        }

        .sidebar a.active {
            background-color: #7c4f34;
        }

        .content {
            margin-left: 260px;
            padding: 20px;
        }

        .content h1 {
            color: #fff;
            margin-bottom: 20px;
        }

        .card {
            background-color: #212529;
            border: none;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .card-header {
            background-color: #343a40;
            color: #fff;
        }

        .table thead th {
            background-color: #343a40;
            color: #fff;
        }

        .table tbody tr {
            background-color: #495057;
            color: #fff;
        }

        .table tbody tr {
            background-color: #495057;
            color: #fff;
        }

        .table tbody tr td {
            background-color: lightblue;
            color: #fff;
        }

        .table tbody tr:hover {
            background-color: #6c757d;
        }

        .btn-primary {
            background-color: #7c4f34;
            border: none;
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <h2>Salon Management</h2>
        <a href="#" class="active"><i class="fas fa-home"></i> Dashboard</a>
    </div>
    <div class="content">
        <h1>Welcome to <span class="user-name" style="color: violet;">
                <?php
                if (isset($_SESSION['user_name'])) {
                    echo $_SESSION['user_name'];
                } else {
                    echo "Guest";
                }
                ?>
            </span>
            <span><a href="logout.php" class="logout-btn" style="margin-left: 500px;">Logout</a></span>
        </h1>


        <div class="card">
            <div class="card-header">
                <h5>List of Appointments</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date Created</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                        </tr>
                        </tr>
                    </thead>
                   
                        <?php if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) { ?>
                             <tbody>
                            <tr>
                                <td>1</td>
                                <td><?php echo $row['last_date']; ?> </td>
                                <td><?php echo $row['fullname']?></td>
                                <td><?php echo $row['email']  ?></td>
                                <td><?php echo $row['total_sum'] ?></td>
                                <td>
                                    <?php if ($row['status'] == 1) { ?>
                                        <span class="badge btn-primary">Verified</span>
                                    <?php } else { ?>
                                        <span class="badge bg-warning">
                                            Pending</span>
                                    <?php  } ?>

                                </td>
                            </tr>
                        </tbody>
                       <?php }
                   } else {
                        echo "No results found.";
                    } ?>
                       
                </table>

            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.table').DataTable();
        });
    </script>
</body>

</html>