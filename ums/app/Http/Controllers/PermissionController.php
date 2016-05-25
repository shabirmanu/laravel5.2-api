<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\CreatePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use App\Models\Role;
use App\Repositories\PermissionRepository;
use App\Repositories\RoleRepository;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use DB;
use Zizaco\Entrust\Entrust;

class PermissionController extends AppBaseController
{
    /** @var  PermissionRepository */
    private $permissionRepository;
    private $entrust;

    public function __construct(PermissionRepository $permissionRepo, Entrust $entrust)
    {
        $this->entrust = $entrust;
        $this->permissionRepository = $permissionRepo;
    }

    /**
     * Display a listing of the Permission.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->permissionRepository->pushCriteria(new RequestCriteria($request));
        $permissions = $this->permissionRepository->all();

        return view('permissions.index')
            ->with('permissions', $permissions);
    }

    /**
     * Display a listing of the Permission.
     *
     * @param RoleRepository $roleRepo
     * @return Response
     */
    public function assignPermission(RoleRepository $roleRepo, Request $request)
    {

        $roles_perm = DB::table('permission_role')->get();
        $existing_roles_perms = [];
        foreach($roles_perm as $value) {
            $existing_roles_perms[] = $value->role_id.'_'.$value->permission_id;
        }
        $this->permissionRepository->pushCriteria(new RequestCriteria($request));
        $permissions = $this->permissionRepository->all();
        $roles = $roleRepo->all();


        return view('permissions.assignperms' , compact('permissions', 'roles', 'existing_roles_perms'));

    }

    /**
     * Show the form for creating a new Permission.
     *
     * @return Response
     */
    public function create()
    {

        return view('permissions.create');
    }


    /**
     * Store a newly created Permission in storage.
     *
     * @param CreatePermissionRequest $request
     *
     * @return Response
     */
    public function storePerm(CreatePermissionRequest $request)
    {
        $input = $request->input('role');
        $data = [];
        foreach($input as $value) {
            $role_id = explode('_', $value)[0];
            $permission_id = explode('_', $value)[1];
            $data[$permission_id][] = $role_id;


        }


//        $this->_debug($data);

        foreach($data as $perm_id => $role_ids) {
            $perm = $this->permissionRepository->find($perm_id);
            $perm->roles()->sync($role_ids);
        }



        Flash::success('Permission saved successfully.');



        return redirect(url('people/permission'))->with([
                'flash_message' => 'Permission saved successfully.',
        ]

        );
    }
    /**
     * Store a newly created Permission in storage.
     *
     * @param CreatePermissionRequest $request
     *
     * @return Response
     */
    public function store(CreatePermissionRequest $request)
    {
        $input = $request->all();

        $permission = $this->permissionRepository->create($input);

        Flash::success('Permission saved successfully.');

        return redirect(route('permissions.index'));
    }

    /**
     * Display the specified Permission.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $permission = $this->permissionRepository->findWithoutFail($id);

        if (empty($permission)) {
            Flash::error('Permission not found');

            return redirect(route('permissions.index'));
        }

        return view('permissions.show')->with('permission', $permission);
    }

    /**
     * Show the form for editing the specified Permission.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $permission = $this->permissionRepository->findWithoutFail($id);

        if (empty($permission)) {
            Flash::error('Permission not found');

            return redirect(route('permissions.index'));
        }

        return view('permissions.edit')->with('permission', $permission);
    }

    /**
     * Update the specified Permission in storage.
     *
     * @param  int              $id
     * @param UpdatePermissionRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePermissionRequest $request)
    {
        $permission = $this->permissionRepository->findWithoutFail($id);

        if (empty($permission)) {
            Flash::error('Permission not found');

            return redirect(route('permissions.index'));
        }

        $permission = $this->permissionRepository->update($request->all(), $id);

        Flash::success('Permission updated successfully.');

        return redirect(route('permissions.index'));
    }

    /**
     * Remove the specified Permission from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $permission = $this->permissionRepository->findWithoutFail($id);

        if (empty($permission)) {
            Flash::error('Permission not found');

            return redirect(route('permissions.index'));
        }

        $this->permissionRepository->delete($id);

        Flash::success('Permission deleted successfully.');

        return redirect(route('permissions.index'));
    }

    /**
     * @param $data
     */
    private function _debug($data)
    {
        echo "<pre>";
        print_r($data);
        exit();
    }
}
