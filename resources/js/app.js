import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

document.addEventListener("DOMContentLoaded", () => {
    const mainElement = document.querySelector("main");

    function showLoader() {
        mainElement.innerHTML = `
            <div class="flex justify-center items-center h-40">
                <div class="animate-spin rounded-full h-10 w-10 border-t-2 border-b-2 border-blue-500"></div>
            </div>
        `;
    }

    function loadPage(url, push = true) {
        showLoader();

        fetch(url, {
            headers: {
                "X-Requested-With": "XMLHttpRequest",
            },
        })
            .then((response) => response.text())
            .then((html) => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, "text/html");

                const newContent = doc.querySelector("main")?.innerHTML;
                if (newContent) {
                    mainElement.innerHTML = newContent;
                    if (push) {
                        history.pushState(null, "", url);
                    }
                }
            })
            .catch((err) => {
                console.error("Page load failed", err);
                mainElement.innerHTML = `<div class="text-center text-red-500 p-4">Page failed to load.</div>`;
            });
    }

    document.body.addEventListener("click", (e) => {
        const link = e.target.closest("a[data-link]");
        if (link) {
            e.preventDefault();
            const url = link.getAttribute("href");
            loadPage(url);
        }
    });

    window.addEventListener("popstate", () => {
        loadPage(location.pathname, false);
    });
});
