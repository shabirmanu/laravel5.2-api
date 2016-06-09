<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\User;
use App\Models\Role;
use App\Models\App;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

class UserController extends AppBaseController
{
    /** @var  UserRepository */
    private $userRepository;
    private $user;

    public function __construct(UserRepository $userRepo, User $user)
    {
        $this->userRepository = $userRepo;
        $this->user = $user;
    }

    /**
     * Display a listing of the User.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->userRepository->pushCriteria(new RequestCriteria($request));
        $users = $this->userRepository->all(['id', 'name', 'email', 'created_at', 'updated_at']);

        //dd($users);
        return view('users.index')
            ->with('users', $users);
    }

    /**
     * Show the form for creating a new User.
     *
     * @return Response
     */
    public function create()
    {
        $apps = App::lists('app_name','apps.app_key');
        $roles = Role::lists('display_name','id');
        return view('users.create', compact('apps','roles'));
    }

    /**
     * Store a newly created User in storage.
     *
     * @param CreateUserRequest $request
     *
     * @return Response
     */
    public function store(CreateUserRequest $request)
    {
        $input = $request->all();

        $user = $this->userRepository->create($input);

        $user->apps()->sync($request->input('app_list'));
        $user->roles()->sync($request->input('role_list'));

        Flash::success('User saved successfully.');

        return redirect(route('users.index'));
    }

    /**
     * Display the specified User.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        return view('users.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified User.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        $apps = App::lists('app_name','apps.app_key');
        $roles = Role::lists('display_name','id');
        return view('users.edit', compact('user' , 'roles', 'apps'));
    }

    /**
     * Update the specified User in storage.
     *
     * @param  int              $id
     * @param UpdateUserRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUserRequest $request)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        $user = $this->userRepository->update($request->all(), $id);
        $user->apps()->sync($request->input('app_list'));
        $user->roles()->sync($request->input('role_list'));

        Flash::success('User updated successfully.');

        return redirect(route('users.index'));
    }

    /**
     * Remove the specified User from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function delegateUser($id)
    {
        $users = $this->userRepository->findWhereNotIn('id', [$id], ['id', 'name']);

        $usersArr = [];

        foreach($users->toArray() as $user_id => $user) {
            $usersArr[$user['id']] =$user['name'];
        }


        $articles = $this->userRepository->articles($id);
        if($articles['status'] == 'success') {
            return view('users.delegate', compact('usersArr' , 'articles','id'));
        }
        else {
            $e = json_decode($articles['status']);
            return view('users.delegate', compact('e','id'));
        }

    }

    /**
     * Remove the specified User from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        $this->userRepository->delete($id);

        Flash::success('User deleted successfully.');

        return redirect(route('users.index'));
    }

    public function delegateAndDestroy(Request $request) {
        $ret = $this->userRepository->delegateArticles($request['user_id'], $request['delegate_id']);
        if($ret['data'] == 'Success') {
            return $this->destroy($request['user_id']);
        }
        return redirect(route('users.index'))->with(
            [
                'flash_message' => 'Error deleting user',
            ]
        );
    }
}
