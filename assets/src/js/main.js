/**
 * Returns a WordPress template part into the DOM.
 *
 * @param {string} templatePart The string of a template part in the theme.
 * @param {DOM Element} target Where the template part should be inserted
 * @return {HTML} Template part into the DOM
 */

	async function fetchTemplatePart(templatePart, target){

		if (!templatePart || !target)
			return

		const ajaxURL = `${siteUrl}/wp-admin/admin-ajax.php?action=ajax_get_template_part&template_part=${templatePart}`

		try {
			//intiate fetch from server side php function
			const response = await fetch(ajaxURL, {
				method: "GET",
				credentials: "same-origin",
				headers: new Headers({"Content-Type": "application/x-www-form-urlencoded"}),
			});
			target.insertAdjacentHTML("afterend", await response.text())

		} catch(error){
			console.error(error);
			return;
		}

	}


/**
 * Toggles Body Lock on / off
 *
 */

function toggleDisableScroll() {
	let body = document.body
		
	if (body.classList.contains("overflow-hidden")) {
		body.classList.remove("overflow-hidden")
	} else {
		body.classList.add("overflow-hidden")
	}
}

/**
 * Toggles Mobile Menu Open or closed
 *
 * @param {Event} event A click event on the hamburger element where the 
 * event listener is.
 */


/**
 * Back to top button
 * 
 * @return {void}
*/

function scrollToTop(event){
	event.preventDefault()
	window.scrollTo({top: 0, behavior: "smooth"})
}


/**
 * Initialises a 'scroll up only' reveal of the sticky header, if the data attribute exists.
 * data-scrollup-only
 * 
 * @return {void}
*/

// Move all sticky sections for header hight when header appears on scroll
// Remove sticky if window is less than sticky element
window.addEventListener("load", () => {

	const header = document.querySelector("header");
	
	let stickySections = document.querySelectorAll(".sticky-prevent");

	if(!stickySections || !header) {
		return;
	}

	const headerHeight = header.offsetHeight;
	let windowHeight = window.innerHeight;

	// Get width of window
	const width = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;

	for (let i = 0; i < stickySections.length; i++) {

		let stickySectionTop = 0;
		let stickySectionTopMin = "0px";
		let stickySectionTopMax = "0px";
		let minRequiredWindowHeight = 0;
		let stickySectionHeight = 0;

		// Get distance of last scroll ( overrides by )
		let lastScrollTop = 0;

		let stickySection = stickySections[i];

		// Add animation
		stickySection.classList.add("transition-all");

		// For offset on scrolled header
		stickySectionTop = parseInt(getComputedStyle(stickySection).top, 10);
		stickySectionTopMin = (stickySectionTop + "px");
		stickySectionTopMax = (stickySectionTop + headerHeight + "px");

		// For removal sticky effect if sticky element is too big for window
		stickySectionHeight = stickySection.offsetHeight;
		minRequiredWindowHeight = stickySectionTop + stickySectionHeight;

		// Initial sticky removal if sticky section is too big
		if(windowHeight < minRequiredWindowHeight) {
			stickySection.classList.remove("sticky");
		}

		// Check on window resize if we need to remove sticky
		window.addEventListener("resize", (event) => {
			
			// Update window height on resize
			windowHeight = window.innerHeight;

			// On window resize sticky removal if sticky section is too big
			if(windowHeight < minRequiredWindowHeight) {
				stickySection.classList.remove("sticky");
			} else {
				stickySection.classList.add("sticky");
			}
			
		});

		window.addEventListener("scroll", () => {

			// Get scroll direction
			const scrollDir = window.scrollY || document.documentElement.scrollTop;

			// If scrolling down
			if (scrollDir > lastScrollTop) {
				if(width > 1024) {
					stickySection.style.top = stickySectionTopMin;	
				}
			} else {
				// If scrolling up
				if(width > 1024) {
					stickySection.style.top = stickySectionTopMax;
				}
			}

			// Set last scroll position
			lastScrollTop = scrollDir <= 0 ? 0 : scrollDir;

		});

	}
	
});


