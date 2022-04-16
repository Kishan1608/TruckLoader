function initMap() {
  var directionsService = new google.maps.DirectionsService;
  var directionsDisplay = new google.maps.DirectionsRenderer;
  const urul = {lat: 19.0453, lng: 72.8890}
  var map = new google.maps.Map(document.getElementById('map'), {
    zoom: 15,
    center: urul,
  });
  const marker = new google.maps.Marker({
    position: urul,
    map: map,
  });
  directionsDisplay.setMap(map);

  var onChangeHandler = function() {
    calculateAndDisplayRoute(directionsService, directionsDisplay);
  };
  document.getElementById('origin').addEventListener('change', onChangeHandler);
  document.getElementById('destination').addEventListener('change', onChangeHandler);
}


function calculateAndDisplayRoute(directionsService, directionsDisplay) {
  directionsService.route({
    origin: document.getElementById('origin').value,
    destination: document.getElementById('destination').value,
    travelMode: 'DRIVING'
  }, function(response, status) {
    if (status === 'OK') {
      directionsDisplay.setDirections(response);
    }
  });
}
