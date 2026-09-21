import * as CookieConsent from 'https://cdn.jsdelivr.net/npm/vanilla-cookieconsent@3.0.1/+esm';

let cookiePopupSettings = {};
if('cookiePopupSettings' in window) {
    cookiePopupSettings = window.cookiePopupSettings;
}
else {
    cookiePopupSettings = {
        enabled: true,
        title: "This website uses cookies",
        description: "This website uses essential cookies to ensure its proper operation and tracking cookies to understand how you interact with it. The latter will be set only after consent.",
        chooseOptionsLinkText: "Let me choose",
        optionsTitle: "Cookie preferences",
        optionsHeading: "Cookie usage",
        optionsDescription: "We use cookies to ensure the basic functionalities of the website and to enhance your online experience. You can choose for each category to opt-in/out whenever you want. For more details relative to cookies and other sensitive data, please read the full",
        optionsPrivacyPolicyLink: {title: "Privacy Policy", url: "/privacy-policy"},
        contactHeading: "More information",
        contactDescription: "For any queries in relation to our policy on cookies and your choices, please",
        contactLink: {title: "contact us", url: "/contact"},
        necessaryTitle: "Strictly necessary cookies",
        necessaryDescription: "These cookies are essential for the proper functioning of my website. Without these cookies, the website would not work properly",
        analyticsTitle: "Performance and Analytics cookies",
        analyticsDescription: "These cookies allow the website to remember the choices you have made in the past",
        analyticsTable: [],
        targetingTitle: "Advertisement and Targeting cookies",
        targetingDescription: "These cookies collect information about how you use the website, which pages you visited and which links you clicked on. All of the data is anonymized and cannot be used to identify you",
        targetingTable: []
    }
}

// Used for pushing consent to Google Tag Manager
function gconsent() {
	const consent = {
		functionality_storage: 'denied',
		security_storage: 'denied',
		personalization_storage: 'denied',
		analytics_storage: 'denied',
		ad_storage: 'denied',
		ad_user_data: 'denied',
		ad_personalization: 'denied'
	};
	if (CookieConsent.acceptedCategory('necessary')) {
		consent['functionality_storage'] = 'granted';
		consent['security_storage'] = 'granted';
		consent['personalization_storage'] = 'granted';
	}

	if (CookieConsent.acceptedCategory('analytics')) {
		consent['analytics_storage'] = 'granted';
	}

	if (CookieConsent.acceptedCategory('targeting')) {
		consent['ad_storage'] = 'granted';
		consent['ad_user_data'] = 'granted';
		consent['ad_personalization'] = 'granted';
	}
	gtag('consent', 'update', consent);
	gtag('event', 'gtm.update_consent', consent);
}

// Used for toggling the cookie preferences button
function checkAndToggleCookieButton() {
    // This cookiePreferences is the element in the footer that allows someone to open the modal
	const cookiePreferences = document.getElementById("cookie-preferences");
	if(!cookiePreferences) {
		return;
	}

    // If we've got a cookie set then that means we need a footer button
	let cookie = getCookie("cc_cookie");
	if(cookie) {
		cookiePreferences.classList.remove("hidden");
	}
    else {
        cookiePreferences.classList.add("hidden");
    }
}

// Used within a map to reformat a cmsTableRow into a cookieTableRow
function cookieTableRow(cmsTableRow) {
    // TODO
    return {
        name: 'Name',
        domain: 'Domain',
        expiration: 'Expiration',
        description: 'Description'
    };
}

// Run plugin with your configuration - https://cookieconsent.orestbida.com/reference/configuration-reference.html
if(cookiePopupSettings.enabled) {
    CookieConsent.run({
        // Revision number to track changes to the policy
        revision: 0,

        // Non-standard config options
        disablePageInteraction: false,

        // Callback triggers on consent option changes
        onChange: (cookie) => gconsent(),

        // Callback triggered only once on the first accept/reject action
        onFirstConsent: () => gconsent(),

        // Callback triggers on every page load and consent change
        // This includes the first consent as well
        // Which means we can use it to show our cookie button if necessary
        onConsent: ({cookie}) => checkAndToggleCookieButton(),

        // GUI config options, not required, worth specifying
        guiOptions: {
            consentModal: {
                layout: 'box',
                position: 'bottom left',
                flipButtons: false,
                equalWeightButtons: true
            },
            preferencesModal: {
                layout: 'box',
                position: '',
                flipButtons: false,
                equalWeightButtons: true
            }
        },

        // Required config options
        categories: {
            necessary: {
                enabled: true,
                readOnly: true
            },
            analytics: {},
            targeting: {}
        },
        language: {
            default: 'en',
            translations: {
                en: {
                    consentModal: {
                        title: cookiePopupSettings.title,
                        description: `${cookiePopupSettings.description} <button type="button" data-cc="show-preferencesModal" class="cc__link">${cookiePopupSettings.chooseOptionsLinkText}</button>`,
                        acceptAllBtn: 'Continue and accept',
                        acceptNecessaryBtn: 'Required cookies only'
                    },
                    preferencesModal: {
                        title: cookiePopupSettings.optionsTitle,
                        acceptAllBtn: 'Continue and accept',
                        acceptNecessaryBtn: 'Required cookies only',
                        savePreferencesBtn: 'Save preferences',
                        sections: [
                            {
                                title: cookiePopupSettings.optionsHeading,
                                description: `${cookiePopupSettings.optionsDescription} <a href="${cookiePopupSettings.optionsPrivacyPolicyLink.url}" class="cc__link">${cookiePopupSettings.optionsPrivacyPolicyLink.title}</a>.`
                            }, 
                            {
                                title: `${cookiePopupSettings.necessaryTitle} <span class="pm__badge">Always Enabled</span>`,
                                description: cookiePopupSettings.necessaryDescription,
                                linkedCategory: 'necessary'
                            }, 
                            {
                                title: cookiePopupSettings.analyticsTitle,
                                description: cookiePopupSettings.analyticsDescription,
                                linkedCategory: 'analytics',
                                // cookieTable: {
                                //     caption: 'List of cookies',
                                //     headers: {
                                //         name: 'Name',
                                //         domain: 'Domain',
                                //         expiration: 'Expiration',
                                //         description: 'Description'
                                //     },
                                //     body: cookiePopupSettings.analyticsTable.map(cmsTableRow => cookieTableRow(cmsTableRow))
                                // }
                            }, 
                            {
                                title: cookiePopupSettings.targetingTitle,
                                description: cookiePopupSettings.targetingDescription,
                                linkedCategory: 'targeting',
                                // cookieTable: {
                                //     caption: 'List of cookies',
                                //     headers: {
                                //         name: 'Name',
                                //         domain: 'Domain',
                                //         expiration: 'Expiration',
                                //         description: 'Description'
                                //     },
                                //     body: cookiePopupSettings.targetingTable.map(cmsTableRow => cookieTableRow(cmsTableRow))
                                // }
                            }, 
                            {
                                title: cookiePopupSettings.contactHeading,
                                description: `${cookiePopupSettings.contactDescription} <a href="${cookiePopupSettings.contactLink.url}" class="cc__link">${cookiePopupSettings.contactLink.title}</a>.`,
                            }
                        ]
                    }
                }
            }
        }
    });
}