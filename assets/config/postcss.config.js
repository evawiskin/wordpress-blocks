module.exports = {
	plugins: [
		require('precss'),
		require('tailwindcss')('./config/tailwind.config.js'),
		require('autoprefixer'),
		require('cssnano')({
			preset: ['default', {
                discardComments: {
                    removeAll: true,
				}}]
        }),		
		require('postcss-sort-media-queries')
	]
}