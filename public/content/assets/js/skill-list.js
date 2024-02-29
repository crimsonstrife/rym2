/** @format */

//variable to hold the last selected skill
var lastSelectedSkill = null;

//only run the script if the jobSkillsList element exists
if (document.getElementById("jobSkillsList")) {
	//add an event listener to the items of skills, for click events
	var list = document.querySelectorAll("#jobSkillsList li");
	for (var i = 0; i < list.length; i++) {
		list[i].addEventListener("click", function (e) {
			//if the last selected skill is not null, remove the selected class
			if (lastSelectedSkill != null) {
				lastSelectedSkill.classList.remove("selected");
			}

			//add the selected class to the selected skill
			e.target.classList.add("selected");

			//if the last selected skill is the same as the selected skill, remove the selected class
			if (lastSelectedSkill == e.target) {
				e.target.classList.remove("selected");
				lastSelectedSkill = null;
			}

			//set the last selected skill to the selected skill
			lastSelectedSkill = e.target;
		});
	}

	//add a listener to the list of skills, for when a new child is added - uses MutationObserver because the DOMNodeInserted event is being deprecated
	// create a new MutationObserver instance
	var observer = new MutationObserver(function (mutationsList) {
		// iterate through the mutations
		for (var mutation of mutationsList) {
			// check if nodes were added
			if (mutation.type === "childList" && mutation.addedNodes.length > 0) {
				// add an event listener to the newly added skills
				mutation.addedNodes.forEach(function (node) {
					node.addEventListener("click", function (e) {
						//if the last selected skill is not null, remove the selected class
						if (lastSelectedSkill != null) {
							lastSelectedSkill.classList.remove("selected");
						}

						//add the selected class to the selected skill
						e.target.classList.add("selected");

						//if the last selected skill is the same as the selected skill, remove the selected class
						if (lastSelectedSkill == e.target) {
							e.target.classList.remove("selected");
							lastSelectedSkill = null;
						}

						//set the last selected skill to the selected skill
						lastSelectedSkill = e.target;
					});
				});
			}
		}
	});

	// observe changes in the jobSkillsList element
	observer.observe(document.getElementById("jobSkillsList"), {
		childList: true,
	});

	//function to add a skill to the list
	function addSkill() {
		//get the skill from the input field
		var skill = document.getElementById("jobSkills").value;

		//if the skill is empty, do nothing
		if (skill == null) {
			return;
		} else {
			//if the skill is not empty, add it to the list
			//check if there are multiple skills in the input field, delimited by a comma
			if (skill.includes(",")) {
				//if there are multiple skills, split them into an array
				var skillArray = skill.split(",");

				//for each skill in the array, add it to the list
				for (var i = 0; i < skillArray.length; i++) {
					//get the list of skills
					var skillList = document.getElementById("jobSkillsList");

					//create a new list item element
					var option = document.createElement("li");

					//set the inner text of the list item to the skill
					option.innerText = skillArray[i];

					//set the class of the list item
					option.className = "list-group-item job-skill-item";

					//set a unique id to the list item
					option.id = uniqueId();

					//add the list item to the list
					skillList.appendChild(option);

					//clear the input field
					document.getElementById("jobSkills").value = "";

					//update the list of skills as an array in the hidden field
					updateSkillArray();
				}
			} else {
				//if there is only one skill, add it to the list
				//get the list of skills
				var skillList = document.getElementById("jobSkillsList");

				//create a new list item element
				var option = document.createElement("li");

				//set the inner text of the list item to the skill
				option.innerText = skill;

				//set the class of the list item
				option.className = "list-group-item job-skill-item";

				//set a unique id to the list item
				option.id = uniqueId();

				//add the list item to the list
				skillList.appendChild(option);

				//clear the input field
				document.getElementById("jobSkills").value = "";

				//update the list of skills as an array in the hidden field
				updateSkillArray();
			}
		}
	}

	//function to remove a skill from the list
	function removeSkill() {
		//get the list of skills
		var skillList = document.getElementById("jobSkillsList");

		//if the last selected skill is not null, remove it from the list
		if (lastSelectedSkill != null) {
			skillList.removeChild(lastSelectedSkill);

			//set the last selected skill to null
			lastSelectedSkill = null;
		} else {
			//remove the last item from the list
			skillList.removeChild(skillList.lastChild);
		}

		//update the list of skills as an array in the hidden field
		updateSkillArray();
	}

	//function to update the list of skills as an array in the hidden field
	// Move the function declaration outside of the block
	function updateSkillArray() {
		//get the list of skills
		var skillList = document.getElementById("jobSkillsList");

		//create an array to store the skills
		var skillArray = [];

		//for each skill in the list, add it to the array
		for (var i = 0; i < skillList.children.length; i++) {
			skillArray.push(skillList.children[i].innerText);
		}

		//get the hidden field
		var skillArrayField = document.getElementById("jobSkillsArray");

		//set the value of the hidden field to the array of skills
		skillArrayField.value = skillArray;
	}

	//event listener for when the document is loaded to look for skills in the list items
	document.addEventListener("DOMContentLoaded", function () {
		//get the list of skills
		var skillList = document.getElementById("jobSkillsList");

		//create an array to store the skills
		var skillArray = [];

		//for each skill in the list, add it to the array
		for (var i = 0; i < skillList.children.length; i++) {
			skillArray.push(skillList.children[i].innerText);
		}

		//get the hidden field
		var skillArrayField = document.getElementById("jobSkillsArray");

		//set the value of the hidden field to the array of skills
		skillArrayField.value = skillArray;
	});

	//add an input event listener to the skill input field
	document.getElementById("jobSkills").addEventListener("input", function () {
		//get the skill from the input field
		var skill = document.getElementById("jobSkills").value;

		//array to hold the list of pending skills if there are multiple skills in the input field
		var pendingSkills = [];

		//get the hidden field
		var skillArrayField = document.getElementById("jobSkillsArray");

		//get the array of skills from the hidden field
		var skillArray = skillArrayField.value.split(",");

		//if the skill is empty, do nothing
		if (skill == null) {
			return;
		} else {
			if (skill.includes(",")) {
				//determine if there are multiple skills in the input field, delimited by a comma
				pendingSkills = skill.split(",");

				//loop through the array of pending skills, if any match the skills in the skill array, disable the add button
				for (var i = 0; i < pendingSkills.length; i++) {
					//loop through the array of skills
					for (var j = 0; j < skillArray.length; j++) {
						//if the pending skill matches a skill in the skill array, disable the add button
						if (pendingSkills[i] == skillArray[j]) {
							document.getElementById("addSkill").disabled = true;
							return;
						} else {
							document.getElementById("addSkill").disabled = false;
						}
					}
				}
			} else {
				//if there is only one skill, check if it matches any of the skills in the skill array
				for (var i = 0; i < skillArray.length; i++) {
					//if the skill matches a skill in the skill array, disable the add button
					if (skill == skillArray[i]) {
						document.getElementById("addSkill").disabled = true;
						return;
					} else {
						document.getElementById("addSkill").disabled = false;
					}
				}
			}
		}
	});

	//add an event listener to the add skill button
	document.getElementById("addSkill").addEventListener("click", function () {
		//call the add skill function
		addSkill();
	});

	//add an event listener to the remove skill button
	document.getElementById("removeSkill").addEventListener("click", function () {
		//call the remove skill function
		removeSkill();
	});
}
