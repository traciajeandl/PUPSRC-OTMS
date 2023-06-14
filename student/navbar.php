<?php
    session_start();
    
    if (!isset($_SESSION['user_id'])) {
        header('Location: http://localhost/index.php');
        exit;
    }

    $isLoggedIn = true;
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-maroon p-3">
    <div class="container-fluid">
        <img class="p-2" src="http://localhost/assets/pup-logo.png" alt="PUP Logo" width="40">
        <a class="navbar-brand" href="http://localhost/student/home.php"><strong>PUPSRC-OTMS</strong></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto order-2 order-lg-1">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="officeServicesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php
                            echo $office_name;
                        ?>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="officeServicesDropdown">
                        <li><a class="dropdown-item" href="http://localhost/student/registrar.php">Registrar</a></li>
                        <li><a class="dropdown-item" href="http://localhost/student/guidance.php">Guidance</a></li>
                        <li><a class="dropdown-item" href="http://localhost/student/academic.php">Academic</a></li>
                        <li><a class="dropdown-item" href="http://localhost/student/accounting.php">Accounting</a></li>
                        <li><a class="dropdown-item" href="http://localhost/student/administrative.php">Administrative Services</a></li>
                    </ul>
                </li>
                <?php if ($office_name != "Select an Office") { ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="officeServicesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Services List
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="officeServicesDropdown">
                        <?php switch ($office_name) { 
                            case 'Guidance Office':
                                echo '
                                <li><a class="dropdown-item" href="/student/guidance/counceling.php">Schedule Counceling</a></li>
                                <li><a class="dropdown-item" href="/student/guidance/good_morals.php">Request Good Moral Document</a></li>
                                <li><a class="dropdown-item" href="/student/guidance/clearance.php">Request Clearance</a></li>
                                ';
                                break;
                            case 'Academic Office':
                                echo '
                                <li><a class="dropdown-item" href="/student/academic/subject_overload.php">Subject Overload</a></li>
                                <li><a class="dropdown-item" href="/student/academic/grade_accreditation.php">Grade Accreditation</a></li>
                                <li><a class="dropdown-item" href="/student/academic/cross_enrollment.php">Cross-Enrollment</a></li>
                                <li><a class="dropdown-item" href="/student/academic/shifting.php">Shifting</a></li>
                                <li><a class="dropdown-item" href="/student/academic/manual_enrollment.php">Manual Enrollment</a></li>
                                <li><a class="dropdown-item" href="/student/academic/servicesinsistools.php">Services in SIS Tools</a></li>
                                ';
                                break;
                            case 'Administrative Office':
                                echo '
                                <li><a class="dropdown-item" href="/student/administrative/view-equipment.php">View Available Equipment</a></li>
                                <li><a class="dropdown-item" href="/student/administrative/view-facility.php">View Available Facilities</a></li>
                                ';
                                break;
                            case 'Registrar Office':
                                echo '
                                <li><a class="dropdown-item" href="/student/registrar/create_request.php">Create Request</a></li>
                                <li><a class="dropdown-item" href="/student/registrar/your_transaction.php">Your Registrar Transactions</a></li>
                                ';
                                break;
                            case 'Accounting Office':
                                echo '
                                <li><a class="dropdown-item" href="/student/accounting/payment1.php">Payment</a></li>
                                <li><a class="dropdown-item" href="/student/accounting/others/offsetting1.php">Offsetting</a></li>
                                <li><a class="dropdown-item" href="../transactions.php">Registrar Transaction History</a></li>
                                ';
                                break;
                            // Add more cases for other office services
                            }
                        ?>
                    </ul>
                </li>
                <?php } ?>
            </ul>
            <ul class="navbar-nav order-3 order-lg-3 w-50 gap-3">
                <div class="d-flex navbar-nav justify-content-center me-auto order-2 order-lg-1 w-100">
                    <form class="d-flex w-100" action="search.php" method="GET" onsubmit="return validateForm(this)">
                        <input class="form-control me-2" type="search" id="search" name="query" placeholder="Search for services..." aria-label="Search" minlength="5" maxlength="50" oninput="validateSearchInput(this)" onkeyup="handleSearchAutocomplete(this)" autocomplete="off">
                        <button class="btn search-btn" type="submit"><strong>Search</strong></button>
                    </form>
                    <div id="autocomplete-list" class="autocomplete-list"></div>
                </div>
                <li class="nav-item dropdown order-1 order-lg-2">
                    <a class="nav-link dropdown-toggle" href="#" id="userProfileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-user-circle me-1"></i>
                        <?php echo $_SESSION["first_name"] . " " . $_SESSION["last_name"]; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userProfileDropdown">
                        <li><a class="dropdown-item" href="http://localhost/student/transactions.php">My Transactions</a></li>
                        <li><a class="dropdown-item" href="#">Account Settings</a></li>
                        <li><a class="dropdown-item" href="http://localhost/student/generate_inquiry.php">Generate Inquiry</a></li>
                        <li><a class="dropdown-item" href="http://localhost/sign_out.php"><i class="fa-solid fa-right-from-bracket"></i> Log Out</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<script>
    function handleSearchAutocomplete(input) {
        var query = input.value.trim();
        var autocompleteList = document.getElementById('autocomplete-list');

        if (query === '') {
            // Clear autocomplete list if query is empty
            autocompleteList.style.display = 'none';
            return;
        }

        // Make an AJAX request to fetch autocomplete results
        $.ajax({
            url: '../autocomplete.php',
            method: 'POST',
            data: { query: query },
            success: function(response) {
                // Update the autocomplete list with the received results
                autocompleteList.innerHTML = response;
                autocompleteList.style.display = 'block';
            }
        });
    }

    function validateSearchInput(input) {
    var regex = /^[a-zA-Z\s]+$/; // Regular expression to allow only letters
    
    var value = input.value;
    var newValue = '';

    // Remove non-letter characters
    for (var i = 0; i < value.length; i++) {
        if (regex.test(value[i])) {
        newValue += value[i];
        }
    }

    input.value = newValue;
    }

    window.addEventListener('DOMContentLoaded', function() {
        var autocompleteList = document.getElementById('autocomplete-list');
        autocompleteList.style.display = 'none';
    });

    function validateForm(form) {
        var queryInput = form.querySelector('#search');
        var query = queryInput.value.trim();

        if (query === '' || queryInput.value.length <= 2) {
            // If query is empty, prevent form submission
            return false;
        }

        // If query is not empty, allow form submission
        return true;
    }
</script>