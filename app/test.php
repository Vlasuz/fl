<?php

$api_url = 'https://api.busel.biz/api/v2';
$api_key = 'UOQDfbJyoyiwKYG2BoddRfaon9DlA6fD';
$api_token = '151|o5OI51BkXnO37gfXhyPP1Ug768qDpn1tT92I24Fa';
$order_id = 206444259;


$link='https://www.instagram.com/kotiki.i.kotiki/';
$qty = 50;
$service = 97;

$post = array(
          'key' => $api_key,
          'action' => 'status',
          'order' => $order_id
      );

/*
$post = array(
          'key' => $api_key,
          'service' => $service,
          'action' => 'add',
          'link' => $link,
	  'quantity' => $qty
      );
*/

$_post = Array();
      if (is_array($post)) {
          foreach ($post as $name => $value) {
            $_post[] = $name.'='.urlencode($value);
          }
      }

      $ch = curl_init($api_url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
      if (is_array($post)) {
          curl_setopt($ch, CURLOPT_POSTFIELDS, join('&', $_post));
      }
      curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)');
      if ($api_token) {
          curl_setopt($ch, CURLOPT_HTTPHEADER,
              [
                  'Authorization: Bearer '.$api_token
              ]
          );
      }

      $result = curl_exec($ch);
      if (curl_errno($ch) != 0 && empty($result)) {
          $result = false;
      }
      curl_close($ch);
      print_r($result);
