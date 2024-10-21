<?php
include "db.php";

// Include necessary files and establish database connection

// Fetch faculty details from the database
$sql = "SELECT id, name, email, classassg FROM faculty";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<tr>';
    echo '<th></th>';
    echo '<th>Id</th>';
    echo '<th>Name</th>';
    echo '<th>Email</th>';
    echo '<th>Class Assigned</th>';
    echo '<th>Operation</th>';
    echo '</tr>';

    while ($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td><input type="checkbox" name="deleteCheckbox" value="' . $row["id"] . '"></td>';
        echo '<td>' . $row["id"] . '</td>';
        echo '<td>' . $row["name"] . '</td>';
        echo '<td>' . $row["email"] . '</td>';
        echo '<td>' . $row["classassg"] . '</td>';
        echo '<td><a href="editfaculty.php?id=' . $row["id"] . '"><i class="fa-solid fa-user-pen" style="color: #324897;"></i></a> | ';
        echo '<a href="deletefaculty.php?id=' . $row["id"] . '" onclick="deleteRecord(' . $row["id"] . ')"><i class="fa-solid fa-trash" style="color: #324697;"></i></a></td>';
        echo '</tr>';
    }
} else {
    echo "<tr><td colspan='6' style=text-align:center;>No records found</td></tr>";
}

// Close database connection
$conn->close();
?>




