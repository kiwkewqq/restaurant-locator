<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Restaurants Locator</title>
    <link rel="icon" type="image/png" href="img/restaurant.png"/>
    <!-- Bootstrap Core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">
    <script src="vendor/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDau-U8fZfnp8Qy9lH3hddU5FBccnpZgvk&libraries=places&callback=initMap"async defer></script>
    <style>
      #map {
        height: 75%;
      }
      html, body {
        height: 100%;
        margin: 0%;
        padding: 2%;
      }
    </style>
</head>
<body>
  <body>
      <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
              <img height="40" style="margin-top: -5px;"src="img/restaurant.png">
              <strong style="font-size:30px; color: white; margin-top:20px">&nbsp;Restaurant Locator</strong>
            </div>
            <form class="form-inline" style="text-align:right; margin-top:10px;">
                <input id="txtSearch" class="form-control" type="text" placeholder="Search" aria-label="Search" value="">
                <button id="btnSearch"class="btn btn-primary" type="submit">Search</button>
            </form>
        </div>
      </nav>
      <div id="map"></div>
      <h3 id="Nearby"><strong>Nearby Restaurants</strong></h3>
      <div id="content" class="row"></div>

      <script>
      var map,infoWindow;
      var point={lat:13.804294,lng:100.538179};
      var input;
      var markers = [];
      $(document).ready(function(){
        $('#btnSearch').click(function(){
          input = $('#txtSearch').val();
          var geocoder =  new google.maps.Geocoder();
          geocoder.geocode( { 'address': input}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
              var p_lat = results[0]['geometry']['location'].lat();
              var p_lng = results[0]['geometry']['location'].lng();
               point={lat: p_lat,lng: p_lng};
            } else {
               point={lat:13.804294,lng:100.538179};
            }
            $('#content').html("");
            clearMap();
          });
        });

        $('#content').on('click','div[id^=restaurant]',function(){
          var place = $(this).attr('data-id');
          var name = $(this).attr('name');
            clearMarkers(place,name);
          });
      });

        function initMap(){
          map = new google.maps.Map(document.getElementById('map'),{
            center:point,
            zoom:17
          });
          infoWindow = new google.maps.InfoWindow;
          var request ={
              location:point,
              radius: 500,
              types: ['restaurant']
          };
          var service = new google.maps.places.PlacesService(map);
          service.nearbySearch(request,callback);
          createMarker(point,false,"You are here");
        }
        function callback(results,status){
          if(status==google.maps.places.PlacesServiceStatus.OK){
              console.log(results);
            for (var i=0;i<results.length;i++){
              if(results[i]['opening_hours']['open_now']==true){
                $('#content').append("<a href='#'><div id='restaurant-"+i+"' data-id='"+results[i]['geometry']['location']+"' name='"+results[i]['name']+"'class='panel panel-primary' style='margin-top: 5px; padding: 5px;' title='click to see a location'>"+
                    "<div class='panel-heading'>"+results[i]['name']+"</div>"+
                    "<div class='panel-body'>"+
                      "<p>"+results[i]['vicinity']+"</p>"+
                    "</div>"+
                 "</div></a>");
              }else{
                $('#content').append("<a href='#'><div id='restaurant-"+i+"' data-id='"+results[i]['geometry']['location']+"' name='"+results[i]['name']+"'class='panel panel-danger' style='margin-top: 5px; padding: 5px;' title='click to see a location'>"+
                    "<div class='panel-heading'>"+results[i]['name']+"</div>"+
                    "<div class='panel-body'>"+
                      "<p>"+results[i]['vicinity']+"</p>"+
                      "<div style='text-align: right'><img src='img/closed.png' style='height:30;'></img></div>"+
                    "</div>"+
                 "</div></a>");
              }

            }
          }
        }
        function createMarker(place,flag,name){
                  if(flag==false){
                    var marker = new google.maps.Marker({
                      map: map,
                      position: point,
                      icon: "img/here-icon.png",
                      animation: google.maps.Animation.BOUNCE,
                      title: "click for more details"
                    });
                    google.maps.event.addListener(marker,'click',function(){
                      infoWindow.setContent(name);
                      infoWindow.open(map,marker);
                    });
                    markers.push(marker);
                  }
                  else {
                    var marker = new google.maps.Marker({
                      map: map,
                      position: place,
                      animation: google.maps.Animation.DROP,
                      title: "click for more details"
                    });
                    google.maps.event.addListener(marker,'click',function(){
                      infoWindow.setContent(name);
                      infoWindow.open(map,marker);
                    });
                    markers.push(marker);
                }
              }
        function clearMarkers(place,name) {
          var p_lat = Number(place.slice(1,11));
          var p_lng = Number(place.slice(13,24));
          var location = {lat: p_lat,lng: p_lng};
          setMapOnAll(null);
          markers = [];
          createMarker(point,false,"You are here");
          createMarker(location,true,name)
        }
        function setMapOnAll(map) {
          for (var i = 0; i < markers.length; i++){
            markers[i].setMap(map);
          }
        }
        function clearMap(){
          setMapOnAll(null);
          markers=[];
          initMap();
        }
      </script>
  </body>
</html>
