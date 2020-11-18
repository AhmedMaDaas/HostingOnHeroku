<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\StatesDatatable;
use App\State;
use Up;
use Storage;
use App\City;
use Form;
use App\Http\Controllers\Notifications;

class statesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(StatesDatatable $states)
    {
        $notifications = new Notifications();
        return $states->render('admin.states.index', ['title' => trans('admin.states_control'), 'notifications' => $notifications]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(request()->ajax()){
            if(request()->has('country_id')){
                $select = request()->has('select') ? request('select') : '';
                return Form::select('city_id', City::where('country_id', request('country_id'))->pluck('name_' . lang(), 'id'), $select, ['class' => 'form-control']);
            }
        }
        $notifications = new Notifications();
        return view('admin.states.create', ['notifications' => $notifications]);
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
            'country_id' => 'required|numeric',
            'city_id' => 'required|numeric',
        ]);

        State::create($data);

        session()->flash('success', trans('admin.record_created_successfully'));

        return redirect('admin/states');
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
        $state = State::find($id);
        $notifications = new Notifications();
        return view('admin.states.edit', ['notifications' => $notifications, 'state' => $state]);
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
            'country_id' => 'required|numeric',
            'city_id' => 'required|numeric',
        ]);

        State::where('id', $id)->update($data);

        session()->flash('success', trans('admin.record_updated_successfully'));

        return redirect('admin/states');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $state = State::find($id);
        $state->delete();
        session()->flash('success', trans('admin.record_deleted_successfully'));
        return back();
    }

    public function multiDelete(){
        foreach(request('delete') as $id){
            $state = State::find($id);
            $state->delete();
        }
        session()->flash('success', trans('admin.records_deleted_successfully'));
        return back();
    }
}
