
/**
*
* Initialises any modals on the page on document load.
* Calls the function that will set up the modal dismissers.
*
**/

window.addEventListener("load", () => {
	const modals = document.querySelectorAll(".modal") ?? []

	modals.forEach((modal) => {
		addModalDismiss(modal)
	})
})

// Listen for HubSpot meetings iframe postMessage events and push to GTM dataLayer
window.addEventListener("message", (event) => {
	if (event.data && event.data.meetingBookingSuccessful) {
		window.dataLayer = window.dataLayer || []
		window.dataLayer.push({
			event: "book_a_call",
			eventCategory: "Booking",
			eventAction: "Meeting Booked",
			eventLabel: "HubSpot Meeting"
		})
	}
})

/**
*
* Triggers a modal open. Put this on a HTML element
*
* @param {string} modalId The string ID of modal, including the selector #
*
**/

function triggerModal(modalId, ajaxLoadContent = false, postId = false, template = false) {
	
	let modal = document.querySelector(modalId)

	if (!modal)
		return

	let effect = modal.dataset.effect;

	if(!effect) {
		effect ="fade";
	}

	if(effect == "fade") {
		modal.classList.remove("invisible", "opacity-0")
		modal.classList.add("visible", "opacity-100")
	}

	if(effect == "slide-right") {
		modal.classList.add("translate-x-0")
		modal.classList.remove("translate-x-full")
	}

	if(effect == "slide-left") {
		modal.classList.add("translate-x-0")
		modal.classList.remove("-translate-x-full")
	}


	toggleDisableScroll();

	/* If using AJAX to load modal content */
	if (ajaxLoadContent && postId && template)
		modalAjaxLoadContent(postId, modalId, template);
}

/**
*
* Sets up the elements that will dismiss a given modal.
*
* NOTE: the way this works, the .modal-dismiss must be a 
* child element of the modal parent element.
*
* NOTE:  A timeout can be added by adding a data attr
* with a numeric milliseconds value to the dismissing element in the HTML.
*
* @param {HTMLElement} modal A modal HTML element.
*
**/

function addModalDismiss(modal){

	if (!modal)
		return

	const modalDismissers = modal.querySelectorAll(".modal-dismiss")

	if (!modalDismissers)
		return
	
	modalDismissers.forEach((modalDismisser) => {
		/* Toggle modal invisible */
		modalDismisser.addEventListener("click", () => {

			if (modalDismisser.dataset.dismissTimeout){
				setTimeout( () => {
					closeModal(modal)
				}, parseInt(modalDismisser.dataset.dismissTimeout))
			} else {
				closeModal(modal)
			}
		})
	})
}

/**
*
* Dismisses a currently open modal.
*
* @param {HTMLElement} modal A modal HTML element.
*
**/
function closeModal(modal) {

	let effect = modal.dataset.effect;

	if( !effect ) {
		effect ="fade";
	}

	if(effect == "fade") {
		modal.classList.add("invisible", "opacity-0")
		modal.classList.remove("visible", "opacity-100")
	}

	if(effect == "slide-right") {
		modal.classList.remove("translate-x-0")
		modal.classList.add("translate-x-full")
	}

	if(effect == "slide-left") {
		modal.classList.remove("translate-x-0")
		modal.classList.add("-translate-x-full")
	}

	// Only re-enable scroll if no other modals are still open.
	const anyOpen = document.querySelectorAll(".modal.visible, .modal.opacity-100, .modal.translate-x-0").length > 0
	if (!anyOpen) {
		document.body.classList.remove("overflow-hidden")
	}
}

async function modalAjaxLoadContent(postId, modalId, template) { 
	const modal = document.querySelector(modalId);
	const contentLoadContainer = modal.querySelector(".modal-load-content");

	// Check we have a container to load the content into
	if (!contentLoadContainer)
		return;

	// Reset content
	contentLoadContainer.innerHTML = "";

	// Reset loading state
	const loader = document.querySelector(".modal-content-loader-icon");
	if (loader)
		loader.classList.remove("hidden");

	const url = `${siteUrl}/wp-admin/admin-ajax.php?action=ajax_load_modal_content&post_id=${postId}&template=${template}`;

	// Load in content
	try {
		const response = await fetch(url, {
			method: "GET",
			credentials: "same-origin",
			headers: new Headers({ "Content-Type": "application/json" })
		});
		const content = await response.text();

		// Scan for existing script tags
		existingScripts = document.scripts;

		// Reload content
		contentLoadContainer.innerHTML = content ?? "<p class=\"text-center\">Unable to retrieve content</p>";
		if (loader)
			loader.classList.add("hidden")		
		
		// Load script tags from loaded content (if not exist)
		initLoadedscripts(modalId, existingScripts);

	} catch(error) {
		console.error(error)
	}
}

// Function to load all inline <script> tags which might be in content
function initLoadedscripts(modalId, existingScripts) {

	const modal = document.querySelector(modalId);
	const contentLoadContainer = modal.querySelector(".modal-load-content");

	let scripts = contentLoadContainer.getElementsByTagName("script");

	for (let i = 0; i < scripts.length; i++) {

		let script = scripts[i];
		let isAsync = false

		// Look if we have already this script
		for (let s = 0; s < existingScripts.length; s++) {
			// Prevent duplicating of scripts
			const existingScript = existingScripts[s];
			if(existingScript.src == script.src) {
				// Check if old one was async
				isAsync = existingScript.async;
				existingScript.remove();
				break;
			}
			
		}

		let newScript = document.createElement("script");
        newScript.async = isAsync;
		newScript.src = script.src;
        document.body.append(newScript);

	}

}