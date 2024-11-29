<?php
include "db_conn.php";

if (isset($_POST['submit'])) {
 
    $s_num = $_POST['s_number'];
    $s_fn = $_POST['s_fn'];
    $s_mn = $_POST['s_mn'];
    $s_ln = $_POST['s_ln'];
    $s_gender = $_POST['s_gender'];
    $s_bday = $_POST['s_birthday'];

    $s_contact = $_POST['s_contact'];
    $s_street = $_POST['s_street'];
    $s_town = $_POST['s_town'];
    $s_province = $_POST['s_province'];
    $s_zip_code = $_POST['s_zip_code'];

    $conn->begin_transaction();
    try {
       
        $sql = "INSERT INTO students (student_number, first_name, middle_name, last_name, gender, birthday) 
                VALUES ('$s_num', '$s_fn', '$s_mn', '$s_ln', '$s_gender', '$s_bday')";
        if (!mysqli_query($conn, $sql)) {
            throw new Exception("Error inserting into students table: " . mysqli_error($conn));
        }
    
       
        $student_id = mysqli_insert_id($conn);
    
        $sql = "INSERT INTO student_details (student_id, contact_number, street, town_city, province, zip_code) 
                VALUES ('$student_id', '$s_contact', '$s_street', '$s_town', '$s_province', '$s_zip_code')";
        if (!mysqli_query($conn, $sql)) {
            throw new Exception("Error inserting into student_details table: " . mysqli_error($conn));
        }
    
       
        $conn->commit();
        echo "New student record added successfully.";
    } catch (Exception $e) {
       
        $conn->rollback();
        echo "Failed: " . $e->getMessage();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student Record</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
        }
        form {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        input, select, button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        button {
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .form-group {
            margin-bottom: 15px;
        }
    </style>
    <script>
        function validateForm() {
            var fields = document.querySelectorAll('input, select');
            for (var i = 0; i < fields.length; i++) {
                if (fields[i].value === "") {
                    alert("Please fill out all fields.");
                    return false;
                }
            }
            return true;
        }
    </script>
</head>
<body>

    <h1>Add New Student</h1>

    <form action="" method="post" onsubmit="return validateForm()">
        <div class="form-group">
            <label for="s_number">Student Number:</label>
            <input type="text" name="s_number" id="s_number" required>
        </div>
        
        <div class="form-group">
            <label for="s_fn">First Name:</label>
            <input type="text" name="s_fn" id="s_fn" required>
        </div>
        
        <div class="form-group">
            <label for="s_mn">Middle Name:</label>
            <input type="text" name="s_mn" id="s_mn" required>
        </div>

        <div class="form-group">
            <label for="s_ln">Last Name:</label>
            <input type="text" name="s_ln" id="s_ln" required>
        </div>

        <div class="form-group">
            <label for="s_gender">Gender:</label>
            <select name="s_gender" id="s_gender" required>
                <option value="">Select Gender</option>
                <option value="0">Male</option>
                <option value="1">Female</option>
            </select>
        </div>

        <div class="form-group">
            <label for="s_birthday">Birthday:</label>
            <input type="date" name="s_birthday" id="s_birthday" required>
        </div>

        <div class="form-group">
            <label for="s_contact">Contact Number:</label>
            <input type="text" name="s_contact" id="s_contact" required>
        </div>

        <div class="form-group">
            <label for="s_street">Street Name:</label>
            <input type="text" name="s_street" id="s_street" required>
        </div>

        <div class="form-group">
            <label for="s_town">Town Name:</label>
            <input type="text" name="s_town" id="s_town" required>
        </div>

        <div class="form-group">
            <label for="s_province">Province Name:</label>
            <input type="text" name="s_province" id="s_province" required>
        </div>

        <div class="form-group">
            <label for="s_zip_code">Zip Code:</label>
            <input type="text" name="s_zip_code" id="s_zip_code" required>
        </div>

        <button type="submit" name="submit">Submit</button>
    </form>

</body>
</html>
