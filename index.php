<?php
session_start();

if(isset($_SESSION['success_msg'])){
    echo '<div class="alert alert-success">'.$_SESSION['success_msg'].'</div>';
    unset($_SESSION['success_msg']); // remove so show once only
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
        table tr td:last-child{
            width: 120px;
        }
    </style>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>
 <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">Students Details</h2>
                        <a href="add_student.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New Student</a>
                    </div>
                    
                    <?php
                    // database connection
                    $server = "localhost";
                    $username = "root";
                    $password = "";
                    $database = "student_records";

                    $link = mysqli_connect($server, $username, $password, $database);

                    if (!$link) {
                        die("Connection failed: " . mysqli_connect_error());
                    }

                    
                    // Attempt select query execution
                    $sql = "SELECT * FROM students";
                    if($result = mysqli_query($link, $sql)){
                        if(mysqli_num_rows($result) > 0){
                            echo '<table class="table table-bordered table-striped">';
                                echo "<thead>";
                                    echo "<tr>";
                                        echo "<th>ID</th>";
                                        echo "<th>First Name</th>";
                                        echo "<th>Last Name</th>";
                                        echo "<th>Age</th>";
                                        echo "<th>Grade</th>";
                                    echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while($row = mysqli_fetch_array($result)){
                                    echo "<tr>";
                                        echo "<td>" . $row['id'] . "</td>";
                                        echo "<td>" . $row['first_name'] . "</td>";
                                        echo "<td>" . $row['last_name'] . "</td>";
                                        echo "<td>" . $row['age'] . "</td>";
                                        echo "<td>" . $row['grade'] . "</td>";
                                        echo "<td>";
                                            echo '<a href="edit_student.php?id='. $row['id'] .'" class="mr-3" title="Edit Student" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                                            echo '<a href="delete_student.php?id='. $row['id'] .'" class="mr-3" title="Delete Student" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                echo "</tbody>";                            
                            echo "</table>";
                            // Free result set
                            mysqli_free_result($result);
                        } else{
                            echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                        }
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
 
                    // Close connection
                    mysqli_close($link);
                    ?>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>