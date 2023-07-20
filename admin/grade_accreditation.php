<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Academic Office - Grade Accreditation Transactions</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="/assets/favicon.ico">
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style.css">
    <script src="https://kit.fontawesome.com/fe96d845ef.js" crossorigin="anonymous"></script>
    <script src="../node_modules/jquery/dist/jquery.min.js"></script>
    <script src="../node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
    <script src="../loading.js"></script>
	<script>
        // Wait for the page to load
        $(document).ready(function() {
            // Handle the dropdown change event
            $("#transaction-type").change(function() {
                var selectedOption = $(this).val();
                switch (selectedOption) {
                    case "":
                        // Redirect to academic.php when "All" is selected
                        window.location.href = "academic.php";
                        break;
                    case "subjectoverload":
                        // Redirect to subject_overload.php when "Subject Overload" is selected
                        window.location.href = "subject_overload.php";
                        break;
                    case "gradeaccreditation":
                        // No action needed, stay on the current page when "Grade Accreditation" is selected
                        break;
                    case "crossenrollment":
                        // Redirect to cross_enrollment.php when "Cross Enrollment" is selected
                        window.location.href = "cross_enrollment.php";
                        break;
                    case "shifting":
                        // Redirect to shifting.php when "Shifting" is selected
                        window.location.href = "shifting.php";
                        break;
                    case "manualenrollment":
                      // Redirect to manual_enrollment.php when "Manual Enrollment" is selected
                        window.location.href = "manual_enrollment.php";
                        break;
                    default:
                        // For any other case, do nothing
                        break;
                }
            });
        });
    </script>
</head>
<body>
    <?php
        // Include the database connection file (conn.php)
        include "../conn.php";

        $office_name = "Academic Office";
        include "navbar.php";

        // Avoid admin user from accessing other office pages
        if ($_SESSION['office_name'] != "Academic Office") {
            header("Location: http://localhost/admin/redirect.php");
            exit();
        }
    ?>
    <div class="wrapper">
        <!-- Loading page -->
        <!-- The container is placed here in order to display the loading indicator first while the page is loading. -->
        <div id="loader" class="center">
            <div class="loading-spinner"></div>
            <p class="loading-text display-3 pt-3">Getting things ready...</p>
        </div>
        <div class="container-fluid py-2">
            <div class="row">
                <div class="col-xs-12">
                    <div class="d-flex w-100 justify-content-between p-0">
                        <div class="d-flex p-2">
                            <select class="form-select" id="transaction-type">
                                <option value="">All</option>
                                <option value="subjectoverload">Subject Overload</option>
                                <option value="gradeaccreditation">Grade Accreditation</option>
                                <option value="crossenrollment">Cross-Enrollment</option>
                                <option value="shifting">Shifting</option>
                                <option value="manualenrollment">Manual Enrollment</option>
                            </select>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <div class="input-group mb-3 d-flex justify-content-end">
                                <input type="text" class="form-control" placeholder="Search...">
                                <button class="btn btn-outline-primary" type="button" id="button-addon2"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                    <table id="transactions-table" class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center" scope="col" style="background-color: #f2f2f2;">Requestor</th>
                                <th class="text-center" scope="col" style="background-color: #f2f2f2;">Application</th>
                                <th class="text-center" scope="col" style="background-color: #f2f2f2;">Transaction ID</th>
                                <th class="text-center" scope="col" style="background-color: #f2f2f2;">Completion Form</th>
                                <th class="text-center" scope="col" style="background-color: #f2f2f2;">Status</th>
                                <th class="text-center" scope="col" style="background-color: #f2f2f2;">Assessed fee</th>
                                <th class="text-center" scope="col" style="background-color: #f2f2f2;">Status</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                // Fetch data from the acad_subject_overload table with join queries
                                $query = "SELECT ao.transaction_id, u.user_id, u.student_no, ao.completion_form, ao.assessed_fee,  
                                          ao.completion_form_status, ao.assessed_fee_status
                                          FROM acad_grade_accreditation ao
                                          INNER JOIN users u ON ao.user_id = u.user_id";
                                $result = $connection->query($query);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . $row['student_no'] . "</td>";
                                        echo "<td>Grade Accreditation</td>";
                                        echo "<td>" . $row['transaction_id'] . "</td>";
                                        echo "<td>";

                                        // Display link to overload letter attachment, if available
                                        if (!empty($row['completion_form'])) {
                                            echo '<a href="../assets/uploads/generated_pdf/' . $row['completion_form'] . '" target="_blank" class="btn btn-primary">View attachment</a>';
                                        } else {
                                            echo 'No attachment';
                                        }

                                        echo "</td>";
                                        echo '<td><select class="form-select status-dropdown" data-request-id="' . $row['transaction_id'] . '">';

                                        // ... (code for generating and displaying the options for overload letter status dropdown) ...

                                        echo "</select></td>";

                                        echo "<td>";
                                        // Display link to Ace Form attachment, if available
                                        if (!empty($row['assessed_fee'])) {
                                            echo '<a href="../assets/uploads/user_uploads' . $row['assessed_fee'] . '" target="_blank" class="btn btn-primary">View attachment</a>';
                                        } else {
                                            echo 'No attachment';
                                        }
                                        echo "</td>";
                                        echo '<td><select class="form-select status-dropdown" data-request-id="' . $row['transaction_id'] . '">';

                                        // ... (code for generating and displaying the options for Ace Form status dropdown) ...

                                        echo "</select></td>";

                                        
                                    }
                                } else {
                                    echo "<tr><td colspan='9' class='text-center'>No records found.</td></tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="d-flex w-100 justify-content-between p-2">
                <!-- Add footer buttons here -->
            </div>
        </div>
        <div class="push"></div>
    </div>
    <?php include '../footer.php'; ?>
    <script>
        // ... (existing JavaScript code remains unchanged) ...
    </script>
</body>
</html>