function initScrollUpOnlyStickyHeader() {

	const header = document.querySelector("header");

	// Only initiate the 'scroll up only' sticky header if the data attribute exists on element
	if(!header || !header.hasAttribute("data-scrollup-only") || !header.classList.contains("sticky"))
		return

	// Get the height of the header
	const headerHeight = header.offsetHeight

	const logoInitial = document.querySelector("#header-logo-initial");

	// Get the last scroll position
	let lastScrollTop = 0;

	window.addEventListener("scroll", () => {

		// Get width & height of window
		const windowWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
		const windowHeight = window.innerHeight;
		
		// Detect scroll direction
		const scrollDir = window.scrollY || document.documentElement.scrollTop;

		// If scrolling down beyond the window height, add class to hide header (only on desktop)
		if (scrollDir > lastScrollTop && scrollDir > windowHeight && windowWidth > 1024) {

			header.classList.add("-translate-y-full")

			if(logoInitial) {
				logoInitial.classList.add("opacity-0", "invisible")
			}

		} else {
			// If scrolling up, remove class to show header
			header.classList.remove("-translate-y-full")

			if(windowWidth > 1024 && logoInitial){
				logoInitial.classList.remove("opacity-0", "invisible")
			}

		}

		// If scrolled to top, remove shadow, otherwise add the shadow
		if (scrollDir <= 0) {
			header.classList.remove("shadow-2xl")

			if(windowWidth > 1024 && logoInitial){
				logoInitial.classList.remove("opacity-0", "invisible");
			}
		} else {
			// If we aren't at the top add a shadow to the header as it's going to be sticky
			header.classList.add("shadow-2xl")

			if(windowWidth > 1024 && logoInitial){
				logoInitial.classList.add("opacity-0", "invisible")
			}
		}

		// Set last scroll position
		lastScrollTop = scrollDir <= 0 ? 0 : scrollDir
	});

	window.addEventListener("mousemove", (event) => {

		// if cursor is moved over where the header would be then show the header (it'll then hide again on scroll)
		if (event.clientY < headerHeight) {
			header.classList.remove("-translate-y-full")
		}

	});


}


// Set up event listener for anything we need
window.addEventListener("load", ()  =>{
	// Scroll up only sticky header
	initScrollUpOnlyStickyHeader()
});


