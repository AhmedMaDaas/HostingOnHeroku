<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Department;
use Up;
use Storage;
use App\Http\Controllers\Notifications;

class departmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notifications = new Notifications();
        return view('admin.departments.index', ['title' => trans('admin.departments_control'), 'notifications' => $notifications]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $notifications = new Notifications();
        return view('admin.departments.create', ['notifications' => $notifications]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $this->validate(request(), [
            'name_ar' => 'required',
            'name_en' => 'required',
            'parent' => 'sometimes|nullable|numeric',
            'is_active' => 'required|in:active,inactive',
            'icon' => '',
            'keywords' => '',
            'description' => '',
        ]);

        $data['owner'] = 'admin';

        if(request()->has('icon')){
            $data['icon'] = Up::upload([
                'file' => 'icon',
                'uploadType' => 'single',
                'path' => 'departments',
                'deleteFile' => ''
            ]);
        }

        Department::create($data);

        session()->flash('success', trans('admin.record_created_successfully'));

        return redirect('admin/departments');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $department = Department::find($id);
        $notifications = new Notifications();
        return view('admin.departments.edit', ['department' => $department, 'notifications' => $notifications]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $this->validate(request(), [
            'name_ar' => 'required',
            'name_en' => 'required',
            'parent' => 'sometimes|nullable|numeric',
            'is_active' => 'required|in:active,inactive',
            'icon' => '',
            'keywords' => '',
            'description' => '',
        ]);

        $dep = Department::find($id);
        $depOwner = $dep->owner;
        $data['owner'] = $depOwner;

        if(request()->has('icon')){
            $data['icon'] = Up::upload([
                'file' => 'icon',
                'uploadType' => 'single',
                'path' => 'departments',
                'deleteFile' => $dep->icon,
            ]);
        }

        Department::where('id', $id)->update($data);

        session()->flash('success', trans('admin.record_updated_successfully'));

        return redirect('admin/departments');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public static function deleteSubDepartments($id){
        $subDepartments = Department::where('parent', $id)->get();

        foreach($subDepartments as $subDepartment){
            Storage::has($subDepartment->icon) ? Storage::delete($subDepartment->icon) : '';
            self::deleteSubDepartments($subDepartment->id);
            $subDepartment->delete();
        }
    }

    public function destroy($id)
    {
        $department = Department::find($id);
        self::deleteSubDepartments($id);
        Storage::has($department->icon) ? Storage::delete($department->icon) : '';
        $department->delete();
        session()->flash('success', trans('admin.record_deleted_successfully'));
        return back();
    }
}
