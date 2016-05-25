<?php

namespace App\Http\Controllers\Auth;

use App\User;
use GuzzleHttp\Exception\ClientException;
use Mockery\CountValidator\Exception;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Auth;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    protected $request;
    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {

      $endpoint = 'http://localhost:8080/api/';
      $httpClient = new Client(['base_uri' => $endpoint]);
      $post_data = array(
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => bcrypt($data['password']),
        'api_token'=> str_random(60),
        'app_key' => env('APP_KEY'),
        'app_name' => env('APP_NAME'),
      );

      try {
        $response = $httpClient->request('GET', 'v1/rregister', ['query' => $post_data]);
        $body =  json_decode($response->getBody());
        $api_token = $body->data->api_token;
        $this->request->session()->put('api_token', $api_token);
        $this->request->session()->put('user', $body->data);
      } catch (ClientException $e) {
        dd($e->getResponse()->getBody()->getContents());

      }

    }

  /**
   * Handle a registration request for the application.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function register(Request $request)
  {
    $validator = $this->validator($request->all());

    if ($validator->fails()) {
      $this->throwValidationException(
        $request, $validator
      );
    }

    $this->create($request->all());
    return redirect($this->redirectPath());
  }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $throttles = $this->isUsingThrottlesLoginsTrait();

        if ($throttles && $lockedOut = $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->getCredentials($request);


        $endpoint = 'http://localhost:8080/api/v1';
        $httpClient = new Client(['base_uri' => $endpoint]);
        $post_data = array(
          'email' => $credentials['email'],
          'password' => $credentials['password'],
          'app_key' => env('APP_KEY'),
        );

        try {
            $response = $httpClient->request('GET', 'v1/rlogin', ['query' => $post_data]);

            $body =  json_decode($response->getBody());

            $api_token = $body->data[0]->api_token;
            $request->session()->put('api_token', $api_token);
            $request->session()->put('user', $body->data[0]);
        } catch (ClientException $e) {
            dd($e->getResponse()->getBody()->getContents());

        }


        return $this->handleUserWasAuthenticated($request, $throttles);
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $request->session()->flush();

        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }


}
