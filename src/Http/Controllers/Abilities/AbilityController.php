<?php

namespace Deltoss\Centurion\Http\Controllers\Abilities;

use Sentinel;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use Deltoss\Centurion\Http\Requests\Abilities\UpdateAbilityRequest;
use Deltoss\Centurion\Http\Requests\Abilities\StoreAbilityRequest;
use Deltoss\Centurion\Http\Requests\Abilities\DestroyAbilityRequest;

class AbilityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $builder = Sentinel::getAbilityRepository()->with('roles')->with('users')->orderBy('name', 'ASC');
        $pageSize = $request->input('page_size');
        
        $abilities = null;
        if ($pageSize)
        {
            $pageSize = min($pageSize, 100); // Dynamic page size from query string, but has an upper limit of 100
        
            // Perform pagination
            $abilities = $builder->paginate($pageSize);
            // Append the additional parameters for 
            // dynamic parameters (e.g. page_size, etc)
            // to also affect the next/prev page links
            $abilities->appends('page_size', $pageSize);
        }
        else
        {
            $abilities = $builder->paginate(); // Use default pagination size setting, defined in model
        }
        return view('centurion::abilities/index', compact('abilities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $abilityCategories = Sentinel::getAbilityCategoryRepository()->orderBy('name', 'ASC')->get();
        return view('centurion::abilities/create', compact('abilityCategories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Deltoss\Centurion\Http\Requests\Abilities\StoreAbilityRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAbilityRequest $request)
    {   
        $abilityCategory = null;
        if ($request->abilityCategory)
        {
            $abilityCategory = $request->abilityCategory;
        }
        else if ($request->newAbilityCategoryText)
        {
            $abilityCategory = Sentinel::getAbilityCategoryRepository()->createModel();
            $abilityCategory->name = $request->newAbilityCategoryText;
            $abilityCategory->save();
        }
        
        $name = $request->input('name');
        $slug = $request->input('slug') ? $request->input('slug') : null;
        
        // Create a new ability
        $ability = Sentinel::getAbilityRepository()->createModel();
        $ability->name = $name;
        $ability->slug = $slug;
        $ability->ability_category_id = $abilityCategory->id;
        $ability->save();

        // Flash stores variables only for the next request, and will be deleted from session afterwards
        // Useful to show messages
		$request->session()->flash('message', trans('centurion::permissions.labels.create_success', ['name' => $ability->name]));
        // redirect
		return redirect()->route('abilities.index');
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
        $ability = Sentinel::getAbilityRepository()->findById($id);
        if(!$ability)
            abort(404);
        return view('centurion::abilities/show', compact('ability'));
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
        $ability = Sentinel::getAbilityRepository()->findById($id);
        if(!$ability)
            abort(404);
        $abilityCategories = Sentinel::getAbilityCategoryRepository()->orderBy('name', 'ASC')->get();
        return view('centurion::abilities/edit', compact('ability', 'abilityCategories'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param \Deltoss\Centurion\Http\Requests\Abilities\UpdateAbilityRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAbilityRequest $request, $id)
    {
        $abilityCategory = null;
        if ($request->abilityCategory)
        {
            $abilityCategory = $request->abilityCategory;
        }
        else if ($request->newAbilityCategoryText)
        {
            $abilityCategory = Sentinel::getAbilityCategoryRepository()->createModel();
            $abilityCategory->name = $request->newAbilityCategoryText;
            $abilityCategory->save();
        }

        $ability = $request->ability;

        $originalName = $ability->name;
        $originalAbilityCategory = $ability->abilityCategory;
        
        $ability->name = $request->input('name');
        $ability->slug = $request->input('slug') ? $request->input('slug') : null;
        $ability->ability_category_id = $abilityCategory->id;
        $ability->save();

        // If ability category was changed, and
        // the permission was the only item 
        // in the category, delete the category
        if ($originalAbilityCategory->id != $ability->ability_category_id && $originalAbilityCategory->abilities()->count() == 0)
            $originalAbilityCategory->delete();

        // Flash stores variables only for the next request, and will be deleted from session afterwards
        // Useful to show messages
        $request->session()->flash('message', trans('centurion::permissions.labels.update_success', ['name' => $originalName]));
        // redirect
        return redirect()->route('abilities.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Deltoss\Centurion\Http\Requests\Abilities\DestroyAbilityRequest $request
     * @param int $id
     * @return Response
     */
    public function destroy(DestroyAbilityRequest $request, $id)
    {
        $ability = $request->ability;
        $ability->delete();
        // If the permission was the only item in the category,
        // delete the category
        if ($ability->abilityCategory->abilities()->count() == 0)
            $ability->abilityCategory->delete();

        // redirect
        $request->session()->flash('message', trans('centurion::permissions.labels.delete_success', ['name' => $ability->name]));
        return redirect()->route('abilities.index');
    }
}
