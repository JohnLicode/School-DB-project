<?php
include "db_conn.php";

$student_data = null;
$student_details = null; 

if (isset($_POST['submit'])) {
    
    $s_id = $_POST['students_id']; 
    $new_s_num = $_POST['new_s_number']; 
    $new_s_id = $_POST['new_s_id']; 
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
      
        $sql = "UPDATE students 
                SET id = '$new_s_id', student_number = '$new_s_num', first_name = '$s_fn', middle_name = '$s_mn', 
                    last_name = '$s_ln', gender = '$s_gender', birthday = '$s_bday' 
                WHERE id = '$s_id'"; 
        if (!mysqli_query($conn, $sql)) {
            throw new Exception("Error updating student record in students table: " . mysqli_error($conn));
        }

      
        $sql = "UPDATE student_details 
                SET student_id = '$new_s_id' 
                WHERE student_id = '$s_id'"; 
        if (!mysqli_query($conn, $sql)) {
            throw new Exception("Error updating student details in student_details table: " . mysqli_error($conn));
        }

       
        $sql = "UPDATE student_details 
                SET contact_number = '$s_contact', street = '$s_street', town_city = '$s_town', 
                    province = '$s_province', zip_code = '$s_zip_code' 
                WHERE student_id = '$new_s_id'"; 
        if (!mysqli_query($conn, $sql)) {
            throw new Exception("Error updating additional details in student_details table: " . mysqli_error($conn));
        }

        
        $conn->commit();
        echo "Student ID and details updated successfully.";

    } catch (Exception $e) {
        
        $conn->rollback();
        echo "Failed: " . $e->getMessage();
    }
}


if (isset($_POST['fetch'])) {
    $fetch_s_id = $_POST['students_id'];
    
   
    $sql = "SELECT * FROM students WHERE id = '$fetch_s_id'";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $student_data = mysqli_fetch_assoc($result);
        
        
        $sql_details = "SELECT * FROM student_details WHERE student_id = '$fetch_s_id'"; 
        $result_details = mysqli_query($conn, $sql_details);
        if ($result_details && mysqli_num_rows($result_details) > 0) {
            $student_details = mysqli_fetch_assoc($result_details);
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<link rel='stylesheet' href='updatestyle.css'>
<head>
    <title>Update Student Record</title>
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
    <?php if (!$student_data): ?>
        
        <form action="" method="post" onsubmit="return validateForm()">
            <label>Enter Student ID to Fetch Data:</label> 
            <input type="text" name="students_id" placeholder="Student ID" required>
            <button type="submit" name="fetch">Fetch Data</button><br><br>
        </form>
    <?php endif; ?>

    <?php if ($student_data && $student_details): ?>
        
        <form action="" method="post" onsubmit="return validateForm()">
            <h2>Student Information</h2>
            <label>Current Student ID:</label>
            <input type="text" name="students_id" value="<?php echo $student_data['id']; ?>" readonly><br><br>

            <label>New Student ID (Primary Key):</label>
            <input type="text" name="new_s_id" value="<?php echo $student_data['id']; ?>" required><br><br>

            <label>New Student Number:</label>
            <input type="text" name="new_s_number" value="<?php echo $student_data['student_number']; ?>" required><br><br>

            <label>First Name:</label>
            <input type="text" name="s_fn" value="<?php echo $student_data['first_name']; ?>" required><br><br>

            <label>Middle Name:</label>
            <input type="text" name="s_mn" value="<?php echo $student_data['middle_name']; ?>" required><br><br>

            <label>Last Name:</label>
            <input type="text" name="s_ln" value="<?php echo $student_data['last_name']; ?>" required><br><br>

            <label>Gender:</label>
            <select name="s_gender" required>
                <option value="0" <?php echo $student_data['gender'] == '0' ? 'selected' : ''; ?>>Male</option>
                <option value="1" <?php echo $student_data['gender'] == '1' ? 'selected' : ''; ?>>Female</option>
            </select><br><br>

            <label>Birthday:</label>
            <input type="date" name="s_birthday" value="<?php echo $student_data['birthday']; ?>" required><br><br>

          
            <label>Contact Number:</label>
            <input type="text" name="s_contact" value="<?php echo $student_details['contact_number']; ?>"><br><br>

            <label>Street Name:</label>
            <input type="text" name="s_street" value="<?php echo $student_details['street']; ?>"><br><br>

            <label>Town Name:</label>
            <input type="text" name="s_town" value="<?php echo $student_details['town_city']; ?>"><br><br>

            <label>Province Name:</label>
            <input type="text" name="s_province" value="<?php echo $student_details['province']; ?>"><br><br>

            <label>Zip Code:</label>
            <input type="text" name="s_zip_code" value="<?php echo $student_details['zip_code']; ?>"><br><br>

            <button type="submit" name="submit">Update</button>
        </form>
    <?php endif; ?>
</body>
</html>
