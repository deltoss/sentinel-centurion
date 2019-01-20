<?php

namespace Deltoss\Centurion\Http\Controllers\Roles;

use Sentinel;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

use Deltoss\Centurion\Http\Requests\Roles\UpdateRoleRequest;
use Deltoss\Centurion\Http\Requests\Roles\StoreRoleRequest;
use Deltoss\Centurion\Http\Requests\Roles\DestroyRoleRequest;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $builder = Sentinel::getRoleRepository()->with('users')->orderBy('name');
        $pageSize = $request->input('page_size');
        
        $roles = null;
        if ($pageSize)
        {
            $pageSize = min($pageSize, 100); // Dynamic page size from query string, but has an upper limit of 100
        
            // Perform pagination
            $roles = $builder->paginate($pageSize);
            // Append the additional parameters for 
            // dynamic parameters (e.g. page_size, etc)
            // to also affect the next/prev page links
            $roles->appends('page_size', $pageSize);
        }
        else
        {
            $roles = $builder->paginate(); // Use default pagination size setting, defined in model
        }
        return view('centurion::roles/index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('centurion::roles/create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Deltoss\Centurion\Http\Requests\Roles\StoreRoleRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRoleRequest $request)
    {   
        $name = $request->input('name');
        $slug = $request->input('slug') ? $request->input('slug') : null;
        
        // Create a new role
        $role = Sentinel::getRoleRepository()->createModel()->create([
            'name' => $name,
            'slug' => $slug,
        ]);

        // Flash stores variables only for the next request, and will be deleted from session afterwards
        // Useful to show messages
        $request->session()->flash('message', trans('centurion::roles.labels.create_success', ['name' => $role->name]));
        // redirect
        return redirect()->route('roles.index');
    }

    /**
     * Display the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $role = Sentinel::findRoleById($id);
        if(!$role)
            abort(404);
        return view('centurion::roles/show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $role = Sentinel::findRoleById($id);
        if(!$role)
            abort(404);
        return view('centurion::roles/edit', compact('role'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Deltoss\Centurion\Http\Requests\Roles\UpdateRoleRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRoleRequest $request, $id)
    {
        $role = $request->role;

        $originalName = $role->name;
        if(!$role)
            abort(404);
        
        $role->name = $request->input('name');
        $role->slug = $request->input('slug') ? $request->input('slug') : null;
        $role->save();

        // Flash stores variables only for the next request, and will be deleted from session afterwards
        // Useful to show messages
        $request->session()->flash('message', trans('centurion::roles.labels.update_success', ['name' => $originalName]));
        // redirect
        return redirect()->route('roles.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Deltoss\Centurion\Http\Requests\Roles\DestroyRoleRequest $request
     * @param int $id
     * @return Response
     */
    public function destroy(DestroyRoleRequest $request, $id)
    {
        $role = Sentinel::findRoleById($id);
        if(!$role)
            abort(404);
        $role->delete();

        // redirect
        $request->session()->flash('message', trans('centurion::roles.labels.delete_success', ['name' => $role->name]));
        return redirect()->route('roles.index');
    }
}