// Services scroll block (all JS is here)
window.addEventListener("load", () => {

	const containers = document.querySelectorAll(".services-scroll");
	const headerEl = document.querySelector("header");
	let manualOffset = headerEl ? headerEl.offsetHeight : 0;
	let isScrollActive = true;

	if(!containers) {
		return;
	}

	// Loop through all service-sliders blocks if we have more that 1 on the page.
	for (let i = 0; i < containers.length; i++) {

		const container = containers[i];
		const id = container.id;
		
		// Define variables for Banner column
		const bannerSectionId = "#" + id + "-banner";
		const bannerSection = container.querySelector(bannerSectionId);
	
		// Define variables for Links column
		const linksSectionId = "#" + id + "-links";
		const linksSection = container.querySelector(linksSectionId);
	
		// Define variables for Description column
		const descSectionId = "#" + id + "-desc";
		const descSection = container.querySelector(descSectionId);

		// Check size of window and toggle class depends on screensize
		const cardsSection = container.querySelector(".services-scroll-cards");

		if(cardsSection) {
			if(window.innerWidth < 1024) {
				cardsSection.classList.add("mobile");
			} else {
				cardsSection.classList.add("desktop");
			}
		}

		window.addEventListener("resize", (event) => {
			if(cardsSection) {
				if(window.innerWidth < 1024) {
					cardsSection.classList.add("mobile");
					cardsSection.classList.remove("desktop");
				} else {
					cardsSection.classList.add("desktop");
					cardsSection.classList.remove("mobile");
				}
			}

			// Adjust header offset
			const resizeHeader = document.querySelector("header");
			if(resizeHeader) manualOffset = resizeHeader.offsetHeight;
		});
	
		// Resolve actions on card click
		let cards = cardsSection.querySelectorAll("a");

		// Check if we have setted up active service we need scroll to on page load
		const activeService = container.dataset.activeService;

		for (let n = 0; n < cards.length; n++) {
	
			const card = cards[n];

			card.addEventListener("click", (event) => {

				// Disable scroll related slide changes on this scroll 
				isScrollActive = false;

				event.preventDefault();

				const parent = event.currentTarget.parentElement;
				const currentId = event.currentTarget.dataset.id;

				if(parent.classList.contains("mobile")) {

					// Get all mobile sections
					let mobileSections = container.querySelectorAll(".mobile-service-section");

					// Look if we have section with same id
					for (let n = 0; n < mobileSections.length; n++) {
						const mobileSection = mobileSections[n];
						if(mobileSection.dataset.id == currentId) {
							// If yes - scroll to this section
							window.scroll({
								top: getTopOffset(mobileSection) - manualOffset,
								behavior: "smooth"
							});
						}
					}

				}
			
				if(parent.classList.contains("desktop")) {

					// Get all services in links column
					let linkSectionServices = linksSection.querySelectorAll(".service");

					// Look if we have section with same id
					for (let n = 0; n < linkSectionServices.length; n++) {
						const linkSectionService = linkSectionServices[n];
						if(linkSectionService.dataset.id == currentId) {
							// If yes - scroll to this section
							window.scroll({
								top: getTopOffset(linkSectionService) - manualOffset,
								behavior: "smooth"
							});
						}
					}
					
				}

			});

			// Check if we have active Service (which setted up in PHP and should scroll to this section)
			if(activeService) {
				
				// If so - simulate click on card
				if(activeService == card.dataset.id) {
					card.click();
				}
			}

		}

		// Get all services in links column
		let linkSectionServices = linksSection.querySelectorAll(".service");

		// Look if we have section with same number
		for (let n = 0; n < linkSectionServices.length; n++) {
			
			const linkSectionService = linkSectionServices[n];
			const topOffset = getTopOffset(linkSectionService);
			const elementHeight = linkSectionService.offsetHeight;

			window.addEventListener("scroll", () => {

				let currentId = linkSectionService.dataset.id;

				if(window.scrollY + elementHeight > topOffset && window.scrollY + elementHeight < topOffset + elementHeight) {

					// Get all banner and desc sections 
					let bannerSectionServices = bannerSection.querySelectorAll(".service");
					let descSectionServices = descSection.querySelectorAll(".service");

					let services = [...bannerSectionServices, ...descSectionServices];

					for (let n = 0; n < services.length; n++) {
						const service = services[n];
						if(service.dataset.id == currentId) {
							service.classList.remove("h-0", "invisible", "opacity-0");
							service.classList.add("h-auto", "visible", "opacity-100");
						} else {
							service.classList.add("h-0", "invisible", "opacity-0");
							service.classList.remove("h-auto", "visible", "opacity-100");
						}
					}

				}

			});

		}

	}

});

