function toggleMenu() {
    const menu = document.getElementById("menu-dropdown");
    menu.classList.toggle("show");
}

// Close menu when clicking outside
document.addEventListener("click", function(event) {
    const menu = document.getElementById("menu-dropdown");
    const menuIcon = document.querySelector(".menu-icon");

    if (!menu.contains(event.target) && !menuIcon.contains(event.target)) {
        menu.classList.remove("show");
    }
});
