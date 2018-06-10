<?php
/*
numverify class - Verify Phone Number
version 1.0 4/15/2018

API reference at https://numverify.com/documentation

Copyright (c) 2015, Wagon Trader

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
class numVerify{

    //*********************************************************
    // Settings
    //*********************************************************

    //Your numverify API key
    //Available at https://numverify.com/product
    private $apiKey = '2c55743510658c098967088384d5c841';

    //API endpoint
    //only needs to change if the API changes location
    private $endPoint = 'http://apilayer.net/api/validate';

    //holds the error code, if any
    public $errorCode;

    //holds the error text, if any
    public $errorText;

    //response object
    public $response;

    //JSON response from API
    public $responseAPI;

    /*
    method:  isValid
    usage:   isValid(string phoneNumber[string countryCode=''][,bool formatJSON=false][,string callBack='']);
    params:  phoneNumber = the phone number to be validated
             countryCode = 2-letter country code
             formatJSON = true to use pretified JSON for debugging
             curl = Use CURL to get response

    This method prepares the API request to verify the supplied phone number.
    If the phone number provided does not contain the country calling code, then you must proved the 2-letter
    country code.

    returns: true if phone number is valid or false if not
    */
    public function isValid($phoneNumber,$countryCode='',$formatJSON=false,$curl=false){

        $request = $this->endPoint.'?access_key='.$this->apiKey.'&number='.$phoneNumber;

        $request .= ( empty($countryCode) ) ? '' : '&country_code='.$countryCode;

        $request .= ( empty($formatJSON) ) ? '' : '&format=1';

        $this->response = ( empty($curl) ) ? $this->sendRequest($request) : $this->sendRequestCURL($request);

        if( !empty($this->response->error->code) ){

            $this->errorCode = $this->response->error->code;
            $this->errorText = $this->response->error->info;

            return false;

        }elseif( empty($this->response->valid) ){

            return false;

        }else{}{

            return true;

        }

    }

    /*
    method:  sendRequest
    usage:   sendRequest(string request);
    params:  request = full endpoint for API request

    This method sends the API request and decodes the JSON response.

    returns: object of request results
    */
    public function sendRequest($request){

        $this->responseAPI = file_get_contents($request);

        $return = json_decode($this->responseAPI);

        return $return;

    }

    /*
    method:  sendRequestCURL
    usage:   sendRequestCURL(string request);
    params:  request = full endpoint for API request

    This method sends the API request by CURL and decodes the JSON response.

    returns: object of request results
    */
    public function sendRequestCURL($request){

        $curl = curl_init($request);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        $this->responseAPI = curl_exec($curl);
        curl_close($curl);

        $return = json_decode($this->responseAPI);

        return $return;

    }

}
?>
