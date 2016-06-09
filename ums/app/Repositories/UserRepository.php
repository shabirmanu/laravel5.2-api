<?php

namespace App\Repositories;

use App\Models\User;
use InfyOm\Generator\Common\BaseRepository;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class UserRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return User::class;
    }

    public function delegateArticles($user_id, $delegate_id) {
        $endpoint = env('BLOG_APP_URL', '10.10.10.93:8000').'/api/';
        $httpClient = new Client(['base_uri' => $endpoint]);
        $post_data = array(
            'user_id' => $user_id,
            'delegate_id' => $delegate_id,
        );
        try {

            $response = $httpClient->request('GET', 'v1/articles/delegate', ['query' => $post_data]);
            $body =  json_decode($response->getBody());
            $return['data'] =  $body->data;
            $return['status'] = 'success';
        } catch (ClientException $e) {
            $return['data'] =  '';
            $return['status'] = $e->getResponse()->getBody()->getContents();
        }

        return $return;
    }
    public function articles($user_id) {
        $endpoint = env('BLOG_APP_URL', '10.10.10.93:8000').'/api/';
        $httpClient = new Client(['base_uri' => $endpoint]);

        try {
            $response = $httpClient->request('GET', 'v1/articles/'.$user_id);
            $body =  json_decode($response->getBody());
            $return['data'] =  $body->data;
            $return['status'] = 'success';
        } catch (ClientException $e) {
            $return['data'] =  '';
            $return['status'] = $e->getResponse()->getBody()->getContents();
        }

        return $return;

    }
}
