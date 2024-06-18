(function () {
    const template = document.createElement("template");
    template.innerHTML = `
    <style>
    :host {
        background-image: -webkit-linear-gradient(-45deg, #252A32 50%, #2d343e 50%);
        border-radius: 10px;
        box-shadow: 0 3px 6px rgb(0 0 0 / 16%), 0 3px 6px rgb(0 0 0 / 23%);
        box-sizing: border-box;
        color: #FFF;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
        height: 180px;
        max-height: 180px;
        max-width: 320px;
        min-height: 180px;
        min-width: 320px;
        outline: none;
        padding: 20px;
        position: relative;
        user-select: none;
        width: 320px;
    }
    
    :host::before {
        border: solid 3px #4D90FE;
        border-radius: 13px;
        box-sizing: border-box;
        content: "";
        display: none;
        height: calc(100% + 6px);
        left: -3px;
        position: absolute;
        top: -3px;
        width: calc(100% + 6px);
        z-index: 0;
    }
    
    :host:active::before {
        display: block;
    }
    
    .body {
        display: flex;
        flex-direction: column;
        height: 100%;
    }
    
    .body > .title {
        font-size: 16px;
        font-weight: 500;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .balances {
        align-items: center;
        display: flex;
        flex-grow: 1;
    }

    .numbers,
    .category {
        color: #ffffffcc;
        font-size: 13px;
        height: 13px;
        line-height: 13px;
        margin: 0;
    }

    .numbers {
        display: flex;
        margin-bottom: 8px;
    }
    
    .numbers span {
        margin-right: 8px;
    }

    ::slotted(img[slot=icon]) {
        bottom: 23px;
        height: 22px;
        position: absolute;
        right: 18px;
        shape-rendering: geometricPrecision;
    }
    </style>
    <div class="body">
        <span class="title"></span>
        <div class="balances">
            <slot></slot>
        </div>
        <p class="numbers">
            <span>••••</span>
            <span>••••</span>
            <span>••••</span>
            <span class="number">••••</span>
        </p>
        <span class="category"></span>
    </div>
    <slot name="icon"></slot>
    `;
    class AccountCard extends HTMLElement {
        constructor() {
            super();
            this.attachShadow({ mode: "open" });
            this.shadowRoot.appendChild(template.content.cloneNode(true));
        }
        static get observedAttributes() {
            return ["data-title", "data-number", "data-category"];
        }
        /* Attributes */
        get title() {
            return this.getAttribute("data-title");
        }
        set title(value) {
            this.setAttribute("data-title", value);
            this.setTitle();
        }
        get number() {
            return this.getAttribute("data-number");
        }
        set number(value) {
            this.setAttribute("data-number", value !== null && value !== void 0 ? value : "••••");
            this.setNumber();
        }
        get category() {
            return this.getAttribute("data-category");
        }
        set category(value) {
            this.setAttribute("data-category", value);
            this.setCategory();
        }
        /* DOM */
        get titleSpan() {
            var _a;
            (_a = this._titleSpan) !== null && _a !== void 0 ? _a : (this._titleSpan = this.shadowRoot.querySelector(".title"));
            return this._titleSpan;
        }
        get numberSpan() {
            var _a;
            (_a = this._numberSpan) !== null && _a !== void 0 ? _a : (this._numberSpan = this.shadowRoot.querySelector(".number"));
            return this._numberSpan;
        }
        get categorySpan() {
            var _a;
            (_a = this._categorySpan) !== null && _a !== void 0 ? _a : (this._categorySpan = this.shadowRoot.querySelector(".category"));
            return this._categorySpan;
        }
        /* Functions */
        connectedCallback() {
            this.setTitle();
            this.setNumber();
            this.setCategory();
        }
        attributeChangedCallback(name) {
            switch (name) {
                case "data-title":
                    this.setTitle();
                    break;
                case "data-number":
                    this.setNumber();
                    break;
                case "data-category":
                    this.setCategory();
                    break;
            }
        }
        setTitle() {
            this.titleSpan.innerHTML = this.title;
        }
        setNumber() {
            var _a;
            const number = (_a = this.number) === null || _a === void 0 ? void 0 : _a.slice(-4);
            this.numberSpan.innerHTML = number;
        }
        setCategory() {
            this.categorySpan.innerHTML = this.category;
        }
    }
    customElements.define("account-card", AccountCard);
})();
(function () {
    const template = document.createElement("template");
    template.innerHTML = `
    <style>
    :host {
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        gap: 5px;
    }

    .title {
        color: #ffffffcc;
        font-size: 13px;
        height: 13px;
        margin: 0;
    }
    
    .figure {
        font-size: 18px;
        font-weight: 500;
        height: 20px;
        line-height: 20px;
    }

    .figure.medium {
        font-size: 16px;
    }

    .figure.small {
        font-size: 15px;
    }

    .figure::after {
        display: inline-block;
        color: #ffffff80;
        font-size: 13px;
        font-weight: 500;
        line-height: 13px;
        margin-left: 5px;
        vertical-align: top;
    }

    .figure.medium::after {
        font-size: 12px;
        margin-left: 4px;
    }

    .figure.small::after {
        font-size: 11px;
        margin-left: 0;
    }

    .currency-php::after {
        content: "PHP";
    }
    </style>
    <span class="title">Projection</span>
    <span class="figure currency-php">
        <slot></slot>
    </span>
    `;
    class CardBalance extends HTMLElement {
        constructor() {
            super();
            this.attachShadow({ mode: "open" });
            this.shadowRoot.appendChild(template.content.cloneNode(true));
        }
        static get observedAttributes() {
            return ["data-title", "data-currency"];
        }
        /* Attributes */
        get title() {
            return this.getAttribute("data-title");
        }
        set title(value) {
            this.setAttribute("data-title", value);
            this.setTitle();
        }
        get currency() {
            var _a;
            return (_a = this.getAttribute("data-currency")) !== null && _a !== void 0 ? _a : "php";
        }
        set currency(value) {
            this.setAttribute("data-currency", value);
            this.setCurrency();
        }
        /* DOM */
        get titleSpan() {
            var _a;
            (_a = this._titleSpan) !== null && _a !== void 0 ? _a : (this._titleSpan = this.shadowRoot.querySelector(".title"));
            return this._titleSpan;
        }
        get figureSpan() {
            var _a;
            (_a = this._figureSpan) !== null && _a !== void 0 ? _a : (this._figureSpan = this.shadowRoot.querySelector(".figure"));
            return this._figureSpan;
        }
        get slot() {
            var _a;
            (_a = this._slot) !== null && _a !== void 0 ? _a : (this._slot = this.shadowRoot.querySelector("slot"));
            return this._slot;
        }
        /* Functions */
        connectedCallback() {
            this.setTitle();
            this.setCurrency();
            this.slot.addEventListener("slotchange", () => {
                const nodes = this.slot.assignedNodes();
                const content = nodes[0].textContent.replace(/,/g, '');
                const balance = parseFloat(content);
                if (isNaN(balance))
                    return;
                if (balance >= 1000000)
                    this.figureSpan.classList.add("small");
                else if (balance >= 100000)
                    this.figureSpan.classList.add("medium");
            });
        }
        attributeChangedCallback(name) {
            switch (name) {
                case "data-title":
                    this.setTitle();
                    break;
                case "data-currency":
                    this.setCurrency();
                    break;
            }
        }
        setTitle() {
            this.titleSpan.innerHTML = this.title;
        }
        setCurrency() {
            this.setAttribute("class", `figure currency-${this.currency}`);
        }
    }
    customElements.define("card-balance", CardBalance);
})();
