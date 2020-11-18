<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\AdminDatatable;
use App\Admin;
use App\Http\Controllers\Notifications;

class adminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(AdminDatatable $admin)
    {
        $notifications = new Notifications();
        return $admin->render('admin.admins.index', ['title' => trans('admin.admins_control'), 'notifications' => $notifications]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $notifications = new Notifications();
        return view('admin.admins.create', ['notifications' => $notifications]);
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
            'name' => 'required',
            'email' => 'required|email|unique:admins',
            'password' => 'required',
        ]);

        $data['password'] = bcrypt(request('password'));
        Admin::create($data);

        session()->flash('success', trans('admin.record_created_successfully'));

        return redirect('admin/admins');
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
        $admin = Admin::find($id);
        $notifications = new Notifications();
        return view('admin.admins.edit', ['admin' => $admin, 'notifications' => $notifications]);
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
            'name' => 'required',
            'email' => 'required|email|unique:admins,email,' . $id,
            'password' => 'sometimes|nullable',
        ]);

        if(request()->has('password')){
            $data['password'] = bcrypt(request('password'));
        }
        else{
            $data['password'] = request('oldPassword');
        }

        Admin::where('id', $id)->update($data);

        session()->flash('success', trans('admin.record_updated_successfully'));

        return redirect('admin/admins');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Admin::find($id)->delete();
        session()->flash('success', trans('admin.record_deleted_successfully'));
        return back();
    }

    public function multiDelete(){
        if(is_array(request('delete'))){
            Admin::destroy(request('delete'));
        }
        else{
            Admin::find(request('delete'))->delete();
        }
        session()->flash('success', trans('admin.records_deleted_successfully'));
        return back();
    }
}
