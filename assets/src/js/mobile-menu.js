/**
 * Toggles Mobile Menu Open or closed
 *
 * @param {Event} event A click event on the hamburger element where the 
 * event listener is.
 */


function toggleMobileMenu(event){
	if (!event)
		return

	const menuToggle = document.querySelector("#mobile-menu-toggle")
	const mobileMenu = document.querySelector("#mobile-menu")
	const opacityOverlay = document.querySelector("#mobile-menu-overlay")
	
	if (!menuToggle || !mobileMenu || !opacityOverlay) return;

	// Get all interactive elements in the mobile menu
	const menuElements = mobileMenu.querySelectorAll("a, button, [tabindex]");
	const submenus = mobileMenu.querySelectorAll(".submenu");
	const accordionPanels = mobileMenu.querySelectorAll(".accordion-body");

	menuToggle.classList.toggle("hamburger-active");

	if (menuToggle.classList.contains("hamburger-active")) {
		//update aria role
		menuToggle.setAttribute("aria-expanded", "true")
		
		//update classes
		mobileMenu.classList.add("block", "mobile-menu-active")
		mobileMenu.classList.remove("translate-x-full")

		opacityOverlay.classList.remove("opacity-0", "invisible", "pointer-events-none")
		opacityOverlay.classList.add("opacity-50")

		// Enable tabindex for visible elements
		menuElements.forEach(element => {
			const parentSubmenu = element.closest(".submenu");
			const parentAccordion = element.closest(".accordion-body");
			
			if ((!parentSubmenu || !parentSubmenu.classList.contains("translate-x-full")) && 
				(!parentAccordion || !parentAccordion.classList.contains("invisible"))) {
				element.setAttribute("tabindex", "0");
			} else {
				element.setAttribute("tabindex", "-1");
			}
		});

		//lock body scroll
		toggleDisableScroll()

	} else {
		//update aria role
		menuToggle.setAttribute("aria-expanded", "false")

		//update classes
		mobileMenu.classList.remove("mobile-menu-active", "flex")
		mobileMenu.classList.add("translate-x-full")

		opacityOverlay.classList.add("opacity-0", "invisible", "pointer-events-none")
		opacityOverlay.classList.remove("opacity-50")

		// Disable tabindex for all elements when menu is closed
		menuElements.forEach(element => {
			element.setAttribute("tabindex", "-1");
		});

		// Reset all submenus to hidden state
		submenus.forEach(submenu => {
			submenu.classList.add("translate-x-full");
			submenu.setAttribute("aria-hidden", "true");
			const items = submenu.querySelectorAll("a, button, [tabindex]");
			items.forEach(item => {
				item.setAttribute("tabindex", "-1");
				if (item.hasAttribute("aria-expanded")) {
					item.setAttribute("aria-expanded", "false");
				}
			});
		});

		// Reset all accordion panels
		accordionPanels.forEach(panel => {
			panel.classList.add("invisible");
			panel.style.maxHeight = "0px";
			panel.setAttribute("aria-hidden", "true");
			const items = panel.querySelectorAll("a, button, [tabindex]");
			items.forEach(item => {
				item.setAttribute("tabindex", "-1");
				if (item.hasAttribute("aria-expanded")) {
					item.setAttribute("aria-expanded", "false");
				}
			});
		});

		//unlock body scroll
		toggleDisableScroll()
	}
}

// Debounce function
function debounce(func, wait) {
    let timeout;
    return function (...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), wait);
    };
}

// Mobile menu screen walker
window.addEventListener("load", (event) => {
	event.preventDefault();

	const container = document.getElementById("mobile-menu");

	if(!container)
		return;

	// Get all interactive elements in the mobile menu
	const menuElements = container.querySelectorAll("a, button, [tabindex]");

	// Initially set all elements to not tabbable
	menuElements.forEach(element => {
		element.setAttribute("tabindex", "-1");
	});

	// Handle submenu toggles
	const submenuToggles = container.querySelectorAll("button[aria-controls]");
	submenuToggles.forEach(toggle => {
		toggle.addEventListener("click", (event) => {
			event.preventDefault();
			const submenu = toggle.nextElementSibling;
            if (!submenu || !submenu.classList.contains("submenu")) return;
			if (submenu) {
				submenu.classList.remove("translate-x-full");
				submenu.setAttribute("aria-hidden", "false");
				
				// Update ARIA states
				toggle.setAttribute("aria-expanded", "true");
				
				// Enable tabindex for items in the new level
				const submenuItems = submenu.querySelectorAll("a, button, [tabindex]");
				submenuItems.forEach(item => {
					item.setAttribute("tabindex", "0");
				});

				// Focus the first item in the submenu
				const firstSubmenuItem = submenu.querySelector("a, button");
				if (firstSubmenuItem) {
					firstSubmenuItem.focus();
				}

				// Resolve back button
				let back = submenu.querySelector(".back");
				if (back) {
					back.addEventListener("click", (event) => {
						event.preventDefault();
						submenu.classList.add("translate-x-full");
						submenu.setAttribute("aria-hidden", "true");
						
						// Update ARIA states
						toggle.setAttribute("aria-expanded", "false");
						
						// Disable tabindex for items in the hidden level
						const submenuItems = submenu.querySelectorAll("a, button, [tabindex]");
						submenuItems.forEach(item => {
							item.setAttribute("tabindex", "-1");
						});

						// Return focus to the toggle button
						toggle.focus();
					});
				}
			}
		});
	});

	// Handle accordion panels
	const accordionToggles = container.querySelectorAll(".accordion-toggle");
	accordionToggles.forEach(toggle => {
		toggle.addEventListener("click", (event) => {
			event.preventDefault();
			const accordionPanel = toggle.parentNode.querySelector(".accordion-body");
			
			if (accordionPanel) {
				const isExpanded = toggle.getAttribute("aria-expanded") === "true";
				
				if (isExpanded) {
					accordionPanel.classList.add("invisible");
					accordionPanel.style.maxHeight = "0px";
					accordionPanel.setAttribute("aria-hidden", "true");
					toggle.setAttribute("aria-expanded", "false");
					
					// Disable tabindex for items in the hidden panel
					const panelItems = accordionPanel.querySelectorAll("a, button, [tabindex]");
					panelItems.forEach(item => {
						item.setAttribute("tabindex", "-1");
					});
				} else {
					accordionPanel.classList.remove("invisible");
					accordionPanel.style.maxHeight = accordionPanel.scrollHeight + "px";
					accordionPanel.setAttribute("aria-hidden", "false");
					toggle.setAttribute("aria-expanded", "true");
					
					// Enable tabindex for items in the visible panel
					const panelItems = accordionPanel.querySelectorAll("a, button, [tabindex]");
					panelItems.forEach(item => {
						item.setAttribute("tabindex", "0");
					});

					// Focus the first item in the panel
					const firstPanelItem = accordionPanel.querySelector("a, button");
					if (firstPanelItem) {
						firstPanelItem.focus();
					}
				}
			}
		});
	});
}); 

// Toggle mega-menu in mobile menu
const toggleMegaMenu = (event, megaMenuId) => {

	// Check if the mega menu exists
	const megaMenu = document.getElementById(megaMenuId);
	if (!megaMenu) return;

	const trigger = event.currentTarget;
	const chevron = trigger.querySelector(".chevron");
	megaMenu.classList.toggle("max-h-0");
	megaMenu.classList.toggle("mb-3");
	trigger.classList.toggle("text-green-500")
	if (chevron) {
		chevron.classList.toggle("rotate-180");
	}
}