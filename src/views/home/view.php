@section "styles" {
    <link rel="stylesheet" href="static/styles/dashboard.css">
}

@section "content-header" {
    <fluent-command-bar default-label-position="right" style="align-self: flex-end;">
        <fluent-app-bar-button id="refresh_command" icon="Refresh" label="Refresh" modifier="Control+Alt" key="R"></fluent-app-bar-button>
        <fluent-app-bar-button id="toggle_closed_command" icon="View" label="Show Closed Accounts" modifier="Control+Alt" key="V"></fluent-app-bar-button>
    </fluent-command-bar>
}

<p>Hello World!</p>
<div id="layout_body"></div>

@section "modals" {
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
}

@section "scripts" {
    <script src="https://cdn.jsdelivr.net/npm/animejs@3.2.1/lib/anime.min.js"></script>
}