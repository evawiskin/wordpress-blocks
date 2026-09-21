const tailwindColors = require('./tailwind-colors.config');
const tailwindFonts = require('./tailwind-fonts.config');

module.exports = {
	// Context starts from the "assets" folder
	content: [
		// Look for all root PHP files in the wordpress theme directory
		'../*.php', 
		// Look for all nested PHP files in the wordpress theme directory
		'../**/*.php', 
		// Look for all root HTML files in the wordpress theme directory
		'../*.html', 
		// Look for all nested HTML files in the wordpress theme directory
		'../**/*.html', 
		// Look for all SRC JS files in the wordpress theme assets
		'./src/js/*.js',
		// Mu-plugin block source — add a new line per mu-plugin that ships
		// Tailwind classes so the JIT scan picks them up.
		'../../../mu-plugins/hiyield-blocks/src/**/*.php',
		'../../../mu-plugins/hiyield-blocks/src/**/*.js'
	],
	safelist: [
		{
			pattern: /(inside-container)-(sm|md|lg|xl|2xl)/
		},
		{
			pattern: /(bg|text|border)-(black|white)/
		},
		{
			pattern: /(bg|text|border)-(teal|purple|electric-green|neutral|deep-ocean-blue|sky-blue)-(500)/
		},
		{
			pattern: /(bg|text|border)-(sunshine-yellow|flamingo-pink|sky-blue)-(500)/,
			variants: ['hover']
		},
		{
			pattern: /(bg|text|border)-(forest-green)-(500|600)/
		},
		{
			pattern: /(grid-cols)-(1|2|3|4|5|6|8|10|12)/,
			variants: ['md','lg','xl']
		},
		{
			pattern: /(col-span)-(1|2|3|4|5|6|7|8|9|10|12)/,
			variants: ['md','lg','xl']
		},
		{
			pattern: /(col-start)-(1|2|3|4|5|6|7|8|9|10|11|12)/,
			variants: ['lg']
		},
		{
			pattern: /(gap)/,
			variants: ['md','lg','xl']
		},
		{
			pattern: /(flex)-(col|row)/,
			variants: ['sm','md','lg','xl']
		},
		{
			pattern: /(ring|ring-offset)-(black|white|transparent)/,
			variants: ['focus-within']
		},
		{
			pattern: /(ring|ring-offset)-(electric-green|forest-green|gretter)-(50|500|600)/,
			variants: ['focus-within']
		},
		{
			pattern: /(size)-(4|5|6|7|8|10|12|16|20|24)/
		}
	],
	theme: {
		...tailwindColors,
		...tailwindFonts,
		screens: {
			sm: "640px",
			md: "768px",
			lg: "1024px",
			xl: "1280px",
			'2xl': "1366px", // design sync with padding 2xl
			'3xl': "1440px", // design sync with padding 3xl
		},
		container: {
			center: true,
			padding: {
				DEFAULT: '1rem',
				sm: '2rem',
				md: '2rem',
				lg: '3rem',
				xl: '3rem',
				'2xl': '4.4375rem', // design sync with screen 2xl
				'3xl': '4.4375rem' // design sync with screen 3xl
			}
		},
		extend: {
			zIndex: {
				'60': '60',
			},
			minHeight: extend => extend('height'),
			maxHeight: extend => extend('height'),
			minWidth: extend => extend('width'),
			maxWidth: extend => extend('width'),
			boxShadow: {
				'inner-2': 'inset 0 4px 4px 0 rgba(0, 0, 0, 0.25)',
			}
		},
		aspectRatio: {
			auto: 'auto',
			square: '1 / 1',
			video: '16 / 9',
			1: '1',
			2: '2',
			3: '3',
			4: '4',
			5: '5',
			6: '6',
			7: '7',
			8: '8',
			9: '9',
			10: '10',
			11: '11',
			12: '12',
			13: '13',
			14: '14',
			15: '15',
			16: '16'
		}
	},
	plugins: [
		require('@tailwindcss/aspect-ratio'),
		require('tailwind-container-break-out')
	]
}