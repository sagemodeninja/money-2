<?php
    session_start();
    include_once "../user/login_guard.php";
    CheckLogin();
?>
<!DOCTYPE html>
<html lang="en-us">
<head>
    <?php include_once "../includes/layouts/common_head_items.php" ?>
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
                                <th>Shortcode</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Account No.</th>
                                <th>Bank Icon</th>
                                <th>Status</th>
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
                                        <label for="shortcode">Shortcode:</label>
                                    </td>
                                    <td>
                                        <input type="text" name="Shortcode" id="shortcode" max="5">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="title">Title:</label>
                                    </td>
                                    <td>
                                        <input type="text" name="Title" id="title" max="80">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="categoryId">Category:</label>
                                    </td>
                                    <td>
                                        <select name="CategoryId" id="categoryId">
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="accountNumber">Account No:</label>
                                    </td>
                                    <td>
                                        <input type="text" name="AccountNumber" id="accountNumber">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label for="bankIcon">Bank Icon:</label>
                                    </td>
                                    <td>
                                        <select name="BankIcon" id="bankIcon">
                                            <option value="">-- Select --</option>
                                            <option value="cimb">CIMB</option>
                                            <option value="gcash">GCash</option>
                                            <option value="master_card">Master Card</option>
                                            <option value="ubp">Union Bank PH</option>
                                            <option value="visa">Visa</option>
                                        </select>
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
    <script src="../assets/scripts/js/account.js"></script>
</body>
</html>