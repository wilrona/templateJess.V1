<?php
/*
verify phone number
numverify ver 0.1
*/

//include the class
include('numverify.class.php');

//instantiate the class
$numVerify = new numVerify();

//phone number to check
//API can accept all numberic numbers or number with special characters like (555) 5555-55555
$phoneNumber = 'ADD_A_PHONE_NUMBER_HERE';

//logic to determine if number is valid, invalid or an error occured
if( $numVerify->isValid($phoneNumber,'US') === false ){
    
    if( !empty($numVerify->errorCode) ){
        //an error occured
        
        echo 'The request returned an error -> ['.$numVerify->errorCode.'] '.$numVerify->errorText;
        
    }else{
        //number is not valid
        
        echo 'The phone number '.$phoneNumber.' is NOT valid';
        
    }
        
}else{
    //number is valid
    
    echo 'The phone number '.$phoneNumber.' is valid';
    
}

//display the response object
var_dump($numVerify->response);

/*
a validation request will return the following object properties

valid - true or false
number - clean format of phone number provided
local_format - local/national format of phone number
international_format - international format of phone number with calling code
country_code - 2-letter country code
country_name - full country name
location - local location if available (country, state, etc)
carrier - name of phones carrier, service provider
line_type - line type
    mobile = mobile phone
    landline = land line
    special_services = police, fire, etc...
    toll_free = toll free number
    premium_rate = paid services number like hotlines

*/
?>
