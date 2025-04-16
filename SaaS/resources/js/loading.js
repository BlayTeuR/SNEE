document.addEventListener("DOMContentLoaded", function () {
    const bar = document.getElementById("progress-bar");

    const startLoading = () => {
        bar.classList.remove("w-0");
        bar.classList.add("w-full");
    };

    const finishLoading = () => {
        setTimeout(() => {
            bar.classList.remove("w-full");
            bar.classList.add("w-0");
        }, 300);
    };

    window.addEventListener("beforeunload", startLoading);
    window.addEventListener("load", finishLoading);

    // Pour les clics internes
    document.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', e => {
            const href = link.getAttribute('href');
            if (href && !href.startsWith('#') && !href.startsWith('javascript:')) {
                startLoading();
            }
        });
    });
});
