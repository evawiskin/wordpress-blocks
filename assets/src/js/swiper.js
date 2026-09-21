document.addEventListener("DOMContentLoaded", () => {

	let swipers = document.querySelectorAll(".swiper, .sm\\:swiper, .md\\:swiper, .lg\\:swiper, .xl\\:swiper, .\\2xl\\:swiper");
    let swiperInstances = [];
	
    for (let i = 0; i < swipers.length; i++) {

        let blockId = swipers[i].id;
        let wrapper = swipers[i].querySelector(".swiper-wrapper");
        let currentSwiperData = swipers[i].dataset;
        let settings = {};

        // Find minimal breakpoint
        let classes = swipers[i].classList;
        let breakpoint = 0;
        let classWord = "";
        for (let n = 0; n < classes.length; n++) {
            classWord = classes[n];
            if(classWord.includes("sm")) { breakpoint = 640; break; }
            if(classWord.includes("md")) { breakpoint = 768; break; }
            if(classWord.includes("lg")) { breakpoint = 1024; break; }
            if(classWord.includes("xl")) { breakpoint = 1280; break; }
            if(classWord.includes("2xl")) { breakpoint = 1536; break; }
        }

        // Define counter (will be used after Swiper init)
        let counter = currentSwiperData.counter;

        // Define Pagination
        if (currentSwiperData.pagination && currentSwiperData.pagination === "true") {
            settings.pagination = {
                el: `#swiper-pagination-${blockId}`,
                clickable: true,
                bulletActiveClass: "active",
                bulletClass: "swiper-bullet"
            };
        }

        // Define Navigation
        if (currentSwiperData.navigation && currentSwiperData.navigation === "true") {
            settings.navigation = {
                nextEl: `#swiper-next-${blockId}`,
                prevEl: `#swiper-prev-${blockId}`,
            };
        }

        // Define Scrollbar
        if (currentSwiperData.navigation && currentSwiperData.scrollbar === "true") {
            settings.scrollbar = {
                el: `#swiper-scrollbar-${blockId}`,
                draggable: true,
                dragClass: "scrollbar-drag",
                lockClass: "hidden",
                hide: false,
                dragSize: 90
            };
        }
        
        // Define Breakpoints
        let mobileCol = currentSwiperData.mobilecol;
        let mobileGap = currentSwiperData.mobilegap;
        let tabletCol = currentSwiperData.tabletcol;
        let tabletGap = currentSwiperData.tabletgap;
        let desktopCol = currentSwiperData.desktopcol;
        let desktopGap = currentSwiperData.desktopgap;
        let xlCol = currentSwiperData.xlcol;
        let xlGap = currentSwiperData.xlgap;

        settings.breakpoints = {
            0: {
                slidesPerView: Number((mobileCol) ? mobileCol : 1),
                spaceBetween: Number((mobileGap) ? mobileGap : 50)
            },
            768: {
                slidesPerView: Number((tabletCol) ? tabletCol : 2),
                spaceBetween: Number((tabletGap) ? tabletGap : 40)
            },
            1024: {
                slidesPerView: Number((desktopCol) ? desktopCol : 3),
                spaceBetween: Number((desktopGap) ? desktopGap : 30)
            },
            ...(xlCol ? {
                1280: {
                    slidesPerView: Number(xlCol),
                    spaceBetween: Number((xlGap) ? xlGap : (desktopGap ?? 30))
                }
            } : {})
        };

        // Define Loop
        if (currentSwiperData.loop && currentSwiperData.loop === "true") {
            let slidesCount = Number(currentSwiperData.slidesCount);
            let maxCols = (desktopCol) ? desktopCol : 3;
            // Loop only works if there are double the amount of slides as columns so lets check and switch to rewind if not
            if(slidesCount >= (maxCols*2)) {
                settings.loop = true;
                settings.rewind = false;
            } else {
                settings.loop = false;
                settings.rewind = true;
            }
        }

        // Define Autoplay
        if (currentSwiperData.autoplay && currentSwiperData.autoplay === "true") {
            settings.autoplay = {
                delay: currentSwiperData.delay ?? 5000,
                disableOnInteraction: currentSwiperData.disableOnInteraction ?? false
            };
            settings.speed = currentSwiperData.speed ?? 1000;
            settings.grabCursor = false;
        }

        // Define Centered Slides
        if (currentSwiperData.centeredSlides && currentSwiperData.centeredSlides === "true") {
            settings.centeredSlides = true;
        }

        // Define effect
        // https://swiperjs.com/swiper-api#fade-effect
        if (currentSwiperData.effect) {

            // Resolve "Fade"
            if(currentSwiperData.effect === "fade") {
                settings.effect = "fade";
                settings.fadeEffect = {
                crossFade: true
                }
            }

            // ... other effects

        }

        //Reimplement showing swiper after init
        let events = {
            on: {
                afterInit: function () {
                    const swiperElement = document.getElementById(blockId);
                    if(!swiperElement) return;
                    
                    swiperElement.classList.remove("invisible");
                    swiperElement.classList.remove("opacity-0");
                    showSwiperNavButtons();
                    swiperElement.parentElement?.classList.remove("invisible", "opacity-0");
                }
            }  
        }
        // Merge settings
        settings = Object.assign(settings, events);
        
        
        // Init Swiper
        if(window.innerWidth > breakpoint) {
            
            swiperInstances[i] = new Swiper(`#${blockId}`, settings);
            // Init counter
            if(counter && counter == "true") {
                swiperCounter( swiperInstances[i] );
            }
        } else {
            swiperInstances[i] = false;
            wrapper.className = wrapper.dataset.swiperDisabledClass;
        }

        // Re-init on 
        window.addEventListener("resize", () => {

            if(window.innerWidth < breakpoint) {

                if(swiperInstances[i]) {
                    swiperInstances[i].destroy( true, true );
                    swiperInstances[i] = false;
                }

                wrapper.className = wrapper.dataset.swiperDisabledClass;

            }
            
            if(window.innerWidth > breakpoint) {

                wrapper.className = "swiper-wrapper";
                if(!swiperInstances[i]) {
                    const swiperEl = document.getElementById(blockId);
                    swiperEl?.parentElement?.classList.add("invisible", "opacity-0");
                    swiperInstances[i] = new Swiper(`#${blockId}`, settings);
                    // Init counter
                    if(counter && counter == "true") {
                        swiperCounter( swiperInstances[i] );
                    }
                }

            }

        });

    }

})

