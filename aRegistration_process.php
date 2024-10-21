<?php
include "db.php";

function sanitizeInput($data) {
    global $conn;
    return mysqli_real_escape_string($conn, $data);
}

function isSltColumnNull($registerNumber) {
    global $conn;
    $query = "SELECT slt FROM student WHERE regno = '$registerNumber'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return ($row['slt'] == NULL);
    }
    else{
    echo "<script>alert('Register number $registerNumber is already in a team.');</script>";
    header("refresh:0.1;url=addteams.php");
    return false;
    }
}

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $teamLeaderName = sanitizeInput($_POST['teamLeaderName']);
        $registerNumber = sanitizeInput($_POST['registerNumber']);
        $section = sanitizeInput($_POST['section']);
        $num = sanitizeInput($_POST['num'] + 1);

        if (isSltColumnNull($registerNumber)) {
            // Insert into the team table
            $sql = "INSERT INTO team (teamleader, tlreg, class, no) VALUES ('$teamLeaderName', '$registerNumber', '$section', '$num')";
            
            if ($conn->query($sql) === TRUE) {
                // Get the last inserted ID (gid) from the team table
                $groupID = $conn->insert_id;

                $update = "UPDATE student SET slt = '$groupID' WHERE regno = '$registerNumber'";
                
                if ($conn->query($update) === TRUE) {
                    foreach ($_POST as $key => $value) {
                        if (strpos($key, 'memberRegisterNumber') !== false) {
                            $memberRegNumber = sanitizeInput($value);

                            if (!(isSltColumnNull($memberRegNumber))) {
                                echo "<script>alert('Register number $memberRegNumber is already in a team.');</script>";
                                header("refresh:0.1;url=addteams.php");
                            } else {
                                $updateQuery = "UPDATE student SET slt = '$groupID' WHERE regno = '$memberRegNumber'";
                                $conn->query($updateQuery);
                            }
                        }
                    }
                    header("Location: manageteams.php");
                    exit();

                } else {
                    throw new Exception("Error updating 'slt' column for team leader: " . $conn->error);
                }
            } else {
                throw new Exception("Error inserting into team table: " . $conn->error);
            }
        } else {
            echo "<script>alert('Team leader\\'s register number $registerNumber is already in a team.');</script>";
            header("refresh:0.1;url=addteams.php");
        }
    }
} catch (Exception $e) {
    echo "Caught exception: " . $e->getMessage();
}

// Close the database connection
$conn->close();
?>