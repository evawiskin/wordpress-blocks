/**
 * 
 * Common utilities functions which might be used everywhere collected in this file
 * 
 */



/**
 * Get distance from element to the top of the page
 * Pass any DOM element as single parameter to get distance from the top of page (top edge of browser's window)
 * Was designed to loop through parents to get right distance from real top, but not from parent
 */
function getTopOffset(element) {

	// Define distance
	let distance = 0;

	// Loop through parents in DOM
	if (element.offsetParent) {
		do {
			distance += element.offsetTop;
			element = element.offsetParent;
		} while (element);
	}

	// Return our distance
	return distance < 0 ? 0 : distance;
};


/**
 * Utilities to work with Cookiess
 * For example:
 * setCookie("custom_cooke", "value", 3) // cookie name, value, days
 * getCookie("custom_cooke");
 * eraseCookie("eraseCookie");
 */
function setCookie(name,value,days) {
    let expires = "";
    if (days) {
        let date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}
function getCookie(name) {

    let nameEQ = name + "=";
    let cookiesArray = document.cookie.split(";");
    
    for(let i=0; i < cookiesArray.length; i++) {

        let cookie = cookiesArray[i];

        while (cookie.charAt(0)==" ") {
            cookie = cookie.substring(1, cookie.length);
        }

        if (cookie.indexOf(nameEQ) == 0) {
            return cookie.substring(nameEQ.length, cookie.length);
        }

    }
    return null;
}
function eraseCookie(name) {   
    document.cookie = name +"=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;";
}


/**
 * Check screen size greater or smaller than given size and execute callback
 * @param {string} comparison - The comparison operator ("larger" or "smaller")
 * @param {number} size - The size to compare against (in pixels)
 * @param {function} callback - The callback function to execute if the condition is met
 */
function checkScreenSize(comparison, size, callback) {
    if (comparison === "larger") {
        if (window.innerWidth > size) {
            callback();
        }
    } else if (comparison === "smaller") {
        if (window.innerWidth < size) {
            callback();
        }
    }
}

/**
 * Moves the contents from one element to another.
 * 
 * @param {HTMLElement} from - The source element from which the contents will be moved.
 * @param {HTMLElement} to - The target element where the contents will be moved to.
 * @returns {void}
 */
function moveContents(from, to) {
    if(from && to) {
        to.innerHTML = from.innerHTML;
        from.innerHTML = "";
    }
}

/**
 * Checks if the element's innerHTML is empty.
 * @param {HTMLElement} el 
 * @returns {boolean} True if the element's innerHTML is empty, false otherwise.
 */
function isContentsEmpty(el) {
    return el.innerHTML.trim() === '';
} 