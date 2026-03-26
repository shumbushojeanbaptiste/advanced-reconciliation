<?php
header("Content-Type: application/json");

$input = json_decode(file_get_contents("php://input"), true);

$ch = curl_init("https://pay.xode.rw/tenant/internal/info");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    "tenant_code" => $input["tenant_code"]
]));

$response = curl_exec($ch);
curl_close($ch);

echo $response;