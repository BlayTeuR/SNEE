document.addEventListener("DOMContentLoaded", function () {
    const bar = document.getElementById("progress-bar");
    const overlay = document.getElementById("loading-overlay");

    const startLoading = () => {
        bar?.classList.remove("w-0");
        bar?.classList.add("w-full");

        overlay?.classList.remove("hidden");
    };

    const finishLoading = () => {
        setTimeout(() => {
            bar?.classList.remove("w-full");
            bar?.classList.add("w-0");

            overlay?.classList.add("hidden");
        }, 300);
    };

    window.startGlobalLoading = startLoading;
    window.stopGlobalLoading = finishLoading;

    // Pour les liens internes
    document.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', e => {
            const href = link.getAttribute('href');
            if (href && !href.startsWith('#') && !href.startsWith('javascript:')) {
                startLoading();
            }
        });
    });

    window.addEventListener("beforeunload", startLoading);
    window.addEventListener("load", finishLoading);
});
