<?php

function gateway_bulutfon_name()
{
    return "Bulutfon";
}
function gateway_bulutfon_config()
{
    $configarray = array("apikey" => array("FriendlyName" => "API Key", "Type" => "password", "Description" => "Please Enter your api key"), "title" => array("FriendlyName" => "Title", "Type" => "text", "Description" => "Please Enter your title"));
    return $configarray;
}
function gateway_bulutfon_send($params)
{
    $url = "https://api.bulutfon.com/v2/sms/messages?apikey=" . $params["apikey"];
    $params["to"] = str_replace("+", "", $params["to"]);
    $data = array("receivers" => array($params["to"]), "content" => $params["message"]);
    if ($params["title"]) {
        $data["title"] = $params["title"];
    }
    $data_string = json_encode($data);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($ch);
    curl_close($ch);
    if ($response != "") {
        $response = json_decode($response, true);
        if (!isset($response["error"])) {
            return array("result" => "success", "response" => $response);
        }
        return array("result" => "error", "response" => $response["error"]["message"]);
    }
    return array("result" => "error", "response" => "Error connection/ check user/pass");
}
?>
