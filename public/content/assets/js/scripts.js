/** @format */
window.addEventListener("DOMContentLoaded", (event) => {
	// Toggle the side navigation
	const sidebarToggle = document.body.querySelector("#sidebarToggle");
	if (sidebarToggle) {
		if (localStorage.getItem("sidebar-toggle") === "true") {
			document.body.classList.toggle("side-nav-toggled");
		}
		sidebarToggle.addEventListener("click", (event) => {
			event.preventDefault();
			document.body.classList.toggle("side-nav-toggled");
			localStorage.setItem(
				"sidebar-toggle",
				document.body.classList.contains("side-nav-toggled")
			);
		});
	}
});
