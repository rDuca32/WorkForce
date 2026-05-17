<header>
    <nav>
        <img class="logo" src="assets/logo.png" alt="logo">
        <ul class="menu">
            <li><a href="index.php">Acasă</a></li>

            <?php if (!isset($_SESSION['user_id'])): ?>
                <li>
                    <a href="auth.php">Autentificare <i class="fa-solid fa-chevron-down menu-icon"></i></a>
                    <ul class="submenu">
                        <li><a href="login.php">Conectare</a></li>
                        <li><a href="register.php">Înregistrare</a></li>
                    </ul>
                </li>
            <?php endif; ?>

            <li><a href="tasks.php">Sarcini</a></li>

            <li>
                <a href="worksites.php">Șantiere <i class="fa-solid fa-chevron-down menu-icon"></i></a>
                <ul class="submenu">
                    <li><a href="progress.php">Progres</a></li>
                    <li><a href="gallery.php">Galerie</a></li>
                </ul>
            </li>

            <li><a href="users.php">Utilizatori</a></li>

            <?php if (isset($_SESSION['user_id'])): ?>
                <li>
                    <a href="profile.php" title="Profilul meu">
                        <i class="fa-solid fa-circle-user user-icon"></i> Profil
                    </a>
                </li>
                
                <li>
                    <a href="logout.php" class="logout-link">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout (<?php echo $_SESSION['username']; ?>)
                    </a>
                </li>
            <?php endif; ?>
            
        </ul>
    </nav>
</header>