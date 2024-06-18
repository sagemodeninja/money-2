document.addEventListener("DOMContentLoaded", () => {
    const navigationView = document.querySelector('#navigation_view');
    navigationView.addEventListener('selectionchanged', e => {
        var args = e.detail.args;
        if (args.isSettingsSelected)
            return;
        window.location.href = args.selectedItem.href;
    });
});
