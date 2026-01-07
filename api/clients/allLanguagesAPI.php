<?php 


##-----Payment----###
## Python

import http.client
import json

conn = http.client.HTTPSConnection("xode.rw")
payload = json.dumps({
  "pay_ref_no": "205106479",
  "slip_no": "2345678",
  "pay_amount": "440",
  "trans_code": "40577890"
})
headers = {
  'Content-Type': 'application/json'
}
conn.request("POST", "/api/clients/build/fee", payload, headers)
res = conn.getresponse()
data = res.read()
print(data.decode("utf-8"))

##cURL
curl --location --request POST 'https://xode.rw/api/clients/build/fee' \
--header 'Content-Type: application/json' \
--data-raw '{
   "pay_ref_no":"205106479" ,
   "slip_no":"2345678",
   "pay_amount":"440",
   "trans_code":"40577890"
   
}'

##php

<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://xode.rw/api/clients/build/fee',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
   "pay_ref_no":"205106479" ,
   "slip_no":"2345678",
   "pay_amount":"440",
   "trans_code":"40577890"
   
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;


##Jquery


var settings = {
  "url": "https://xode.rw/api/clients/build/fee",
  "method": "POST",
  "timeout": 0,
  "headers": {
    "Content-Type": "application/json"
  },
  "data": JSON.stringify({
    "pay_ref_no": "205106479",
    "slip_no": "2345678",
    "pay_amount": "440",
    "trans_code": "40577890"
  }),
};

$.ajax(settings).done(function (response) {
  console.log(response);
});


##Java Unirest


Unirest.setTimeouts(0, 0);
HttpResponse<String> response = Unirest.post("https://xode.rw/api/clients/build/fee")
  .header("Content-Type", "application/json")
  .body("{\r\n   \"pay_ref_no\":\"205106479\" ,\r\n   \"slip_no\":\"2345678\",\r\n   \"pay_amount\":\"440\",\r\n   \"trans_code\":\"40577890\"\r\n   \r\n}")
  .asString();
  
  
  ##HTTP
  
POST /api/clients/buld/fee HTTP/1.1
Host: xode.rw
Content-Type: application/json
Content-Length: 117

{
   "pay_ref_no":"205106479" ,
   "slip_no":"2345678",
   "pay_amount":"440",
   "trans_code":"40577890"
   
}


##JavaScript  Fetch


var myHeaders = new Headers();
myHeaders.append("Content-Type", "application/json");

var raw = JSON.stringify({
  "pay_ref_no": "205106479",
  "slip_no": "2345678",
  "pay_amount": "440",
  "trans_code": "40577890"
});

var requestOptions = {
  method: 'POST',
  headers: myHeaders,
  body: raw,
  redirect: 'follow'
};

fetch("https://xode.rw/api/clients/build/fee", requestOptions)
  .then(response => response.text())
  .then(result => console.log(result))
  .catch(error => console.log('error', error));



##Nodejs Request

var request = require('request');
var options = {
  'method': 'POST',
  'url': 'https://xode.rw/api/clients/build/fee',
  'headers': {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    "pay_ref_no": "205106479",
    "slip_no": "2345678",
    "pay_amount": "440",
    "trans_code": "40577890"
  })

};
request(options, function (error, response) {
  if (error) throw new Error(error);
  console.log(response.body);
});

##Java Script XHR

// WARNING: For POST requests, body is set to null by browsers.
var data = JSON.stringify({
  "pay_ref_no": "205106479",
  "slip_no": "2345678",
  "pay_amount": "440",
  "trans_code": "40577890"
});

var xhr = new XMLHttpRequest();
xhr.withCredentials = true;

xhr.addEventListener("readystatechange", function() {
  if(this.readyState === 4) {
    console.log(this.responseText);
  }
});

xhr.open("POST", "https://xode.rw/api/clients/build/fee");
xhr.setRequestHeader("Content-Type", "application/json");

xhr.send(data);


##Node Js Native


var https = require('follow-redirects').https;
var fs = require('fs');

var options = {
  'method': 'POST',
  'hostname': 'xode.rw',
  'path': '/api/clients/build/fee',
  'headers': {
    'Content-Type': 'application/json'
  },
  'maxRedirects': 20
};

var req = https.request(options, function (res) {
  var chunks = [];

  res.on("data", function (chunk) {
    chunks.push(chunk);
  });

  res.on("end", function (chunk) {
    var body = Buffer.concat(chunks);
    console.log(body.toString());
  });

  res.on("error", function (error) {
    console.error(error);
  });
});

var postData = JSON.stringify({
  "pay_ref_no": "205106479",
  "slip_no": "2345678",
  "pay_amount": "440",
  "trans_code": "40577890"
});

req.write(postData);

req.end();

##PHP Guzzle

<?php
$client = new Client();
$headers = [
  'Content-Type' => 'application/json'
];
$body = '{
  "pay_ref_no": "205106479",
  "slip_no": "2345678",
  "pay_amount": "440",
  "trans_code": "40577890"
}';
$request = new Request('POST', 'https://xode.rw/api/clients/build/fee', $headers, $body);
$res = $client->sendAsync($request)->wait();
echo $res->getBody();

##PHP pecl
<?php
$client = new http\Client;
$request = new http\Client\Request;
$request->setRequestUrl('https://xode.rw/api/clients/build/fee');
$request->setRequestMethod('POST');
$body = new http\Message\Body;
$body->append('{
   "pay_ref_no":"205106479" ,
   "slip_no":"2345678",
   "pay_amount":"440",
   "trans_code":"40577890"
   
}');
$request->setBody($body);
$request->setOptions(array());
$request->setHeaders(array(
  'Content-Type' => 'application/json'
));
$client->enqueue($request)->send();
$response = $client->getResponse();
echo $response->getBody();


##Python Client


import http.client
import json

conn = http.client.HTTPSConnection("xode.rw")
payload = json.dumps({
  "pay_ref_no": "205106479",
  "slip_no": "2345678",
  "pay_amount": "440",
  "trans_code": "40577890"
})
headers = {
  'Content-Type': 'application/json'
}
conn.request("POST", "/api/clients/build/fee", payload, headers)
res = conn.getresponse()
data = res.read()
print(data.decode("utf-8"))

?>