/** @format */

//function to count the characters in the summary text area, max at the character limit of the field, takes an element ID as an input variable
function characterCount(fieldID, outputField) {
	//get the element to monitor
	var fieldToWatch = document.getElementById(fieldID);

	//get the charater limit from the field
	var limit = fieldToWatch.maxLength;

	//get the output location
	var counterText = document.getElementById(outputField);

	//calculate if the limit has been reached
	var remaining = limit - fieldToWatch.value.length;

	//if the limit has been reached, set the text to red
	if (remaining <= 0) {
		counterText.style.setProperty("color", "#dc3545", "important");
	} else {
		counterText.style.color = "var(--bs-body-color)";
	}

	//display the remaining characters
	counterText.innerHTML = remaining + "/" + limit;
}
