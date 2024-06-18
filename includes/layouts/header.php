<?php
    $userEmail = @$_SESSION["user_email"];
    $userPicture = @$_SESSION["user_picture"];
?>

<header>
    <h1 id="app_name_branding">Finance Tracker</h1>
    
    <a href="/user/logout.php">Logout</a> <!-- Production... -->
    <div class="person-picture">
        <img class="image" src="<?php echo $userPicture; ?>" alt="User Photo">
    </div>
</header>