/** @format */
var OpenStreetMapProvider = window.GeoSearch.OpenStreetMapProvider;
var addressNotFound = false;

//setup
const provider = new OpenStreetMapProvider();

//search
const results = await provider.search({ query: address });

//response
console.log(results); //array of objects

//check if the results are empty
if (results.length === 0) {
	console.log("No results found");
	addressNotFound = true;
} else {
	console.log("Results found");
	addressNotFound = false;
}

if (addressNotFound === true) {
	//set the map
	var map = L.map("map").setView([0, 0], 18);
	var marker = L.marker([0, 0]).addTo(map);
	marker
		.bindPopup(
			"<b>Event Location:</b><br>" + mapLocationTitle + "<br> Not Found."
		)
		.openPopup();
	L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
		maxZoom: 19,
		attribution:
			'&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
	}).addTo(map);
} else {
	//lat lng
	const lat = results[0].y;
	const lng = results[0].x;

	//set the map
	var map = L.map("map").setView([lat, lng], 18);
	var marker = L.marker([lat, lng]).addTo(map);
	marker.bindPopup("<b>Event Location:</b><br>" + mapLocationTitle).openPopup();
	L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
		maxZoom: 19,
		attribution:
			'&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>',
	}).addTo(map);
	//on click, open the google maps link in a new tab
	marker.on("click", function (e) {
		window.open(
			"https://www.google.com/maps/search/?api=1&query=" +
				lat +
				"," +
				lng +
				"&query_place_id=" +
				results[0].placeId
		);
	});
}
