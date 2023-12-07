<header>
    <nav class="navbar navbar-light">
        <div class="container-fluid justify-content-start gap-4">
            <button onclick="toggleSidebar()" class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="./index.php">My Website</a>
            <?php
                if (isset($_SESSION['user_name'])) {
                    echo '<ul class="navbar-nav ml-auto flex-row gap-4">
                            <li class="nav-item">
                                <a class="nav-link" href="index.php">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="addProduct.php">Add product</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="logout.php">Logout</a>
                            </li>
                        </ul>';
                } else {
                    echo '<ul class="navbar-nav ml-auto flex-row gap-4">
                            <li class="nav-item">
                                <a class="nav-link" href="index.php">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="login.php">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="register.php">Register</a>
                            </li>
                        </ul>';
                }
            ?>
        </div>
    </nav>
</header>
