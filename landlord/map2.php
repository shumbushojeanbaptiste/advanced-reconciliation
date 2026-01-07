<style>
  #map {
    height: 100%;
  }
  html, body {
    height: 100%;
    margin: 0;
    padding: 0;
  }
</style>
<div class="card content-card">
    <header class="card-header">
        <a href="javascript:history.go(-1)" class="btn-icon btn-icon-primary btn-bar"><i class="far fa-arrow-left"></i></a>
        <h3 class="page-title">Add Address</h3>
        
        <div class="right-menu">
            <a href="#" class="btn-icon btn-icon-primary btn-lang">
                <svg class="icon-lang" width="1em" height="1em" viewBox="0 0 32 32">
                    <path fill-rule="evenodd" d="M22 22.424c-1.099.95-2.388 1.675-3.868 2.174-.952.268-1.919.402-2.901.402-1.48 0-2.721-.399-3.725-1.197C10.502 23.006 10 21.711 10 19.921c0-.743.117-1.463.35-2.16.233-.698.65-1.403 1.252-2.115.289-.317.505-.553.65-.708.144-.155.271-.266.382-.333-.792-.683-1.188-1.346-1.188-1.992-.03-.749.497-1.857 1.584-3.325C13.779 8.429 14.605 8 15.507 8c.455 0 .91.132 1.368.397.457.265.792.488 1.004.667.212.18.39.375.534.585.144.21.216.412.216.607-.006.03-.021.046-.046.046-.847-.305-1.565-.457-2.155-.457-.233 0-.532.054-.898.16-.365.107-.744.3-1.137.58-.393.28-.59.563-.59.85 0 .286.274.59.82.913.547.323.875.506.986.548.233-.03 1.092-.231 2.578-.603l1.073-.205c.237-.046.557-.117.963-.215l-.857 2.64h-.064c-.24 0-.792.085-1.658.256-4.163.962-6.244 2.552-6.244 4.768 0 .396.224.84.672 1.334.995.719 2.22 1.163 3.675 1.334.46.085.878.14 1.252.164.375.025 1.02.052 1.934.082A122.29 122.29 0 0122 22.424z"></path>
                </svg>
            </a>
            <a href="" class="btn-icon btn-icon-primary btn-cart"><i class="far fa-shopping-bag"></i></a>
        </div>
    </header>
    
    <div id="map"></div>
    
    <div class="card-footer border-0 px-20">
        <form action="<?=base_url()?>checkout/addaddress" method="POST">
            <input type="hidden" id="lat" name="lat">
            <input type="hidden" id="long" name="long">
            <input type="submit" name="submit" class="btn btn-primary btn-block" value="Add Address">
        </form>
        <a href="<?=base_url()?>checkout/skipaddress" class="btn btn-default btn-block"> Skip </a>
    </div>
</div>
<!-- End your project here-->

<script>
     function locate(){
        if ("geolocation" in navigator){
            navigator.geolocation.getCurrentPosition(function(position){ 
                var currentLatitude = position.coords.latitude;
                var currentLongitude = position.coords.longitude;

                var infoWindowHTML = "Latitude: " + currentLatitude + "<br>Longitude: " + currentLongitude;
                var infoWindow = new google.maps.InfoWindow({map: map, content: infoWindowHTML});
                var currentLocation = { lat: currentLatitude, lng: currentLongitude };
                infoWindow.setPosition(currentLocation);
                $("#lat").val(currentLatitude);
                $("#long").val(currentLongitude);
            });
        }
    }
</script>

<script>
      var myLatlng,map;
      function initMap() {
          locate();

      //myLatlng = {lat: 21.2130653, lng: 72.8402571};

      map = new google.maps.Map(
          document.getElementById('map'), {
            zoom: 20,
            center: myLatlng,
            //streetViewControl: 0
          });
      let markers = [];

      marker = new google.maps.Marker({
          position: myLatlng,
          map,
          title: "Your Location"
        });
        markers.push(marker);

      infoWindow = new google.maps.InfoWindow;

        // Try HTML5 geolocation.
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            var myLatlng = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };

            document.getElementById('lat').value=position.coords.latitude;
            document.getElementById('long').value=position.coords.longitude;
            map.setCenter(myLatlng);
            map.setZoom(18);
            //map.setStreetView(0);
          
            marker = new google.maps.Marker({
              position: myLatlng,
              map,
              title: "Your Location"
            });
            markers.push(marker);

          }, function() {
            handleLocationError(true, infoWindow, map.getCenter());
          });
        } else {
          // Browser doesn't support Geolocation
          handleLocationError(false, infoWindow, map.getCenter());
        }

        map.addListener('click', function(mapsMouseEvent) {
      // Close the current InfoWindow.
      for (let i = 0; i < markers.length; i++) {
          markers[i].setMap(null);
        }
        markers = [];
      // Create a new InfoWindow.

        var string = mapsMouseEvent.latLng.toString();                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            var array = string.split(",");
        var lat = array[0].substring(1);
        var dsfsd = array[1].trim();
        var long = dsfsd.substring(0,dsfsd.length-1);
      document.getElementById('lat').value=lat;
      document.getElementById('long').value=long;

        marker = new google.maps.Marker({
          position: mapsMouseEvent.latLng,
          map,
          title: "Your Location"
        });
      markers.push(marker);
    
    });

    marker.addListener("click", () => {
          infoWindow.open(map, marker);
        });



      }

      function handleLocationError(browserHasGeolocation, infoWindow, pos) {
        infoWindow.setPosition(pos);
        infoWindow.setContent(browserHasGeolocation ?
                              'Error: The Geolocation service failed.' :
                              'Error: Your browser doesn\'t support geolocation.');
        infoWindow.open(map);
      }
    </script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCuHX1kolcnapKN-MAW_hhgOLPXEbHVKQA&callback=initMap"></script>
