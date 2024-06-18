(function () {
    const template = document.createElement("template");
    template.innerHTML = `
    <style>
    :host {
        align-items: center;
        background-color: rgba(3, 106, 196, 0.2);
        border-radius: 5px;
        color: var(--accent-fill-rest);
        cursor: pointer;
        display: flex;
        flex-grow: 1;
        font-family: var(--body-font);
        font-size: 14px;
        font-weight: 500;
        gap: 10px;
        justify-content: center;
        padding: 10px;
        user-select: none;
        -webkit-user-select: none;
    }

    :host(:hover) {
        background-color: rgba(3, 106, 196, 0.25);
    }
    
    :host(:active) {
        background-color: rgba(3, 106, 196, 0.3);
    }
    </style>
    <fluent-symbol-icon></fluent-symbol-icon> 
    <span>
        <slot></slot>
    </span>
    `;
    class ActionButton extends HTMLElement {
        constructor() {
            super();
            this.attachShadow({ mode: "open" });
            this.shadowRoot.appendChild(template.content.cloneNode(true));
        }
        static get observedAttributes() {
            return ["data-icon"];
        }
        /* Attributes */
        get icon() {
            return this.getAttribute("data-icon");
        }
        set icon(value) {
            this.setAttribute("data-icon", value);
            this.setIcon();
        }
        /* DOM */
        get symbolIcon() {
            var _a;
            (_a = this._symbolIcon) !== null && _a !== void 0 ? _a : (this._symbolIcon = this.shadowRoot.querySelector("fluent-symbol-icon"));
            return this._symbolIcon;
        }
        /* Functions */
        connectedCallback() {
            this.setIcon();
        }
        attributeChangedCallback(name) {
            if (name != "data-icon")
                return;
            this.setIcon();
        }
        setIcon() {
            this.symbolIcon.symbol = this.icon;
        }
    }
    customElements.define("action-button", ActionButton);
})();
class TransactionManager {
    constructor() {
        this.operation = Operation.Create;
    }
    loadAccount(account) {
        this.account = account;
        this.registerComponents();
        this.refresh();
    }
    refresh() {
        this.loadCard();
        this.loadBalances();
        this.loadTransactions();
    }
    registerComponents() {
        if (this.isRegistered)
            return;
        this.registerMenu();
        this.registerActions();
        this.registerEditor();
        this.isRegistered = true;
    }
    registerMenu() {
        // Menu
        this.contextMenu = globalContext.addMenu("transactions_row", this.container);
        // Options
        let options = ["Update", "Post", "Delete", "Cancel"];
        let menuOptions = options.reduce((mo, o) => {
            const option = new ContextMenuOption(o);
            option.visible((trans) => trans.Posted == (o == "Cancel"));
            mo.push(option);
            return mo;
        }, []);
        menuOptions[0].onClick(trans => this.updateBtnClicked(trans));
        menuOptions[1].onClick(trans => this.post(trans));
        menuOptions[2].onClick(trans => this.delete(trans));
        menuOptions[3].onClick(trans => this.cancel(trans));
        this.contextMenu.addOptions(...menuOptions);
    }
    registerActions() {
        const inputs = this.editor.querySelectorAll("form input");
        this.actions.forEach((action) => {
            const type = parseInt(action.dataset.action);
            action.addEventListener("click", () => {
                this.operation = Operation.Create;
                this.transactionType = type;
                clearForm();
                let typeInput = this.editor.querySelector("form input[name=TransactionType]");
                typeInput.value = type;
                let dateInput = this.editor.querySelector("form input[name=Date]");
                dateInput.value = DateTime.now().toString("yyyy-MM-dd");
                this.editor.show();
                this.changeTheme("#999999");
            });
        });
        function clearForm() {
            inputs.forEach(input => {
                input.value = input.type != "number" ? "" : "0.00";
            });
        }
    }
    registerEditor() {
        const amountInput = this.editor.querySelector("form input#amount");
        const amountInputHidden = this.editor.querySelector("form input[name=Amount]");
        amountInput.addEventListener("input", () => {
            let amount = parseFloat(amountInput.value);
            if (this.transactionType == TransactionType.Withdraw)
                amount *= -1;
            amountInputHidden.value = amount;
        });
        // TODO: Refactor
        const dissmissEditorBtn = document.querySelector("#dismiss_editor_dialog_btn");
        dissmissEditorBtn.addEventListener("click", () => {
            this.changeTheme("#dadada");
            this.editor.hide();
        });
        $("#save_btn").click(() => this.save());
    }
    loadCard() {
        const account = this.account;
        this.card.title = account.Title;
        this.card.number = account.AccountNumber;
        this.card.category = account.Category;
    }
    loadBalances() {
        const data = { accountId: this.account.Id };
        const balances = this.card.querySelectorAll("card-balance");
        axios.get("account/balance.php", { params: data })
            .then(response => {
            const payload = response.data;
            const content = payload.content;
            if (!payload.state) {
                alert(`Oops! ${content}`);
                return;
            }
            balances[0].innerText = toCurrency(content.Balance);
            balances[1].innerText = toCurrency(content.Projection);
        })
            .catch(error => {
            alert("An error occured.");
            console.log(error);
        });
    }
    loadTransactions() {
        const data = { AccountId: this.account.Id };
        axios.get("transaction/read.php", { params: data })
            .then(response => {
            const payload = response.data;
            const content = payload.content;
            if (!payload.state) {
                this.container.innerHTML = `<p class="centered">Oops! ${content}</p>`;
                return;
            }
            this.container.innerHTML = null;
            let transactions = this.groupTransactions(content);
            for (let key in transactions) {
                const group = this.newGroup(key, transactions[key]);
                this.container.appendChild(group);
            }
        })
            .catch(error => {
            alert("An error occured.");
            console.log(error);
        });
    }
    groupTransactions(trans) {
        var _a;
        let groups = {};
        for (let t of trans) {
            const key = t.Date + t.Posted;
            ((_a = groups[key]) !== null && _a !== void 0 ? _a : (groups[key] = [])).push(t);
        }
        return groups;
    }
    newGroup(date, trans) {
        let group = $("<div>").addClass("transaction-group");
        let header = $("<p>").addClass("transaction-group-header");
        let body = $("<div>").addClass("transaction-group-body");
        // Title/header...
        let dateTime = DateTime.parse(date.slice(0, -1));
        header.text(dateTime.toString("MMM. dd, yyyy"));
        const status = trans[0].Posted ? "actual" : "projection";
        header.addClass(status);
        group.append(header);
        group.append(body);
        for (let t of trans) {
            let row = this.newRow(t);
            body.append(row);
        }
        // TODO: Refactor
        return group[0];
    }
    newRow(trans) {
        // TODO: Refactor?
        const status = trans.Posted ? "actual" : "projection";
        let row = $(`<div class="transaction-row ${status}">`);
        let main = $("<div>").addClass("main-content");
        let desc = $(`<div class='transaction-description'><p>${trans.Description}</p></div>`);
        let summary = $("<div class='transaction-summary'>");
        main.append(desc);
        main.append(summary);
        row.append(main);
        let isDebit = trans.Total >= 0;
        let transAmount = Math.abs(trans.Total);
        let amount = $(`<p>${!isDebit ? "-" : ""} PHP ${toCurrency(transAmount.toString())}</p>`);
        let ref = $("<p>REF: N/A</p>");
        summary.append(amount);
        summary.append(ref);
        row[0].addContext(this.contextMenu, trans);
        const actions = $("<div>").addClass("actions-container");
        row.append(actions);
        if (status === "projection") {
            const editAction = this.newAction("edit", "Edit");
            const postAction = this.newAction("post", "CompletedSolid");
            const deleteAction = this.newAction("delete", "Delete");
            editAction.click(() => {
                collapseActions();
                this.updateBtnClicked(trans);
            });
            postAction.click(() => {
                collapseActions();
                this.post(trans);
            });
            deleteAction.click(() => {
                collapseActions();
                this.delete(trans);
            });
            actions.append(editAction);
            actions.append(postAction);
            actions.append(deleteAction);
        }
        else {
            const cancelAction = this.newAction("delete", "Cancel");
            actions.append(cancelAction);
            cancelAction.click(() => {
                collapseActions();
                this.cancel(trans);
            });
        }
        // Touch events...
        let initialTouch;
        let initialLeft;
        main[0].addEventListener("touchstart", e => {
            initialTouch = e.changedTouches[0];
            initialLeft = parseInt(main.css("left"));
        });
        main[0].addEventListener("touchmove", e => {
            const touch = Array.from(e.changedTouches)
                .find(tch => tch.identifier === initialTouch.identifier);
            if (touch === undefined) {
                console.log("No matches found of the initial touch.");
                return;
            }
            const xDelta = touch.pageX - initialTouch.pageX;
            const yDelta = touch.pageY - initialTouch.pageY;
            const left = Math.min(0, initialLeft + xDelta);
            if (Math.abs(xDelta) > Math.abs(yDelta))
                e.preventDefault();
            main.css({ left: left });
        });
        main[0].addEventListener("touchend", e => {
            const touch = Array.from(e.changedTouches)
                .find(tch => tch.identifier === initialTouch.identifier);
            if (touch === undefined) {
                console.log("No matches found of the initial touch.");
                return;
            }
            const left = parseInt(main.css("left"));
            const actionWidth = actions[0].clientWidth;
            const threshold = actionWidth / 2;
            const snapPoint = actionWidth * (Math.abs(left) > threshold);
            anime({
                targets: main[0],
                left: -snapPoint,
                duration: 200,
                easing: "easeInOutQuad"
            });
        });
        function collapseActions() {
            anime({
                targets: main[0],
                left: 0,
                duration: 200,
                easing: "easeInOutQuad"
            });
        }
        return row;
    }
    newAction(name, symbol) {
        const action = $(`<div class="action action-${name}" tabindex="-1">`);
        const icon = $(`<fluent-symbol-icon></fluent-symbol-icon>`);
        // Icon
        action.append(icon);
        icon.attr("symbol", symbol);
        icon.attr("font-size", 20);
        icon.attr("foreground", "#fff");
        return action;
    }
    updateBtnClicked(data) {
        this.operation = Operation.Update;
        let inputs = this.editor.querySelectorAll("form input");
        inputs.forEach(input => {
            let name = input.name;
            if (name == "Amount")
                return;
            input.value = name != "" // Empty
                ? data[name]
                : Math.abs(data.Amount);
        });
        this.transactionType = data.TransactionType;
        this.changeTheme("#999999");
        this.editor.show();
    }
    save() {
        const operation = Operation[this.operation].toLowerCase();
        const endpoint = `transaction/${operation}.php`;
        // TODO: Refactor
        let form = this.editor.querySelector("form");
        let data = new FormData(form);
        data.append("AccountId", this.account.Id.toString());
        let trans = Object.fromEntries(data.entries());
        axios
            .post(endpoint, trans)
            .then(response => {
            if (response.data.state)
                this.refresh();
            this.operation = Operation.Create;
            this.editor.hide();
            this.changeTheme("#dadada");
        })
            .catch(error => {
            console.log(error);
        });
    }
    delete(trans) {
        this.handlePost("transaction/delete.php", trans);
    }
    post(trans) {
        this.handlePost("transaction/post.php", trans);
    }
    cancel(trans) {
        this.handlePost("transaction/cancel.php", trans);
    }
    handlePost(endpoint, trans) {
        axios
            .post(endpoint, trans)
            .then(response => {
            if (response.data.state)
                this.refresh();
        })
            .catch(error => {
            console.log(error);
        });
    }
    changeTheme(theme) {
        document.querySelector(`meta[name="theme-color"]`)
            .setAttribute("content", theme);
    }
}
