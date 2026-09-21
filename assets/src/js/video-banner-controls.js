const allowReplay = (event, id) => {
	const pauseButton = document.getElementById(`video-banner-pause-${id}`);
	const replayButton = document.getElementById(`video-banner-replay-${id}`);
	pauseButton.classList.add("hidden");
	replayButton.classList.remove("hidden");
}

const videoBannerPause = (event, id) => {
	const video = document.getElementById(`video-banner-${id}`);
	const pauseButton = event.currentTarget;
	const playButton = document.getElementById(`video-banner-play-${id}`);
	video.pause();
	pauseButton.classList.add("hidden");
	playButton.classList.remove("hidden");
}

const videoBannerPlay = (event, id) => {
	const video = document.getElementById(`video-banner-${id}`);
	const playButton = event.currentTarget;
	const pauseButton = document.getElementById(`video-banner-pause-${id}`);
	video.play();
	playButton.classList.add("hidden");
	pauseButton.classList.remove("hidden");
}

const videoBannerReplay = (event, id) => {
	const video = document.getElementById(`video-banner-${id}`);
	const replayButton = event.currentTarget;
	const pauseButton = document.getElementById(`video-banner-pause-${id}`);
	video.currentTime = 0;
	video.play();
	replayButton.classList.add("hidden");
	pauseButton.classList.remove("hidden");
}