// Everything for custom cursor
window.addEventListener("load", () => {

	const cursorSections = document.querySelectorAll(".has-cursor");
	// Use as mobile/desktop breakpoint in px
	let breakpoint = 1024;

	for (let i = 0; i < cursorSections.length; i++) {

		const section = cursorSections[i];
		const customCursor = section.querySelector(".custom-cursor");

		// Leave cursor in a middle for video blocks for mobile
		if(customCursor) {
			if(section.classList.contains("video-modal-trigger")) {
				customCursor.classList.add("lg:hidden");
			} else {
				customCursor.classList.add("hidden");
			}
		}

		section.addEventListener("mouseover", (event) => customCursorMouseEnter(event, section, customCursor) );
		section.addEventListener("mouseout", (event) => customCursorMouseLeave(event, section, customCursor));
		section.addEventListener("mousemove", (event) => customCursorMouseMove(event, section, customCursor));		
	}

	const customCursorMouseEnter = (event, section, customCursor) => {

		if (customCursor && window.innerWidth >= breakpoint) {
			// Separate logic for mobile and other elements
			if(section.classList.contains("video-modal-trigger")) {

				if(!section.classList.contains("play")) {
					// Hide Standard cursor
					section.classList.add("lg:cursor-none");
					section.classList.remove("cursor-default");
				}
				customCursor.classList.remove("lg:hidden", "center-absolute");
			} else {
				// Hide Standard cursor
				section.classList.add("lg:cursor-none");
				section.classList.remove("cursor-default");
				customCursor.classList.remove("hidden", "center-absolute");
			}
		}

		// Click states
		if(customCursor) {
			event.target.addEventListener("mousedown", () => {
				customCursor.classList.add("bg-white");
			});
			event.target.addEventListener("mouseup", () => {
				customCursor.classList.remove("bg-white")
			});
		}

	}

	const customCursorMouseLeave = (event, section, customCursor) => {

		// Hide Standard cursor
		section.classList.remove("lg:cursor-none");
		section.classList.add("cursor-default");

		if (customCursor) {

			// Separate logic for mobile and other elements
			if(section.classList.contains("video-modal-trigger")) {
				customCursor.classList.add("lg:hidden", "center-absolute");
			} else {
				customCursor.classList.add("hidden", "center-absolute");
			}
			
		}
		
	}

	const customCursorMouseMove = (event, section, customCursor) => {

		// Only use this function on larger screens
		if (window.innerWidth < breakpoint)
			return;

		if(!customCursor) return;

		const container = section.getBoundingClientRect();
		const cursorWidth = customCursor.offsetWidth;
		const cursorHeight = customCursor.offsetHeight;
		const mouseY = event.clientY - container.top - (cursorHeight/2)
		const mouseX = event.clientX - container.left - (cursorWidth/2)

		const animateStyles = {
			transform: `translate3D(${mouseX}px, ${mouseY}px, 0)`,
		}

		customCursor.animate(animateStyles, {
			duration: 500,
			fill: "forwards"
		})

	}

});

// Manage content updating in person modal
window.addEventListener("load", () => {

	const personModalButtons = document.querySelectorAll(".person-has-modal");
	const modalWrapper = document.getElementById("modal_content");

	if(modalWrapper){
		for(let i = 0; i < personModalButtons.length; i++) {

			const personButton = personModalButtons[i];
			const personName = personButton.dataset.name;
			const personJob = personButton.dataset.job;
			const personImage = personButton.dataset.image;
			const personImageAlt = personButton.dataset.imageAlt;
			const personImageFun = personButton.dataset.imageFun;
			const personImageFunAlt = personButton.dataset.imageFunAlt;
			const personDesc = personButton.dataset.description;
			const personLinkedIn = personButton.dataset.linkedIn;

			const jsonString = personButton.dataset.likes;
			const personLikes = JSON.parse(jsonString);

			personButton.addEventListener("click", (event) => {

				modalWrapper.querySelector("#person_name").innerHTML = personName;
				modalWrapper.querySelector("#job_title").innerHTML = personJob;
				modalWrapper.querySelector("#profile_image").src = personImage;
				modalWrapper.querySelector("#profile_image").alt = personImageAlt;
				modalWrapper.querySelector("#profile_image_fun").src = personImageFun;
				modalWrapper.querySelector("#profile_image_fun").alt = personImageFunAlt;
				modalWrapper.querySelector("#person_description").innerHTML = personDesc;
				modalWrapper.querySelector("#person_likes").innerHTML = personLikes.likes.join(", ");
				modalWrapper.querySelector("#person_dislikes").innerHTML = personLikes.dislikes.join(", ");
				modalWrapper.querySelector("#person_linkedin").href = personLinkedIn;

				if(modalWrapper.querySelector("#profile_image").getAttribute("src") != "") {
					modalWrapper.querySelector("#profile_image").classList.remove("hidden");
				}
				if(modalWrapper.querySelector("#person_linkedin").getAttribute("href") != "") {
					modalWrapper.querySelector("#person_linkedin").classList.remove("hidden");
				}

			});
			
		}

	}
});

// Check and Show cookie preferences button on first load 
window.addEventListener("load", () => { checkAndShowCookieButton(); });

function checkAndShowCookieButton() {
	const container = document.getElementById("cookie-preferences");
	if(!container) {
		return;
	}
	let cookie = getCookie("cc_cookie");
	if(cookie) {
		container.classList.remove("hidden");
	}
}