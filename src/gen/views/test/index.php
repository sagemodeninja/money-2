<!DOCTYPE html>
<html lang="en-ph">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#f2f2f2" />

    <link rel="stylesheet" href="/static/fonts/segoe-fluent-icons/segoe-fluent-icons.css">
    <link rel="stylesheet" href="/static/fonts/segoe-ui-variable/segoe-ui-variable.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="/static/styles/core/Layout.css">
    <link rel="stylesheet" href="/static/styles/core/ContextMenu.css">
    <link rel="stylesheet" href="/static/styles/layout_core_compatible.css">

    

    <title>eMoney</title>
</head>
<body>
    <header>
        <h1 id="app_name_branding">Finance Tracker</h1>
        
        <a href="/user/logout.php">Logout</a> <!-- Production... -->
        <div class="person-picture">
            <img class="image" src="<?php #echo $userPicture; ?>" alt="User Photo">
        </div>
    </header>

    <fluent-navigation-view id="navigation_view" header="Home" pane-display-mode="left" header-src="tag" selects-on-load>
        <fluent-navigation-view-menu-items>
                <fluent-navigation-view-item icon="Home" content="Home" tag="Home" href="/index.php"></fluent-navigation-view-item>
                
                <fluent-navigation-view-item icon="NewFolder" content="Master files" selects-on-invoke="false">
                    <fluent-navigation-view-menu-items>
                        <fluent-navigation-view-item icon="AddFriend" content="Users" tag="Users" href="/user/index.php"></fluent-navigation-view-item>
                        <fluent-navigation-view-item icon="Tag" content="Categories" tag="Categories" href="/category/index.php"></fluent-navigation-view-item>
                        <fluent-navigation-view-item icon="ChipCardCreditCardReader" content="Accounts" tag="Accounts" href="/account/index.php"></fluent-navigation-view-item>
                    </fluent-navigation-view-menu-items>
                </fluent-navigation-view-item>
        </fluent-navigation-view-menu-items>
            
        <fluent-navigation-view-header-content style="flex-direction: column; margin-right: 10px; min-width: 0;">
            
        </fluent-navigation-view-header-content>

        <fluent-navigation-view-content-frame style="position: relative;">
            <!-- layout-body-content -->
            
        </fluent-navigation-view-content-frame>
    </fluent-navigation-view>

    

    <!-- Subject for deprecation. -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="/assets/scripts/js/Syntax.js"></script>

    <script src="https://cdn.jsdelivr.net/gh/sagemodeninja/fluent-icon-element-component@v1.0.2/src/fluent-icon-element.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/sagemodeninja/fluent-navigation-view-component@v1.3.10/src/fluent-navigation-view.min.js" type="module"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mousetrap/1.6.5/mousetrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/sagemodeninja/fluent-command-bar-component@v1.3.6/src/fluent-command-bar.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fluentui/web-components/dist/web-components.min.js" type="module"></script>
    <script src="/assets/scripts/js/navigation.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    
</body>
</html>