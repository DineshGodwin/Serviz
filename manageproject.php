<?php
session_start();
include "adminheader.php";
// Check if user is not logged in, redirect to login page
if (!isset($_SESSION['name'])) {
    // Display confirmation message before redirecting
    echo "<script>
            var confirmMsg = confirm('You need to login to access this page. Click OK to login.');
            if (confirmMsg) {
                window.location.href = 'facultylogin.php';
            }
          </script>";
    // Stop execution
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Search Page</title>
<style>
    
.search-bar {
    width: 45%;
    padding: 10px;
    font-size: 16px;
    margin-left: 50px;
    margin-top: 30px;
    position: relative; /* Add relative positioning */
}

.dropdown {
    width:45%;
    position: absolute; /* Change position to absolute */
    top: calc(100% + 10px); /* Position dropdown below the input */
    left: 345px; /* Align dropdown with the left edge of the input */
    position: absolute;
    background-color: #f9f9f9;
    max-height: 200px;
    overflow-y: auto;
    border: 1px solid #ddd;
    border-top: none;
    z-index: 2;
}
    .dropdown-item {
        padding: 10px;
        cursor: pointer;
        
    }
    .dropdown-item:hover {
        background-color: #f1f1f1;
    }
    label{
        margin-left:130px;
    }
   button{
            background-color: #324897;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 30px;
            cursor: pointer;
   }
</style>
</head>
<body>

<div class="container d-flex flex-column justify-content-center">
    <div class="title">
    <h1 style="text-align:center;margin-top:60px;margin-bottom:40px;">Project Repository</h1>
    <form id="searchForm" method="GET" action="projectDetails.php">
        <!-- Search bar for project title -->
        <label for="projectTitle">Search by Project Title:</label>
        <input type="text" id="projectTitle" name="projectTitle" class="search-bar" placeholder="Enter project title">
        <button type="submit" name="searchByTitle" onclick="return validateForm()">Submit</button>

        <div id="projectDropdown" class="dropdown"></div>
    </form>
    <br>
</div>
<div class="comm">
    <p style="text-align:center;">or</p>
    <form id="searchFormCommunity" method="GET" action="projectDetails.php">
        <!-- Search bar for community -->
        <label for="community">Search by Community:</label>
        <input type="text" id="community" name="community" class="search-bar" placeholder="Enter community">
        <button type="submit" name="searchByCommunity" onclick="return validateFormCommunity()">Submit</button>
        <div id="communityDropdown" class="dropdown"></div>
    </form>
</div>
    <?php
    include "footer.php";
    ?>
</div>
<script>

document.addEventListener("DOMContentLoaded", function() {
    var projectTitleInput = document.getElementById("projectTitle");
    var communityInput = document.getElementById("community");
    var projectDropdown = document.getElementById("projectDropdown");
    var communityDropdown = document.getElementById("communityDropdown");

    // Function to fetch suggestions from the server
    function fetchSuggestions(column, searchTerm, dropdownContainer) {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "getSuggestions.php?column=" + column + "&searchTerm=" + searchTerm, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    displaySuggestions(response, dropdownContainer);
                } else {
                    console.error("Error fetching suggestions");
                }
            }
        };
        xhr.send();
    }

    // Function to display suggestions in the dropdown
    function displaySuggestions(suggestions, dropdownContainer) {
        dropdownContainer.innerHTML = "";
        suggestions.forEach(function(suggestion) {
            var dropdownItem = document.createElement("div");
            dropdownItem.classList.add("dropdown-item");
            dropdownItem.textContent = suggestion;
            dropdownItem.addEventListener("click", function() {
                if (dropdownContainer.id === "projectDropdown") {
                    projectTitleInput.value = suggestion;
                } else if (dropdownContainer.id === "communityDropdown") {
                    communityInput.value = suggestion;
                }
                dropdownContainer.innerHTML = "";
            });
            dropdownContainer.appendChild(dropdownItem);
        });
    }

    // Function to validate the project title search form
    function validateForm(event) {
        var searchTerm = projectTitleInput.value.trim();
        if (searchTerm === "") {
            alert("Please enter a project title");
            event.preventDefault(); // Prevent form submission
        }
    }

    // Function to validate the community search form
    function validateFormCommunity(event) {
        var searchTerm = communityInput.value.trim();
        if (searchTerm === "") {
            alert("Please enter a community");
            event.preventDefault(); // Prevent form submission
        }
    }

    // Add event listeners to the forms for submission
    var projectForm = document.getElementById("searchForm");
    var communityForm = document.getElementById("searchFormCommunity");

    projectForm.addEventListener("submit", submitProjectForm);
    communityForm.addEventListener("submit", submitCommunityForm);

    function submitProjectForm(event) {
        validateForm(event);
    }

    function submitCommunityForm(event) {
        validateFormCommunity(event);
    }

    // Remove event listeners before navigating back
    window.addEventListener("beforeunload", function() {
        projectForm.removeEventListener("submit", submitProjectForm);
        communityForm.removeEventListener("submit", submitCommunityForm);
    });

// Event listener for project title input
projectTitleInput.addEventListener("input", function() {
    var searchTerm = projectTitleInput.value.trim();
    if (searchTerm !== "") {
        fetchSuggestions("projtitle", searchTerm, projectDropdown);
    } else {
        projectDropdown.innerHTML = ""; // Clear dropdown content
    }
});

// Event listener for community input
communityInput.addEventListener("input", function() {
    var searchTerm = communityInput.value.trim();
    if (searchTerm !== "") {
        fetchSuggestions("community", searchTerm, communityDropdown);
    } else {
        communityDropdown.innerHTML = ""; // Clear dropdown content
    }
});


});


</script>

</body>
</html>