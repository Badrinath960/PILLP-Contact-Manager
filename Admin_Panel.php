<!-- INSERT INTO `contact_details` (`srn`, `emp_id`, `emp_name`, `emp_phone`, `emp_email`, `department`, `address`, `timeStump`) VALUES (NULL, '1234567890', 'Badrinath Pandey', '9621733055', 'pandeybadrinath@gmail.com', 'IT', ' Ram Nagar Mangal Pandey Road Sama Vadodra Gujarat', current_timestamp()); -->

 <!-- for alert -->
 <?php
$insert = false;
$update = false;
$delete = false;

// Connect to the Database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "conatctmanagementsysytem";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// deletion part
if(isset($_GET['delete'])){
  $srn = $_GET['delete'];
  $delete = true;
  $sql = "DELETE FROM `contact_details` WHERE `srn` = $srn";
  $result = mysqli_query($conn, $sql);
} 
  
  //  updation part
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['srnEdit'])) {
        $srn = $_POST['srnEdit'];
        $emp_id = $_POST["idEdit"];
        $emp_name = $_POST["nameEdit"];
        $emp_phone = $_POST["phoneNumberEdit"];
        $emp_email = $_POST["emailEdit"];
        $department = $_POST["departmentEdit"];
        $address = $_POST["addressEdit"];

        $sql = "UPDATE `contact_details` SET `emp_id` = ?, `emp_name` = ?, `emp_phone` = ?, `emp_email` = ?, `department` = ?, `address` = ? WHERE `srn` = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssssssi", $emp_id, $emp_name, $emp_phone, $emp_email, $department, $address, $srn);
            if (mysqli_stmt_execute($stmt)) {
                // echo "Updated";
                $update = true;
            } else {
                echo "Error updating record: " . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        // Insertion part
        $emp_id = mysqli_real_escape_string($conn, $_POST["emp_id"]);
        $emp_name = mysqli_real_escape_string($conn, $_POST["emp_name"]);
        $emp_phone = mysqli_real_escape_string($conn, $_POST["emp_phone"]);
        $emp_email = mysqli_real_escape_string($conn, $_POST["emp_email"]);
        $department = mysqli_real_escape_string($conn, $_POST["department"]);
        $address = mysqli_real_escape_string($conn, $_POST["address"]);

        $sql = "INSERT INTO `contact_details` (`emp_id`, `emp_name`, `emp_phone`, `emp_email`, `department`, `address`) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssssss", $emp_id, $emp_name, $emp_phone, $emp_email, $department, $address);
            if (mysqli_stmt_execute($stmt)) {
                // echo "Inserted";
                $insert = true;
            } else {
                echo "Error inserting record: " . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/2.0.5/css/dataTables.dataTables.min.css">

  <title>Contact Management System</title>
</head>

<body>
<!-- Modal for Edit-->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit this Note</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="/ContactManagementSystem/login/Admin_Panel.php" method="post">
          <input type="hidden" name="srnEdit" id="srnEdit">
          <div class="mb-3">
            <label for="idEdit" class="form-label"><b>Employee ID:</b></label>
            <input type="text" class="form-control" id="idEdit" name="idEdit">
          </div>
          <div class="mb-3">
            <label for="nameEdit" class="form-label"><b>Name:</b></label>
            <input type="text" class="form-control" id="nameEdit" name="nameEdit">
          </div>
          <div class="mb-3">
            <label for="phoneNumberEdit" class="form-label"><b>Phone Number:</b></label>
            <input type="text" class="form-control" id="phoneNumberEdit" name="phoneNumberEdit">
          </div>
          <div class="mb-3">
            <label for="emailEdit" class="form-label"><b>Email ID:</b></label>
            <input type="email" class="form-control" id="emailEdit" name="emailEdit">
          </div>
          <div class="mb-3">
            <label for="departmentEdit" class="form-label"><b>Department:</b></label>
            <input type="text" class="form-control" id="departmentEdit" name="departmentEdit">
          </div>
          <div class="mb-3">
            <label for="addressEdit" class="form-label"><b>Address:</b></label>
            <textarea class="form-control" name="addressEdit" placeholder="Leave a comment here" id="addressEdit" style="height: 100px"></textarea>
          </div>
          <br>
          <button type="submit" class="btn btn-primary">Save changes</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal for View More -->
<div class="modal fade" id="viewMoreModal" tabindex="-1" aria-labelledby="viewMoreModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewMoreModalLabel">View More Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="viewMoreDetails">
          <!-- Content will be loaded here dynamically -->
        </div>
      </div>
    </div>
  </div>
</div>
  
  <!-- navigetion bar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <h2 style="color:white;" >PILLP Contact Manager</h2>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll"
        aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-center" id="navbarScroll">
        <ul class="navbar-nav mx-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#myTable">Conatct Data</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Updates</a>
          </li>
        </ul>
        <form action="">
        <button class="btn btn-outline-success" type="submit" name="logout">Logout</button>
        </form>
      </div>
    </div>
  </nav>

  <!-- Conformation alert for features like insert update delete -->
  <?php
    if($insert){
      echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
      <strong>Success!</strong> Contact Added Successfully.
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    }
  ?>
  <?php
    if($update){
      echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
      <strong>Success!</strong> Contact Updated Successfully.
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    }
  ?>
  <?php
    if($delete){
      echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
      <strong>Success!</strong> Conatct Deleted Successfully.
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    }
  ?>
   
   <!-- form of the insert contect table -->
  <div class="container my-5">
  <form action="/ContactManagementSystem/login/Admin_Panel.php" method="post" onsubmit="return validateForm()">
  <h3>Add New Contact Details :</h3>
      <div class="mb-4">
        <label for="emp_id" class="form-label"><b>Enter Employee ID :</b></label>
        <input type="number" class="form-control" id="emp_id" name="emp_id">
      </div>
      <div class="mb-4">
        <label for="emp_name" class="form-label"><b>Enter Full Name :</b></label>
        <input type="text" class="form-control" id="emp_name" name="emp_name">
      </div>
      <div class="mb-4">
        <label for="emp_phone" class="form-label"><b>Enter Phone Number :<b></label>
        <input type="tel" class="form-control" id="emp_phone" name="emp_phone">
      </div>
      <div class="mb-4">
        <label for="emp_email" class="form-label"><b>Enter Email ID :</b></label>
        <input type="email" class="form-control" id="emp_email" name="emp_email">
      </div>
      <div class="mb-4">
        <label for="department" class="form-label"><b>Enter Department Name :</b></label>
        <input type="text" class="form-control" id="department" name="department">
      </div>
      <div class="mb-3">
        <label for="address" class="form-label"></b>Enter Address :</b></label>
        <textarea class="form-control" id="address" name="address" placeholder="Enter Address"></textarea>
      </div>
      <br>
      <button type="submit" class="btn btn-primary btn-lg active"><b>Submit</b></button>
    </form>

  </div>

  <div class="container">
    <table class="table" id="myTable">
      <thead>
        <tr>
          <th scope="col"  class="table-warning">Sr.No</th>
          <th scope="col"  class="table-warning"> ID </th>
          <th scope="col"  class="table-warning">Name</th>
          <th scope="col"  class="table-warning">Phone Number</th>
          <th scope="col"  class="table-warning">Email Address</th>
          <th scope="col"  class="table-warning"> Department</th>
          <th scope="col"  class="table-warning"> Address</th>
          <th scope="col"  class="table-warning"> Actions</th>
        </tr>
      </thead>
      <tbody>
<?php
$sql = "SELECT * FROM `contact_details`";
$result = mysqli_query($conn, $sql);
$srn = 0;
// Fetch and display results
while ($row = mysqli_fetch_assoc($result)) {
    $srn++;
    echo "<tr>
            <th scope='row' class='table-info'>" . $srn . "</th>
            <td class='table-info'>" . $row['emp_id'] . "</td>
            <td class='table-info'>" . $row['emp_name'] . "</td>
            <td class='table-info'>" . $row['emp_phone'] . "</td>
            <td class='table-info'>" . $row['emp_email'] . "</td>
            <td class='table-info'>" . $row['department'] . "</td>
            <td class='table-info'>" . $row['address'] . "</td>
            <td>
                <div class='edit d-flex justify-content-between'>
                    <button type='button' class='btn btn-info btn-sm me-2 view-more' id='v" . $row['srn'] . "' data-bs-toggle='modal' data-bs-target='#viewMoreModal'>View More</button>
                    <button type='button' class='btn btn-danger btn-lg btn-sm me-2 edit' id='e" . $row['srn'] . "' data-bs-toggle='modal' data-bs-target='#editModal'>Edit</button>
                    <button type='button' class='btn btn-success btn-lg btn-sm me-2 delete' id='d" . $row['srn'] . "' data-bs-toggle='modal' data-bs-target='#editModal'>Delete</button>
                </div>
            </td>
        </tr>";
}
?>
      </tbody>
    </table>
  </div>

  <!-- Option 1: Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
    crossorigin="anonymous"></script>

   <!--  jQuery will be loaded before DataTables -->
   <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<!-- Include DataTables JavaScript library -->
<script src="//cdn.datatables.net/2.0.3/js/dataTables.min.js"></script>
  

<script>
  $(document).ready(function() {
    $('#myTable').DataTable(); //  #myTable is the ID of the table that I  turned into a DataTable
});
</script>
 
      <!-- Form validation -->
<script>
  // Function to validate Employee ID
  function validateEmpId() {
    var empId = document.getElementById("emp_id").value;
    // Check if empId is exactly 6 digits
    if (empId === "" || empId.length !== 6 || isNaN(empId)) {
      alert("Employee ID must be exactly 6 digits.");
      return false;
    }
    return true;
  }

  // Function to validate Phone Number
  function validatePhoneNumber() {
    var phoneNumber = document.getElementById("emp_phone").value;
    // Regex pattern to validate phone number (10 digits)
    var phonePattern = /^\d{10}$/;
    if (phoneNumber === "" || !phonePattern.test(phoneNumber)) {
      alert("Please enter a valid 10-digit phone number.");
      return false;
    }
    return true;
  }

  // Function to validate Email
  function validateEmail() {
    var email = document.getElementById("emp_email").value;
    // Regex pattern for email validation
    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (email === "" || !emailPattern.test(email)) {
      alert("Please enter a valid email address.");
      return false;
    }
    return true;
  }

  // Function to validate Department
  function validateDepartment() {
    var department = document.getElementById("department").value;
    if (department === "") {
      alert("Department cannot be empty.");
      return false;
    }
    return true;
  }

  // Function to validate Address
  function validateAddress() {
    var address = document.getElementById("address").value;
    if (address === "") {
      alert("Address cannot be empty.");
      return false;
    }
    return true;
  }

  // Function to validate Name
  function validateName() {
    var name = document.getElementById("emp_name").value;
    // Regex pattern to validate name (only alphabets and spaces)
    var namePattern = /^[A-Za-z\s]+$/;
    if (name === "" || !namePattern.test(name)) {
      alert("Please enter a valid name.");
      return false;
    }
    return true;
  }

  // Function to validate the entire form
  function validateForm() {
    // Call individual validation functions and return false if any of them fail
    if (!validateEmpId() || !validatePhoneNumber() || !validateEmail() || !validateDepartment() || !validateAddress() || !validateName()) {
      return false;
    }
    return true;
  }
</script>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Edit section
    var edits = document.getElementsByClassName('edit');

    Array.from(edits).forEach(function(element) {
        element.addEventListener("click", function(e) {
            e.preventDefault();

            var tr = e.target.closest('tr');

            if (tr) {
                var emp_idCell = tr.querySelector("td:nth-child(2)");
                var emp_nameCell = tr.querySelector("td:nth-child(3)");
                var emp_phoneCell = tr.querySelector("td:nth-child(4)");
                var emp_emailCell = tr.querySelector("td:nth-child(5)");
                var departmentCell = tr.querySelector("td:nth-child(6)");
                var addressCell = tr.querySelector("td:nth-child(7)");

                if (emp_idCell && emp_nameCell && emp_phoneCell && emp_emailCell && departmentCell && addressCell) {
                    var id = emp_idCell.innerText.trim();
                    var name = emp_nameCell.innerText.trim();
                    var phone = emp_phoneCell.innerText.trim();
                    var email = emp_emailCell.innerText.trim();
                    var department = departmentCell.innerText.trim();
                    var address = addressCell.innerText.trim();

                    // the values in the edit modal input fields
                    document.getElementById('idEdit').value = id;
                    document.getElementById('nameEdit').value = name;
                    document.getElementById('phoneNumberEdit').value = phone; // Corrected variable name
                    document.getElementById('emailEdit').value = email;
                    document.getElementById('departmentEdit').value = department;
                    document.getElementById('addressEdit').value = address;

                    var srn = e.target.id;
                    document.getElementById('srnEdit').value = srn.substr(1); // Removed 'd' prefix from ID

                    // Toggle Bootstrap modal
                    $('#editModal').modal('toggle');
                } else {
                    console.error("One or more cells not found.");
                }
            } else {
                console.error("Parent table row (tr) not found.");
            }
        });
    });

    // Delete section
    var deletes = document.getElementsByClassName('delete');

    Array.from(deletes).forEach(function(element) {
        element.addEventListener("click", function(e) {
            e.preventDefault();

            var srn = e.target.id.substr(1);

            // Confirm deletion
            if (confirm("Are you sure you want to delete this contact?")) {
                console.log("User confirmed deletion of sno: " + srn);

                // Redirect to delete endpoint (replace with appropriate URL)
                window.location = "/ContactManagementSystem/login/Admin_Panel.php?delete=" + srn;
            } else {
                console.log("Deletion canceled by user.");
            }
        });
    });

    // View More section
    var viewMores = document.getElementsByClassName('view-more');

    Array.from(viewMores).forEach(function(element) {
        element.addEventListener("click", function(e) {
            e.preventDefault();

            var tr = e.target.closest('tr');

            if (tr) {
                var emp_idCell = tr.querySelector("td:nth-child(2)");
                var emp_nameCell = tr.querySelector("td:nth-child(3)");
                var emp_phoneCell = tr.querySelector("td:nth-child(4)");
                var emp_emailCell = tr.querySelector("td:nth-child(5)");
                var departmentCell = tr.querySelector("td:nth-child(6)");
                var addressCell = tr.querySelector("td:nth-child(7)");

                if (emp_idCell && emp_nameCell && emp_phoneCell && emp_emailCell && departmentCell && addressCell) {
                    var id = emp_idCell.innerText.trim();
                    var name = emp_nameCell.innerText.trim();
                    var phone = emp_phoneCell.innerText.trim();
                    var email = emp_emailCell.innerText.trim();
                    var department = departmentCell.innerText.trim();
                    var address = addressCell.innerText.trim();

                    // Construct the HTML content for View More modal
                    var viewMoreContent = "<p><b>Employee ID:</b> " + id + "</p>" +
                                          "<p><b>Name:</b> " + name + "</p>" +
                                          "<p><b>Phone Number:</b> " + phone + "</p>" +
                                          "<p><b>Email:</b> " + email + "</p>" +
                                          "<p><b>Department:</b> " + department + "</p>" +
                                          "<p><b>Address:</b> " + address + "</p>";

                    // Set the content in the View More modal
                    document.getElementById('viewMoreDetails').innerHTML = viewMoreContent;
                  
                } else {
                    console.error("One or more cells not found.");
                }
            } else {
                console.error("Parent table row (tr) not found.");
            }
        });
    });
});
</script>

</body>

</html>