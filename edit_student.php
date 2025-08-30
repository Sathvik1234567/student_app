<?php
session_start();

// Database connection
$server = "localhost";
$username = "root";
$password = "";
$database = "student_records";

$link = mysqli_connect($server, $username, $password, $database);
if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

// Define variables
$first_name = $last_name = $age = $grade = "";
$first_name_err = $last_name_err = $age_err = $grade_err = "";

// Get ID from GET or POST
$id = isset($_GET["id"]) ? trim($_GET["id"]) : (isset($_POST["id"]) ? $_POST["id"] : "");

// If form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate first name
    $input_first_name = trim($_POST["first_name"]);
    if(empty($input_first_name)){
        $first_name_err = "Please enter a first name.";
    } elseif(!preg_match("/^[a-zA-Z\s]+$/", $input_first_name)){
        $first_name_err = "Please enter a valid first name.";
    } else {
        $first_name = $input_first_name;
    }

    // Validate last name
    $input_last_name = trim($_POST["last_name"]);
    if(empty($input_last_name)){
        $last_name_err = "Please enter a last name.";
    } elseif(!preg_match("/^[a-zA-Z\s]+$/", $input_last_name)){
        $last_name_err = "Please enter a valid last name.";
    } else {
        $last_name = $input_last_name;
    }

    // Validate age
    $input_age = trim($_POST["age"]);
    if(empty($input_age)){
        $age_err = "Please enter the age.";
    } elseif(!ctype_digit($input_age)){
        $age_err = "Please enter a positive integer.";
    } elseif((int)$input_age < 5 || (int)$input_age > 18){
        $age_err = "Please enter an age between 5 and 18.";
    } else {
        $age = (int)$input_age;
    }

    // Validate grade
    $input_grade = trim($_POST["grade"]);
    if(empty($input_grade)){
        $grade_err = "Please enter a grade.";
    } else {
        $grade = $input_grade;
    }

    // If no errors, update record
    if(empty($first_name_err) && empty($last_name_err) && empty($age_err) && empty($grade_err)) {
        $sql = "UPDATE students SET first_name=?, last_name=?, age=?, grade=? WHERE id=?";
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "ssisi", $first_name, $last_name, $age, $grade, $id);
            if(mysqli_stmt_execute($stmt)){
             // Store success message in session
              $_SESSION['success_msg'] = "Student updated successfully!";
                header("location: index.php");
                exit();
            } else {
                echo "Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    }

} else { 
    // Form not submitted: fetch current student
    if(!empty($id)){
        $sql = "SELECT * FROM students WHERE id=?";
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "i", $id);
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
                if(mysqli_num_rows($result) == 1){
                    $row = mysqli_fetch_assoc($result);
                    $first_name = $row["first_name"];
                    $last_name = $row["last_name"];
                    $age = $row["age"];
                    $grade = $row["grade"];
                } else {
                    header("location: error.php");
                    exit();
                }
            } else {
                echo "Something went wrong. Please try again later.";
            }
            mysqli_stmt_close($stmt);
        }
    } else {
        header("location: error.php");
        exit();
    }
}


// Close connection
mysqli_close($link);
?>

 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Update Record</h2>
                    <p>Please edit the input values and submit to update the employee record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" name="first_name" class="form-control <?php echo (!empty($first_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $first_name; ?>">
                            <span class="invalid-feedback"><?php echo $first_name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" name="last_name" class="form-control <?php echo (!empty($last_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $last_name; ?>">
                            <span class="invalid-feedback"><?php echo $last_name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Age</label>
                            <input type="text" name="age" class="form-control <?php echo (!empty($age_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $age; ?>">
                            <span class="invalid-feedback"><?php echo $age_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Grade</label>
                            <input type="text" name="grade" class="form-control <?php echo (!empty($grade_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $grade; ?>">
                            <span class="invalid-feedback"><?php echo $grade_err;?></span>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>