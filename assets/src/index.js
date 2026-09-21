// Get all .css files from the css Folder
require.context('./css', false, /\.css$/);

// Get all .js files from the js Folder
require.context('./js', false, /\.js$/);

// Get all IMG files from the imgs Folder
require.context('./imgs', true, /\.(png|jpe?g|gif|svg)$/);

// Get all FONT files from the fonts Folder
require.context('./fonts', true, /\.(eot|ttf|woff|woff2)$/);

// Get all VENDOR files from the vendor Folder
require.context('./vendor', true);