

/*
* Creates a google map instance, instantiated by a callback function
* which is run in the php file containing the google map api script.
* @return Side Effect - Google map is created.
*/

function initMap() {

    // Find HTML entities with location data attributes to get address co-ordinates from contact block
    const locationData = document.querySelectorAll(".google-map-location");

    let markers = [];

    // this is only going to work with 1 map atm, I guess we need to think if that's a problem in the future
    const map = new google.maps.Map(document.getElementById("google-map"), {

        //these will get overridden later as we have a trick whch centralises in the
        //geometric centrepoint of the markers (if more than 1)
        zoom: 10,
        center: {
            lat: parseFloat(locationData[0].dataset.lat),
            lng: parseFloat(locationData[0].dataset.lng)
        },
        disableDefaultUI: true		
    });


    //create markers on the map
    for (let i = 0; i < locationData.length; i++){
        markers.push(
            new google.maps.Marker({
                position: {
                    lat: parseFloat(locationData[i].dataset.lat),
                    lng: parseFloat(locationData[i].dataset.lng)
                },
                map: map,
                title: locationData[i].dataset.title,
                icon: {
                    path: "M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z",
                    fillColor: "#3C9496",
                    fillOpacity: 0.8,
                    strokeWeight: 0,
                    scale: 1.2,
                    anchor: new google.maps.Point(15, 30)
                }
            })
        )
    }

    //sets the centre of the map to the geometric centre of all the pins,
    //if more than 1 pin

    if (markers.length > 1) {
        let bounds = new google.maps.LatLngBounds()

        for (let i = 0; i < markers.length; i++) {
            bounds.extend(markers[i].getPosition())
        }

        map.setCenter(bounds.getCenter())
        map.fitBounds(bounds)
    }

    //add map styles
    const mapStyles = new google.maps.StyledMapType(
        [
            {
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#f5f5f5"
                    }
                ]
            },
            {
                "elementType": "labels.icon",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#616161"
                    }
                ]
            },
            {
                "elementType": "labels.text.stroke",
                "stylers": [
                    {
                        "color": "#f5f5f5"
                    }
                ]
            },
            {
                "featureType": "administrative.land_parcel",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#bdbdbd"
                    }
                ]
            },
            {
                "featureType": "poi",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#eeeeee"
                    }
                ]
            },
            {
                "featureType": "poi",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#757575"
                    }
                ]
            },
            {
                "featureType": "poi.park",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#e5e5e5"
                    }
                ]
            },
            {
                "featureType": "poi.park",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#9e9e9e"
                    }
                ]
            },
            {
                "featureType": "road",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#ffffff"
                    }
                ]
            },
            {
                "featureType": "road.arterial",
                "elementType": "labels",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "road.arterial",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#757575"
                    }
                ]
            },
            {
                "featureType": "road.highway",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#dadada"
                    }
                ]
            },
            {
                "featureType": "road.highway",
                "elementType": "labels",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "road.highway",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#616161"
                    }
                ]
            },
            {
                "featureType": "road.local",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "road.local",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#9e9e9e"
                    }
                ]
            },
            {
                "featureType": "transit.line",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#e5e5e5"
                    }
                ]
            },
            {
                "featureType": "transit.station",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#eeeeee"
                    }
                ]
            },
            {
                "featureType": "water",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#c9c9c9"
                    }
                ]
            },
            {
                "featureType": "water",
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "color": "#9e9e9e"
                    }
                ]
            }
        ]
    )

    //applies the styles to the map
    map.mapTypes.set("styled_map", mapStyles)
	map.setMapTypeId("styled_map")

}