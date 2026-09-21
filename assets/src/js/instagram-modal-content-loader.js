const container = document.getElementById("instagram-modal-data");
const loader = document.getElementById("instagram-modal-content-loader");

const loadInstaContent = async (event) => {
	
	// Get data attributes
	const userName = event.currentTarget.dataset.instagramUsername;
	const caption = event.currentTarget.dataset.postCaption;
	const timeStamp = event.currentTarget.dataset.timeStamp;
	const mediaType = event.currentTarget.dataset.mediaType;
	const mediaUrl = event.currentTarget.dataset.mediaUrl;

	if (!container)
		return;

	const url = `
		${siteUrl}/wp-admin/admin-ajax.php?action=ajax_load_instagram_modal_content
		&user=${userName}
		&time_stamp=${timeStamp}
		&media_type=${mediaType}
		&caption=${caption}
	`;

	try {

		const response = await fetch(url, {
			method: "GET",
			credentials: "same-origin",
			headers: new Headers({ "Content-Type": "application/json" })
		});
		
		const content = await response.text();

		if (content) {

			// Hide loader
			loader.classList.add("hidden");

			// Load content into DOM
			container.innerHTML = content
			
			const image = document.getElementById("modal-instagram-media");

			// Then populate the image tag returned. We do this to avoid sending it on out GET request and having to sanitize awkward urls
			if (image) {
				image.src = mediaUrl;
			}

		}
			
		else
			container.innerHTML = "Unable to retrieve data";

	} 
	catch(error) {

		container.innerHTML = "Something went wrong. Please try again later.";
		console.error(error)
	}
	
}

const clearModalInstaContent = () => {
	container.innerHTML = "";

	// Show loader
	loader.classList.remove("hidden");
}