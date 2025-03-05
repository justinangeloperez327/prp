document.addEventListener("DOMContentLoaded", function () {
    const logo = document.querySelector(".fi-logo");
    const darkLogo = "/images/PRP-logo-Negative-120x40px.svg";
    const lightLogo = "/images/PRP-logo-Positive-120x40px.svg";

    function updateLogo() {
        const htmlElement = document.documentElement;
        if (htmlElement.classList.contains("dark")) {
            console.log("dark");
            logo.src = darkLogo;
        } else {
            console.log("light");
            logo.src = lightLogo;
        }
    }

    // Initial logo update
    updateLogo();

    // Listen for theme mode changes
    const observer = new MutationObserver(function (mutations) {
        const htmlElement = document.documentElement;
        if (htmlElement.classList.contains("dark")) {
            logo.src = darkLogo;
        } else {
            logo.src = lightLogo;
        }
    });

    observer.observe(document.documentElement, { attributes: true });
});
