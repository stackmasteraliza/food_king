"use strict";

const drawerSets = document?.querySelectorAll("[data-drawer]");

document?.addEventListener("click", function (event) {
    const drawerDivs = document?.querySelectorAll(".drawer");
    const backdrop = document.querySelectorAll('.backdrop');
    const isClickInsideDrawer = event.target.closest('.drawer') || event.target.closest('[data-drawer]');
    if (!isClickInsideDrawer) {
        drawerSets?.forEach(drawerBtn => drawerBtn?.classList?.remove("active"));
        drawerDivs?.forEach(drawerDiv => drawerDiv?.classList?.remove("active"));
        backdrop?.forEach(drop => drop?.classList.remove("active"));
    } else {
        document.body.classList.remove('overflow-hidden');
        document.body.style.overflowY = 'auto';
    }
});