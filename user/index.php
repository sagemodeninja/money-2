<?php
    session_start();
    include_once "../user/login_guard.php";
    CheckLogin();
?>
<!DOCTYPE html>
<html lang="en-us">
<head>
    <?php include_once "../includes/layouts/common_head_items.php" ?>
    <title>Financial - Accounts</title>
    <link rel="stylesheet" href="../assets/styles/main.css">
</head>
<body>
    <?php include_once "../includes/layouts/header.php" ?>
    <fluent-navigation-view id="navigation_view" header="Home" pane-display-mode="left" header-src="tag" selects-on-load>
        <?php include_once "../includes/layouts/navigation_items.php" ?>
            
        <fluent-navigation-view-header-content>
            <button id="refresh_btn">Refresh</button>
            <button id="create_btn">Create</button>
        </fluent-navigation-view-header-content>

        <fluent-navigation-view-content-frame style="position: relative;">
            <!-- layout-body-content -->
            <div id="layout_body">
                <div id="layout_workspace">
                    <table id="table">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Firstname</th>
                                <th>Lastname</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="4" class="centered">Press "Refresh" to retrieve list.</td>
                            </tr>
                        </tbody>
                    </table>
        
                    <div id="editor_container">
                        <form id="editor">
                            <input type="hidden" name="Id">
                            <table>
                                <tr>
                                    <td>
                                        <label for="firstname">Firstname:</label>
                                    </td>
                                    <td>
                                        <input type="text" name="Firstname" id="firstname" max="20">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="lastname">Lastname:</label>
                                    </td>
                                    <td>
                                        <input type="text" name="Lastname" id="lastname" max="20">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="email">Email:</label>
                                    </td>
                                    <td>
                                        <input type="text" name="Email" id="email" max="320">
                                    </td>
                                </tr>
                            </table>
                            <button type="button" id="save_btn">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </fluent-navigation-view-content-frame>        
    </fluent-navigation-view>

    <!-- SCRIPTS -->
    <?php include_once "../includes/layouts/common_scripts.php"; ?>
    <script src="../assets/scripts/js/user.js"></script>
</body>
</html>