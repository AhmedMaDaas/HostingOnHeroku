<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\UsersDatatable;
use App\User;
use Storage;
use Up;
use App\Rules\PhoneNumber;
use App\Http\Controllers\Notifications;

class usersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UsersDatatable $users)
    {
        $notifications = new Notifications();
        return $users->render('admin.users.index', ['title' => trans('admin.users_control'), 'notifications' => $notifications]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $notifications = new Notifications();
        return view('admin.users.create', ['notifications' => $notifications]);
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
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'level' => 'required|in:user,mall,company',
            'phone' => ['required', new PhoneNumber()],
            'photo' => 'sometimes|nullable|image|mimes:png,jpg,jpeg,gif',
        ]);

        if(request()->hasFile('photo')){
            $data['photo'] = Up::upload([
                'file' => 'photo',
                'uploadType' => 'single',
                'path' => 'users',
                'deleteFile' => ''
            ]);
        }

        $data['password'] = bcrypt(request('password'));
        User::create($data);

        session()->flash('success', trans('admin.record_created_successfully'));

        return redirect('admin/users');
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
        $user = User::find($id);
        $notifications = new Notifications();
        return view('admin.users.edit', ['user' => $user, 'notifications' => $notifications]);
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
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'sometimes|nullable',
            'level' => 'required|in:user,mall,company',
            'phone' => ['required', new PhoneNumber()],
            'photo' => 'sometimes|nullable|image|mimes:png,jpg,jpeg,gif',
        ]);

        $user = User::find($id);
        $data['photo'] = $user->photo;

        if(request()->hasFile('photo')){
            $data['photo'] = Up::upload([
                'file' => 'photo',
                'uploadType' => 'single',
                'path' => 'users',
                'deleteFile' => $user->photo
            ]);
        }

        if(request()->has('password')){
            $data['password'] = bcrypt(request('password'));
        }
        else{
            $data['password'] = request('oldPassword');
        }
        
        User::where('id', $id)->update($data);

        session()->flash('success', trans('admin.record_updated_successfully'));

        return redirect('admin/users');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        Storage::has($user->photo) ? Storage::delete($user->photo) : '';
        $user->delete();
        session()->flash('success', trans('admin.record_deleted_successfully'));
        return back();
    }

    public function multiDelete(){
        foreach (request('delete') as $id) {
            $user = User::find($id);
            Storage::has($user->photo) ? Storage::delete($user->photo) : '';
            $user->delete();
        }
        session()->flash('success', trans('admin.records_deleted_successfully'));
        return back();
    }
}
