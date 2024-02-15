<?php $title = $_SESSION['header-title']; ?>


<nav class="navbar navbar-expand-lg sticky-top py-1 ">
    <div class="container-fluid">
        <img src="../images/GRC_Logo-Rich-Black.png" alt="GreenRiver College logo" id="grc-logo">
        <button type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" class="navbar-toggler"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse mx-1" id="navbar-menu">
            <ul class="navbar-nav nav-underline">
                <li><a href="/sprint3/index.php" class="nav-link">User Dashboard</a></li>
                <li><a href="/sprint3/signup_form.php" class="nav-link">Sign-up</a></li>
                <li><a href="/sprint3/application_form.php" class="nav-link">New Application</a></li>
                <li><a href="/sprint3/contact_form.php" class="nav-link">Contact</a></li>
                <li>
                    <ul class="navbar-nav nav-underline">
                        <li><a href="/sprint3/admin_dashboard.php" class="nav-link">Admin Dashboard</a></li>
                        <li><a href="/sprint3/admin_announcement.php" class="nav-link">Admin Announcement</a></li>
                    </ul>
                </li>
                <li class="d-flex justify-content-end" id="dark-mode-list-item">
                    <div class="dark-switch-outer">
                        <input type="checkbox" id="dark-mode-switch">
                        <label for="dark-mode-switch">
                            <i class="fas fa-sun"></i>
                            <i class="fas fa-moon"></i>
                        </label>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>