<?php
session_start();
$_SESSION['header-title'] = 'ATT - Signup Form';
include 'header.php'?>
<main>
    <div class="container p-3" id="main-container">
        <h3 class="form-header p-3 mb-0">Sign-up</h3>
        <div class="form-container mb-5">
            <div class="form-body">
                <form method="post" action="https://dragonfly.greenriverdev.com/sprint2/signupPHP.php" onsubmit="return validateForm()" class="my-3">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name*</label>
                        <div id="name" class="row mb-4">
                            <div class="col-sm">
                                <input type="text" class="form-control" name="firstName" placeholder="First name" aria-label="First name" required>
                            </div>
                            <div class="col-sm pt-sm-0 pt-2">
                                <input type="text" class="form-control" name="lastName" placeholder="Last name" aria-label="Last name" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="input-email" class="form-label">Email*</label>
                        <input type="email" class="form-control" id="input-email" name="email" placeholder="e.g. example@email.com" required>
                        <small id="email-message">Note: an @greenriver.edu email is preferred</small>
                    </div>

                    <div class="mb-3">
                        <label for="input-cohort-num" class="form-label">Cohort Number*</label>
                        <input type="number" class="form-control" id="input-cohort-num" name="cohort-num" min="1" max="100" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="seekingInternship" name="status" value="Seeking Internship">
                            <label for="seekingInternship" class="form-check-label">Seeking Internship</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="seekingJob" name="status" value="Seeking Job">
                            <label for="seekingJob" class="form-check-label">Seeking Job</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="notSearching" name="status" value="Not Actively Searching">
                            <label for="notSearching" class="form-check-label">Not Actively Searching</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="input-roles" class="form-label">What roles are you looking for?*</label>
                        <textarea class="form-control" id="input-roles" name="roles" minlength="50" maxlength="500" placeholder="Type here..." required></textarea>
                    </div>

                    <button type="submit" class="submit-btn">Submit</button>
                </form>
            </div>
        </div>
    </div>
</main>


<?php include 'footer.php' ?>
<script src="js/contactscript.js"></script>
</body>
</html>