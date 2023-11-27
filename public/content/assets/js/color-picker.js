/** @format */

// Create a new color picker instance
// https://iro.js.org/guide.html#getting-started
const colorPicker = new iro.ColorPicker("#color-picker", {
	// Set the size of the color picker
	width: 250,
	// Set the initial color
	color: currentColor,
	// Set the border radius of the color picker
	borderRadius: 3,
	// Set the border color
	borderColor: "#fff",
});

var values = document.getElementById("values");
var input = document.getElementById("schoolColor");
var preview = document.getElementById("color-block");

// https://iro.js.org/guide.html#color-picker-events
colorPicker.on(["color:init", "color:change"], function (color) {
	// Show the current color in different formats
	// Using the selected color: https://iro.js.org/guide.html#selected-color-api
	values.innerHTML = [
		"hex: " + color.hexString,
		"rgb: " + color.rgbString,
		"hsl: " + color.hslString,
	].join("<br>");

	input.value = color.hexString;

	//set the background color of the preview div
	preview.style.backgroundColor = color.hexString;
});

input.addEventListener("change", function () {
	colorPicker.color.hexString = this.value;
});
