(function () {
    const template = document.createElement("template");
    template.innerHTML = `
    <style>
    :host {
        --radius: 5px;
        --radius-mob: 15px;
        --margin: 15px;
        --top: calc(44px / 2);
        --width: 500px;
    }
    
    :host {
        background-color: rgba(0, 0, 0, 0.1);
        display: none;
        height: 100%;
        left: 0;
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 4;
    }

    :host(.visible) {
        display: block;
    }
    
    .panel {
        background-color: #fff;
        border-top-left-radius: var(--radius-mob);
        border-top-right-radius: var(--radius-mob);
        box-shadow: 0 0 2px rgba(0, 0, 0, 0.2), 0 calc(32 * 0.5px) calc((32 * 1px)) rgba(0, 0, 0, 0.24);
        overflow: hidden;
        position: absolute;
        right: 0;
        top: 100%;
        height: calc(100% - var(--top));
        width: 100%;
    }

    .handle-bar {
        align-items: center;
        display: flex;
        height: 20px;
        justify-content: center;
        width: 100%;
    }

    .handle-bar::before {
        background-color: rgba(194, 194, 194, 1);
        border-radius: 4px;
        content: "";
        display: block;
        height: 6px;
        width: 100px;
    }

    slot {
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        gap: 15px;
        height: calc(100% - 20px);
        max-height: 100%;
        padding-top: 10px;
    }
    
    /* Tablet & Up */
    @media only screen and (min-width: 768px) {
        .panel {
            border-radius: var(--radius);
            height: calc(100% - var(--margin) * 2);
            right: calc(var(--width) * -1);
            top: var(--margin);
            width: 500px;
        }
        
        .handle-bar::before {
            display: none;
        }
    }
    </style>

    <div class="panel">
        <div class="handle-bar"></div>
        <slot></slot>
    </div>
    `;
    class TransactionPanel extends HTMLElement {
        constructor() {
            super();
            this.attachShadow({ mode: "open" });
            this.shadowRoot.appendChild(template.content.cloneNode(true));
            this.clickedThroughPanel;
            this.overlay = { alpha: 0.0 };
        }
        /* DOM */
        get panel() {
            var _a;
            (_a = this._panel) !== null && _a !== void 0 ? _a : (this._panel = this.shadowRoot.querySelector(".panel"));
            return this._panel;
        }
        connectedCallback() {
            this.addEventListener("click", this.hide);
            this.panel.addEventListener("click", () => this.clickedThroughPanel = true);
        }
        show() {
            this.classList.add("visible");
            this.animate(true, 0.1);
        }
        hide() {
            if (this.clickedThroughPanel) {
                this.clickedThroughPanel = false;
                return;
            }
            this.animate(false, 0);
        }
        animate(show, alpha) {
            var timeline = anime.timeline({
                duration: 300,
                easing: "easeOutQuint",
                update: () => this.changeTheme(),
                complete: () => {
                    if (!show) {
                        this.classList.remove("visible");
                    }
                }
            });
            // Panel
            const panelAnim = { targets: this.panel };
            if (window.innerWidth < 768)
                panelAnim.top = show ? 22 : window.innerHeight;
            else
                panelAnim.right = show ? 15 : -531; // TODO: Dynamic?
            timeline.add(panelAnim, 0);
            // Overlay
            const background = `rgba(0, 0, 0, ${alpha})`;
            timeline.add({ targets: this, background: background }, 0);
            // Theme
            // FIXME: Flicker on show first attempt.
            timeline.add({ targets: this.overlay, alpha: alpha }, 0);
        }
        changeTheme() {
            const theme = computeAlphaBlend("f2f2f2", "000000", this.overlay.alpha);
            document.querySelector('meta[name="theme-color"]')
                .setAttribute("content", theme);
        }
    }
    customElements.define("transaction-panel", TransactionPanel);
})();
