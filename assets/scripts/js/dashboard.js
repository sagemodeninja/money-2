var body;
var refreshCommand;
var toggleCommand;
var closedAccounts;
var contextMenu;
var transactionPanel;
var transaction;
$(document).ready(() => {
    var isShowClosed = false;
    body = document.querySelector("#layout_body");
    refreshCommand = document.querySelector("#refresh_command");
    toggleCommand = document.querySelector("#toggle_closed_command");
    transactionPanel = document.querySelector("transaction_panel");
    transaction = new TransactionManager();
    transaction.card = document.querySelector("#transaction_card");
    transaction.actions = document.querySelectorAll("action-button");
    transaction.container = document.querySelector(".transaction-container");
    transaction.editor = document.querySelector("#editor_dialog");
    closedAccounts = [];
    contextMenu = globalContext.addMenu("accounts_card", body);
    refreshCommand === null || refreshCommand === void 0 ? void 0 : refreshCommand.addEventListener("click", refreshAccounts);
    // Toggle show/hide closed accounts...
    toggleCommand === null || toggleCommand === void 0 ? void 0 : toggleCommand.addEventListener("click", () => {
        toggleCommand.icon = isShowClosed ? "View" : "Hide";
        toggleCommand.label = isShowClosed ? "Show Closed Accounts" : "Hide Closed Accounts";
        closedAccounts.forEach(card => {
            if (isShowClosed)
                card.hide();
            else
                card.show();
        });
        isShowClosed = !isShowClosed;
    });
    // Context menu options...
    let closeOption = new ContextMenuOption("Close");
    let deleteOption = new ContextMenuOption("Delete");
    closeOption.visible(d => d.Status === "Active");
    deleteOption.visible(d => d.Status === "Active");
    closeOption.onClick(acct => {
        let conf = confirm(`Close account \"${acct.Title}\"?`);
        if (conf) {
            $.ajax({
                url: "account/crud/close.php",
                method: "POST",
                data: { Id: acct.Id },
                dataType: "JSON",
                success: payload => {
                    if (payload.state) {
                        refreshCategories();
                    }
                    else {
                        alert(payload.content);
                    }
                }
            });
        }
    });
    deleteOption.onClick(acct => {
        let conf = confirm(`Delete account \"${acct.Title}\"?`);
        if (conf) {
            $.ajax({
                url: "account/crud/delete.php",
                method: "POST",
                data: { Id: acct.Id },
                dataType: "JSON",
                success: payload => {
                    if (payload.state) {
                        refreshCategories();
                    }
                    else {
                        alert(payload.content);
                    }
                }
            });
        }
    });
    contextMenu.addOptions(closeOption, deleteOption);
    refreshAccounts(); // Auto refresh @ start.
});
function refreshAccounts() {
    body.innerHTML = '<p style="text-align: center;">Fetching Accounts...</p>';
    axios.get("account/crud/read_categorized.php")
        .then(response => {
        body.innerHTML = null;
        let accounts = response.data;
        if (accounts.state) {
            let content = accounts.content;
            let categories = content.reduce((arr, acct) => {
                let categoryId = acct.CategoryId;
                if (!arr.some(cat => cat.Id == acct.CategoryId))
                    arr.push({
                        Id: categoryId,
                        Title: acct.Category,
                        Color: acct.CategoryColor
                    });
                return arr;
            }, []);
            let categorized = content.reduce((map, acct) => { var _a; return map.set(acct.CategoryId, [...(_a = map.get(acct.CategoryId)) !== null && _a !== void 0 ? _a : [], acct]); }, new Map());
            refreshBalances(categories, categorized);
        }
        else {
            body.innerHTML = `<p class="centered">Oops! ${accounts.content}</p>`;
        }
    })
        .catch(error => {
        console.dir(error);
    });
}
function refreshBalances(categories, categorized) {
    categorized.forEach((accounts, _category) => {
        var _a, _b, _c;
        let category = (_a = categories.find(cat => cat.Id == _category)) !== null && _a !== void 0 ? _a : {};
        let color = (_b = category === null || category === void 0 ? void 0 : category.Color) !== null && _b !== void 0 ? _b : "9E9E9E";
        let title = (_c = category === null || category === void 0 ? void 0 : category.Title) !== null && _c !== void 0 ? _c : "Uncategorized";
        const container = $('<div class="category"></div>');
        const accountsGrid = $('<div class="accounts-grid"></div>');
        const tag = $(`<fluent-symbol-icon symbol="Tag" foreground="#${color}" font-size="13" class="tag"></fluent-symbol-icon>`);
        const label = $(`<span class="title">${title}</span>`);
        body.appendChild(container[0]);
        body.appendChild(accountsGrid[0]);
        container.append(tag);
        container.append(label);
        $.each(accounts, (idx, account) => {
            let card = newCard(account, title);
            accountsGrid.append(card);
        });
    });
}
// HELPERS
function newCard(account, category) {
    var _a, _b;
    let accountNumber = (_b = (_a = account.AccountNumber) === null || _a === void 0 ? void 0 : _a.slice(-4)) !== null && _b !== void 0 ? _b : "••••";
    let bankIcon = account.BankIcon;
    const card = $("<account-card>");
    const runningBalance = $("<card-balance>...</card-balance>");
    const projectedBalance = $("<card-balance>...</card-balance>");
    const accountBankIcon = $('<img class="account-bank-icon" slot="icon">');
    card.append(runningBalance);
    card.append(projectedBalance);
    card.append(accountBankIcon);
    card.prop("title", account.Title);
    card.prop("number", accountNumber);
    card.prop("category", category);
    runningBalance.prop("title", "Actual");
    projectedBalance.prop("title", "Projection");
    if (bankIcon != null) {
        accountBankIcon.attr("src", `assets/images/bank_icons/${bankIcon}.svg`);
        if (bankIcon == "master_card")
            accountBankIcon.attr("style", "bottom:16px;height:35px;");
        if (bankIcon == "ubp" || bankIcon == "gcash")
            accountBankIcon.attr("style", "bottom:20px;height:30px;right:25px;");
        if (bankIcon == "cimb")
            accountBankIcon.attr("style", "bottom:20px;height:28px;right:25px;");
    }
    // Context menu...
    card[0].addContext(contextMenu, account);
    // Visibility...
    if (account.Status !== "Active") {
        closedAccounts.push(card);
        card.hide();
    }
    $.ajax({
        url: "account/balance.php?accountId=" + account.Id,
        method: "GET",
        dataType: "JSON",
        success: payload => {
            if (payload.state) {
                let balances = payload.content;
                runningBalance.text(toCurrency(balances.Balance));
                projectedBalance.text(toCurrency(balances.Projection));
            }
            else {
                runningBalance.text("!");
                projectedBalance.text("!");
                console.error(payload.content);
            }
        }
    });
    card.click(() => {
        transaction_panel.show();
        transaction.loadAccount(account);
    });
    return card;
}
function toCurrency(value) {
    return Intl.NumberFormat("en-US", { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(value);
}
