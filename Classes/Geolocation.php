<?php
class Geolocation {
    public $lat, $lng, $metadata;

    public function __construct($latitude='', $longitude='')
    {
        
        $this->lat = ($latitude == '' && $longitude == '' OR empty($latitude) && empty($longitude)) ? Cookie::get('latitude') : $this->lat;
        $this->lng = ($latitude == '' && $longitude == '' OR empty($latitude) && empty($longitude)) ? Cookie::get('longitude') : $this->lng;

        $url = sprintf("https://maps.googleapis.com/maps/api/geocode/json?latlng=%s,%s", $this->lat, $this->lng);

        if ( Curl::get($url) != '' )
        {

            $content = file_get_contents($url); // get json content

            $this->metadata = json_decode($content, true); //json decoder

            return $this->metadata;
        }
        else
        {
            return false;
        }
    }

    public function city() {
        if(count($this->metadata['results']) > 0) {
            // for format example look at url
            // https://maps.googleapis.com/maps/api/geocode/json?latlng=40.714224,-73.961452
            $result = $this->metadata['results'][0];

            // save it in db for further use
            return $result['address_components'][1]['long_name'];

        }
        
        return false;
    }

    public function latitude() {
        return $this->lat;
    }

    public function longitude() {
        return $this->lng;
    }
}
