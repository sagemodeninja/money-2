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

    
    <link rel="stylesheet" href="static/styles/dashboard.css">


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
            
    <fluent-command-bar default-label-position="right" style="align-self: flex-end;">
        <fluent-app-bar-button id="refresh_command" icon="Refresh" label="Refresh" modifier="Control+Alt" key="R"></fluent-app-bar-button>
        <fluent-app-bar-button id="toggle_closed_command" icon="View" label="Show Closed Accounts" modifier="Control+Alt" key="V"></fluent-app-bar-button>
    </fluent-command-bar>

        </fluent-navigation-view-header-content>

        <fluent-navigation-view-content-frame style="position: relative;">
            <!-- layout-body-content -->
            



<p>Hello World!</p>
<div id="layout_body"></div>




        </fluent-navigation-view-content-frame>
    </fluent-navigation-view>

    
    <transaction-panel id="transaction_panel">
        <div class="card-container">
            <account-card id="transaction_card">
                <card-balance data-title="Actual" id="running_balance"></card-balance>
                <card-balance data-title="Projection" id="projected_balance"></card-balance>
            </account-card>
        </div>
        <div class="action-container">
            <action-button data-icon="Add" data-action="0">Deposit</action-button>
            <action-button data-icon="Remove" data-action="1">Withdraw</action-button>
        </div>
        <div class="transaction-container"></div>
    </transaction-panel>

    <fluent-dialog id="editor_dialog" hidden="true">
        <div class="dialog_content">
            <div class="dialog-header">
                <span class="dialog-title">Transaction</span>
                <fluent-app-bar-button id="dismiss_editor_dialog_btn" key="Escape" class="dialog-dismiss-button">
                    <fluent-symbol-icon symbol="ChromeClose" font-size="12" slot="icon"></fluent-symbol-icon>
                </fluent-app-bar-button>
            </div>
            <div class="dialog-body">
                <form id="editor">
                    <input type="hidden" name="Id">
                    <input type="hidden" name="TransactionType">
                    <input type="hidden" name="Amount">
                    <table>
                        <tr>
                            <td>
                                <label for="date">Date:</label>
                            </td>
                            <td>
                                <input type="date" name="Date" id="date" placeholder="yyyy-mm-dd">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="description">Description:</label>
                            </td>
                            <td>
                                <input type="text" name="Description" id="description" max="100">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="amount">Amount:</label>
                            </td>
                            <td>
                                <input type="number" inputmode="decimal" id="amount">
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            <div class="dialog-footer">
                <fluent-button id="save_btn" appearance="accent">Save</fluent-button>
            </div>
        </div>
    </fluent-dialog>


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

    
    <script src="https://cdn.jsdelivr.net/npm/animejs@3.2.1/lib/anime.min.js"></script>

</body>
</html>