<nav class="navbar navbar-dark bg-dark">
    <div class="container">
        <button class="btn btn-info" type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
            <span><i class="fas fa-bars"></i></span>
            <a class="navbar-brand" href="dashboard.php">Admin</a>
            <div class="collapse navbar-collapse" id="app-nav">
                <ul class="nav navbar-nav">
                    <li><a href="showMovies.php">Movies</a></li>
                    <li><a href="Admins.php">Members</a></li>
                </ul>
        </button>
        <button class="btn btn-info" type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
            <span><i class="fas fa-bars"></i></span>
            <a class="navbar-brand"><?php echo $_SESSION['Username'] ?></a>
            <div class="collapse navbar-collapse" id="app-nav">
                <ul class="nav navbar-nav">
                    <li><a href="Admins.php?do=Edit&ID=<?php echo $_SESSION['ID'] ?>">Edit Profile</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
        </button>

    </div>
    </div>
</nav>