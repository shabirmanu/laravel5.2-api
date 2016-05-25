<?php

namespace App\Http\Controllers\Rauth;

use Auth;
use GuzzleHttp\Client;


class AuthController extends Controller {

  public function authenticate() {

    $httpClient = new Client();

    $endpoint = 'http://localhost:8080/api/v1/rlogin';
    $post_data = array(
      'email' => $username,
      'password' => $password,
    );

// Send a post to the base url
    $response = $httpClient->post($endpoint, $post_data)->send();
    print_r($response); exit;

  }

}