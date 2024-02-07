/** @format */
//function to toggle the media view between list and gallery
function toggleMediaView(view) {
	if (view == "list") {
		document.getElementById("list-view").classList.remove("hidden");
		document.getElementById("gallery-view").classList.add("hidden");
		document.getElementById("listView").classList.add("active");
		document.getElementById("galleryView").classList.remove("active");
	} else if (view == "gallery") {
		document.getElementById("list-view").classList.add("hidden");
		document.getElementById("gallery-view").classList.remove("hidden");
		document.getElementById("listView").classList.remove("active");
		document.getElementById("galleryView").classList.add("active");
	}
}

//function to format the filesize, since we can't use the php function in javascript
function formatFilesize(filesize) {
	if (filesize == null) {
		return "N/A";
	} else if (filesize < 1024) {
		return filesize + " B";
	} else if (filesize < 1048576) {
		return (filesize / 1024).toFixed(2) + " KB";
	} else if (filesize < 1073741824) {
		return (filesize / 1048576).toFixed(2) + " MB";
	} else {
		return (filesize / 1073741824).toFixed(2) + " GB";
	}
}

//function to check if a file exists via javascript
function fileExists(url) {
	var http = new XMLHttpRequest();
	http.open("HEAD", url, false);
	http.send();
	return http.status != 404;
}

//function to generate a unique id
function uniqueId() {
	var ui_id =
		Date.now().toString(36) +
		Math.random().toString(36).substring(2, 12).padStart(12, 0);

	return ui_id;
}
