<?php

    if( $_SERVER["REQUEST_URI"] == '/socialmedia/messenger/messages.php'){
        $location = '../timeline.php';
    }else{
        $location = 'timeline.php';
    }
?>
<header>
    <nav class="navbar navbar-expand-lg nav-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand text-light ml-3" href="<?php echo $location;?>">DK CloneBook</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto mr-5 pr-3 mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link text-light search" href="search.php">Search</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-expanded="false">
                            <?php echo strtoupper($_SESSION["username"]);?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="/socialmedia/profile.php?id=<?php echo $_SESSION["user_id"];?>">Profile</a></li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>