function swiperCounter( swiper ) {
    // Set up event listener for slide change
    swiper.on("slideChange", () => {
        let element = swiper.el;
        let counter = element.parentElement.querySelector(".counter");
        if(counter) {
            counter.innerHTML = swiper.activeIndex + 1;
        }
    });
}


// Conditionally hide nav buittons if both are disabled (if we have less slides than needed for sliding)
// Checks on window resize as well 
function showSwiperNavButtons() {
    // Get all swipers on the page
    let swipers = document.querySelectorAll(".swiper, .sm\\:swiper, .md\\:swiper, .lg\\:swiper, .xl\\:swiper, .\\2xl\\:swiper");
	
    for (let i = 0; i < swipers.length; i++) {
        
        const swiper = swipers[i];
        const swiperObject = swiper.swiper;

        // Get navigation buttons
        if(swiperObject) {

            const nextButtonId = swiperObject.params.navigation.nextEl;
            const prevButtonId = swiperObject.params.navigation.prevEl;
            
            // Check if we have 2 buttons (to make sure this is nav buttons)
            if(nextButtonId && prevButtonId) {

                const nextButton = document.querySelector(nextButtonId);
                const prevButton = document.querySelector(prevButtonId);

                if(nextButton && prevButton) {

                    // Keep nav visible when looping
                    if(swiperObject.params.loop) {
                        nextButton.classList.remove("hidden");
                        prevButton.classList.remove("hidden");
                        continue;
                    }

                    const nextIsDisabled = nextButton.classList.contains("swiper-button-disabled");
                    const prevIsDisabled = prevButton.classList.contains("swiper-button-disabled");

                    // Only if both are disabled - hide them
                    if(nextIsDisabled && prevIsDisabled) {
                        nextButton.classList.add("hidden");
                        prevButton.classList.add("hidden");
                    } else {
                        nextButton.classList.remove("hidden");
                        prevButton.classList.remove("hidden");
                    }

                }
            }

        }
    }
}

window.addEventListener("resize", () => { showSwiperNavButtons(); });