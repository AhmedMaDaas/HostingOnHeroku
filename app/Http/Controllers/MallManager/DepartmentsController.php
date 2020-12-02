<?php

namespace App\Http\Controllers\MallManager;

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
        $allowedDepartments = $this->getAllowedDepartments();
        $notifications = new Notifications();
        return view('mall_manager.departments.index', ['title' => trans('admin.departments_control'), 'notifications' => $notifications, 'allowedDepartments' => $allowedDepartments, 'depId' => 0]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $notifications = new Notifications();
        return view('mall_manager.departments.create', ['notifications' => $notifications]);
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
            'icon' => 'sometimes|nullable|image|mimes:svg',
            'photo' => 'sometimes|nullable|image|mimes:svg,jpg,jpeg,png,gif',
            'keywords' => 'sometimes|nullable|string',
            'description' => 'sometimes|nullable|string',
        ]);

        $data['is_active'] = 'inactive';
        $data['owner'] = auth()->guard('web')->user()->id;

        if(request()->has('icon')){
            $data['icon'] = Up::upload([
                'file' => 'icon',
                'uploadType' => 'single',
                'path' => 'departments',
                'deleteFile' => ''
            ]);
        }

        if(request()->has('photo')){
            $data['photo'] = Up::upload([
                'file' => 'photo',
                'uploadType' => 'single',
                'path' => 'departments',
                'deleteFile' => ''
            ]);
        }

        Department::create($data);

        session()->flash('success', trans('admin.record_created_successfully'));

        return redirect('mall-manager/departments');
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
        $allowedDepartments = $this->getAllowedDepartments();
        if(!$this->checkDepartmentExist($id, $allowedDepartments)){
            session()->flash('error', trans('admin.manager_delete_or_update_error'));
            return redirect('mall-manager/departments');
        }

        $notifications = new Notifications();

        $department = Department::find($id);
        return view('mall_manager.departments.edit', ['notifications' => $notifications, 'department' => $department]);
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
        $allowedDepartments = $this->getAllowedDepartments();
        if(!$this->checkDepartmentExist($id, $allowedDepartments)){
            session()->flash('error', trans('admin.manager_delete_or_update_error'));
            return redirect('mall-manager/departments');
        }

        $data = $this->validate(request(), [
            'name_ar' => 'required',
            'name_en' => 'required',
            'parent' => 'sometimes|nullable|numeric',
            'icon' => 'sometimes|nullable|image|mimes:svg',
            'photo' => 'sometimes|nullable|image|mimes:svg,jpg,jpeg,png,gif',
            'keywords' => 'sometimes|nullable|string',
            'description' => 'sometimes|nullable|string',
        ]);

        $dep = Department::find($id);
        $data['is_active'] = 'inactive';
        $data['owner'] = auth()->guard('web')->user()->id;

        if(request()->has('icon')){
            $data['icon'] = Up::upload([
                'file' => 'icon',
                'uploadType' => 'single',
                'path' => 'departments',
                'deleteFile' => $dep->icon,
            ]);
        }

        if(request()->has('photo')){
            $data['photo'] = Up::upload([
                'file' => 'photo',
                'uploadType' => 'single',
                'path' => 'departments',
                'deleteFile' => $dep->photo,
            ]);
        }

        $dep->update($data);

        session()->flash('success', trans('admin.record_updated_successfully'));

        return redirect('mall-manager/departments');
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
            Storage::has($subDepartment->photo) ? Storage::delete($subDepartment->photo) : '';
            self::deleteSubDepartments($subDepartment->id);
            $subDepartment->delete();
        }
    }

    public function destroy($id)
    {
        $allowedDepartments = $this->getAllowedDepartments();
        if(!$this->checkDepartmentExist($id, $allowedDepartments)){
            session()->flash('error', trans('admin.manager_delete_or_update_error'));
            return redirect('mall-manager/departments');
        }

        $department = Department::find($id);
        self::deleteSubDepartments($id);
        Storage::has($department->icon) ? Storage::delete($department->icon) : '';
        Storage::has($department->photo) ? Storage::delete($department->photo) : '';
        $department->delete();
        session()->flash('success', trans('admin.record_deleted_successfully'));
        return back();
    }

    private function getAllowedDepartments(){
        $allowedDepartments = Department::selectRaw('id as id')
                                         ->where([['owner', auth()->guard('web')->user()->id], 'is_active' => 'inactive'])
                                         ->get('id');
        return $allowedDepartments;
    }

    private function checkDepartmentExist($id, $allowedDepartments){
        foreach ($allowedDepartments as $key => $department) {
            if($department->id == $id){
                return true;
            }
        }
        return false;
    }
}