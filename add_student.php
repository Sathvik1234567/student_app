<?php
// database connection
$server = "localhost";
$username = "root";
$password = "";
$database = "student_records";

$success_msg = "";
$form_submitted = $_SERVER["REQUEST_METHOD"] == "POST";


$link = mysqli_connect($server, $username, $password, $database);
if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}
 
// Define variables and initialize with empty values
$first_name = $last_name = $age = $grade = "";
$first_name_err = $last_name_err = $age_err = $grade_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST")

// Validate first name
$input_first_name = isset($_POST["first_name"]) ? trim($_POST["first_name"]) : "";
if(empty($input_first_name)){
    $first_name_err = "Please enter a first name.";
} elseif(!preg_match("/^[a-zA-Z\s]+$/", $input_first_name)){
    $first_name_err = "Please enter a valid first name.";
} else{
    $first_name = $input_first_name;
}

// Validate last name
$input_last_name = isset($_POST["last_name"]) ? trim($_POST["last_name"]) : "";
if(empty($input_last_name)){
    $last_name_err = "Please enter a last name.";
} elseif(!preg_match("/^[a-zA-Z\s]+$/", $input_last_name)){
    $last_name_err = "Please enter a valid last name.";
} else{
    $last_name = $input_last_name;
}

// Validate age
$input_age = isset($_POST["age"]) ? trim($_POST["age"]) : "";
if(empty($input_age)){
    $age_err = "Please enter the age.";
} elseif(!ctype_digit($input_age)){
    $age_err = "Please enter a positive integer value.";
} elseif((int)$input_age < 5 || (int)$input_age > 18){
    $age_err = "Please enter an age between 5 and 18.";
} else{
    $age = (int)$input_age;
}

    // Validate grade
    $input_grade = trim($_POST["grade"]);
    if(empty($input_grade)){
        $grade_err = "Please enter the grade.";
    } elseif(!ctype_digit($input_grade)){
        $grade_err = "Please enter a positive integer.";
    } elseif((int)$input_grade < 0 || (int)$input_grade > 100){
        $grade_err = "Please enter an grade between 0 and 100.";
    } else {
        $grade = (int)$input_grade;
    }
    
    // Check input errors before inserting in database
    if(empty($first_name_err) && empty($last_name_err) && empty($age_err) && empty($grade_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO students (first_name, last_name, age, grade) VALUES (?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssis", $param_first_name, $param_last_name, $param_age, $param_grade);
            
            // Set parameters
        $param_first_name = $first_name;
        $param_last_name = $last_name;
        $param_age = (int)$age;
        $param_grade = $grade;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Clear form fields
                $success_msg = "Student added successfully!";
                $first_name = $last_name = $age = $grade = "";
            if(!empty($success_msg)){
                echo '<div class="alert alert-success">' . $success_msg . '</div>';
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
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
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add student record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" name="first_name" class="form-control <?php echo ($form_submitted && !empty($first_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $first_name; ?>">
                            <span class="invalid-feedback"><?php echo $first_name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" name="last_name" class="form-control <?php echo ($form_submitted && !empty($last_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $last_name; ?>">
                            <span class="invalid-feedback"><?php echo $last_name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Age</label>
                            <input type="text" name="age" class="form-control <?php echo ($form_submitted && !empty($age_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $age; ?>">
                            <span class="invalid-feedback"><?php echo $age_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Grade</label>
                            <input type="text" name="grade" class="form-control <?php echo ($form_submitted && !empty($grade_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $grade; ?>">
                            <span class="invalid-feedback"><?php echo $grade_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>