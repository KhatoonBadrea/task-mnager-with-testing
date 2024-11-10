<?php

namespace App\Http\Controllers\Api;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Services\RoleService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Role\StoreRequest;
use App\Http\Requests\Role\UpdateRequest;

class RoleController extends Controller
{ 

    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $role = Role::select('name', 'description')->get();
        return $this->success($role);
    }

    /**
     * Store a newly created resource in storage.
     * @param StoreRequest $request
     * @return @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $validationdata = $request->validated();
        $response = $this->roleService->create_Role($validationdata);
        if (!$response) {
            return $this->error();
        } else {
            return $this->success();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        return $this->success($role);
    }

    /** 
     * Update the specified resource in storage.
     * @param UpdateRequest $request
     * @param Role $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, Role $role)
    {
        $validatedRequest = $request->validated();

        $response = $this->roleService->update_Role($role, $validatedRequest);
        if (!$response) {

            return $this->error();
        } else {
            return $this->success();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return $this->success();
    }
}
