<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateUserAPIRequest;
use App\Http\Requests\API\UpdateUserAPIRequest;
use App\Models\User;
use App\Models\App;
use App\Models\Role;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use InfyOm\Generator\Utils\ResponseUtil;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Hash;

/**
 * Class UserController
 * @package App\Http\Controllers\API
 */

class UserAPIController extends AppBaseController
{
    /** @var  UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }


    
    public function rlogin(Request $request)
    {
        //$input = $request->all();
        $input = $request->all();


        if(empty($input)) {
            return Response::json(ResponseUtil::makeError('Credentials not provided!'), 400);
        }

        if(!$this->_validate_app(array_except($input, 'password'))) {
            return Response::json(ResponseUtil::makeError('You are not authorized to login from this app'), 400);
        }


        if(!$this->userRepository->first()) {
            return Response::json(ResponseUtil::makeError('Invalid Email'), 400);
        }

        $user = $this->userRepository->first()->getEmail($input['email'])->get();
        
        if(empty($user->toArray())) {
            return Response::json(ResponseUtil::makeError('Invalid Email'), 400);
        }
        
        $enc_pass = $user->toArray()[0]['password'];
        if(Hash::check($input['password'], $enc_pass)){
            return $this->sendResponse($user, 'Users retrieved successfully');
        }

        return Response::json(ResponseUtil::makeError('Password Incorrect'), 400);
    }

    public function rregister(Request $request)
    {
        //$input = $request->all();
        $input = $request->all();

        if(empty($input)) {
            return Response::json(ResponseUtil::makeError('Form entries are empty!'), 400);
        }
        
        //first entry
        if(!$this->userRepository->first()) {

            $user = $this->_register_users_and_attach_roles($input);
            return $this->sendResponse($user->toArray(), 'User registered successfully');

        }

        $user = $this->userRepository->first()->getEmail($input['email'])->get();
        if(!empty($user->toArray())) {
            return Response::json(ResponseUtil::makeError('This Email has already been taken!'), 400);
        }


        $user = $this->_register_users_and_attach_roles($input);
        return $this->sendResponse($user, 'User registered successfully');
    }

    private function _register_users_and_attach_roles($input) {
        $app_key = $this->_store_app(array_only($input, ['app_key' , 'app_name']));
        $users = $this->userRepository->create($input);
        $users->apps()->attach($app_key);
        $role = Role::where('name','app_user')->first();
        if(!empty($role)) {
            $role_arr = $role->toArray();
            $role_id = $role_arr['id'];
            $users->roles()->attach($role_id);
        }

        return $users;

    }

    protected function _store_app($app) {

        $existing_app = App::where('app_key', $app['app_key'])->first();

        if(!empty($existing_app)) {
            return  $existing_app['app_key'];
        }

        $app = App::create($app);
        return $app->app_key;
    }

    protected function _validate_app($data) {
        $app = $this->userRepository->findByField('email', $data['email'])->first()->apps();
        $app_data = $app->where('apps.app_key',$data['app_key'])->first();
        if(!empty($app_data)) {
            return true;
        }
        return false;
    }



}
