// returns a video element with the source, classes, and attributes provided
function createVideo(source, classes, attributes) {
	const video = document.createElement("video");
	const defaultAttributes = {
		autoplay: true,
		loop: false,
		muted: false,
		playsinline: true,
		controls: true,
		width: "100%",
		height: "100%"
	};
	attributes = {...defaultAttributes, ...attributes};
	for (const attr in attributes)
		video[attr] = attributes[attr];
	const classesArray = classes.split(" ");
	video.classList.add(...classesArray);
	const videoSourceElement = document.createElement("source");
	videoSourceElement.src = source;
	const videoType = source.split("?")[0].split(".").pop();
	videoSourceElement.type = `video/${videoType}`;
	video.appendChild(videoSourceElement);
	return(video);
}

// runs on load for each video container
function videoBlock(videoContainer) {
	const videoSection = videoContainer.parentElement;
	const mainVideoUrl = videoContainer.getAttribute("data-main-video-url");
	const coverImage = videoContainer.querySelector("img");
	
	// Handle click to play video
	if(coverImage && mainVideoUrl){
		videoContainer.addEventListener("click", function() {
			// Create video element with opacity 0
			const videoElement = createVideo(
				mainVideoUrl,
				"w-full h-full object-center md:object-cover opacity-0 transition-opacity duration-500", 
				{
					autoplay: true,
					playsinline: true
				}
			);
			
			// Add video to container
			videoContainer.appendChild(videoElement);
			
			// Load and play video
			videoElement.load();
			videoElement.play().catch(e => console.log('Video autoplay prevented:', e));
			
			// Fade out cover image
			coverImage.classList.add("transition-opacity", "duration-500", "opacity-0");
			
			// After fade completes, fade in video and remove image
			setTimeout(() => {
				videoElement.classList.remove("opacity-0");
				videoElement.classList.add("opacity-100");
				
				// Remove cover image after video fades in
				setTimeout(() => {
					if(coverImage.parentNode === videoContainer) {
						videoContainer.removeChild(coverImage);
					}
				}, 500);
			}, 500);
			
			// Hide custom cursors (desktop and mobile)
			const customCursor = videoSection.querySelector(".custom-cursor");
			const customCursorMobile = videoSection.querySelector(".custom-cursor-mobile");
			if(customCursor){
				customCursor.classList.add("!hidden");
				videoSection.classList.add("!cursor-pointer");
			}
			if(customCursorMobile){
				customCursorMobile.classList.add("!hidden");
			}
			
			// Remove opacity on video container
			videoContainer.classList.remove("opacity-50");
		}, {once: true}); // Only run once
	}
}

document.addEventListener("DOMContentLoaded", () => {
	const videoContainers = document.querySelectorAll(".video-container");
	if(videoContainers){
		videoContainers.forEach(function(videoContainer) {
			videoBlock(videoContainer);
		});
	}
});
