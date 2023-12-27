/** @format */

//function to generate a random password and fill it into the password field
function generateRandomPassword() {
	//set the length of the password
	var length = 14,
		charset =
			"abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*?";
	//create a string to hold the password
	var password = "";

	//password generation idea from https://dev.to/code_mystery/random-password-generator-using-javascript-6a by Foolish Developer
	for (var i = 0; i <= length; i++) {
		//var randomNum = Math.floor(Math.random() * charset.length);  some information suggests math.random is not secure, so using crypto.getRandomValues instead - https://developer.mozilla.org/en-US/docs/Web/API/crypto_property
		var randomNum = new Uint32Array(1); //create a Uint32Array with a length of 1
		window.crypto.getRandomValues(randomNum); //fill the Uint32Array with a cryptographically secure random value
		password += charset.charAt(randomNum % charset.length); //use the random value to get a character from the charset and add it to the password string
	}

	//set the password field to the generated password
	document.getElementById("password").value = password;

	//set the confirm password field to the generated password
	document.getElementById("confirmPassword").value = password;
}

//function to show the password in the password field
function showPasswordValue() {
	//get the password field
	var passwordField = document.getElementById("password");

	//check if the password field is type password
	if (passwordField.type === "password") {
		//if it is, change it to type text
		passwordField.type = "text";
	} else {
		//if it is not, change it to type password
		passwordField.type = "password";
	}
}

//function to show the current password in the password field
function showCurrentPasswordValue() {
	//get the current password field
	var currentPasswordField = document.getElementById("currentPassword");

	//check if the current password field is type password
	if (currentPasswordField.type === "password") {
		//if it is, change it to type text
		currentPasswordField.type = "text";
	} else {
		//if it is not, change it to type password
		currentPasswordField.type = "password";
	}
}

//function to show the confirm password in the password field
function showConfirmPasswordValue() {
	//get the confirm password field
	var confirmPasswordField = document.getElementById("confirmPassword");

	//check if the confirm password field is type password
	if (confirmPasswordField.type === "password") {
		//if it is, change it to type text
		confirmPasswordField.type = "text";
	} else {
		//if it is not, change it to type password
		confirmPasswordField.type = "password";
	}
}

//function to check if the password and confirm password fields match
function checkPasswordMatch() {
	//get the password and confirm password fields
	var password = document.getElementById("password");
	var confirmPassword = document.getElementById("confirmPassword");

	//check if the password and confirm password fields match
	if (password.value != confirmPassword.value) {
		//if they do not match, set the confirm password field to invalid
		confirmPassword.setCustomValidity("Passwords do not match");
	} else {
		//if they do match, set the confirm password field to valid
		confirmPassword.setCustomValidity("");
	}
}

//add an event listener to the password field to check if the password and confirm password fields match
password.addEventListener("keyup", checkPasswordMatch);
confirmPassword.addEventListener("keyup", checkPasswordMatch);
