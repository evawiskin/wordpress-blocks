
// Create format object template
class Format {
	constructor(title, icon, type, tagName, className) {
		this.title = title;
		this.icon = icon;
		this.type = type;
		this.tagName = tagName;
		this.className = className
	}
}

// Add custom formats to this array
const formats = [
	new Format("Underline", "editor-underline", "underline", "span", "underline"), // Underline
    new Format("Asterisk", "star-filled", "decoration", "span", "hy-asterisk"), // HY Asterisk
	// new Format("Button Secondary", "button", "button-secondary", "span", "hy-button-primary-hollow"), // button-hollow
	// new Format("Button Primary", "button", "button-primary", "span", "hy-button-primary"), // button-primary
	// new Format("Error", "warning", "error", "span", "text-system-red") // Error
];

// Register formats
formats.forEach(format => {
	const newFormat = (props) => {
		return wp.element.createElement(
			wp.editor.RichTextToolbarButton,
			{
				icon: format.icon,
				title: format.title,
				onClick: () => {
					props.onChange(
						wp.richText.toggleFormat(
							props.value,
							{
								type: `${format.type}/output`
							}
						)
					)
				},
				isActive: props.isActive
			}
		)
	}
	wp.richText.registerFormatType(
		`${format.type}/output`, 
		{ title: format.title, tagName: format.tagName, className: format.className, edit: newFormat, } 
	);
})