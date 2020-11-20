<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\CountriesDatatable;
use App\Country;
use Up;
use Storage;
use App\Http\Controllers\Notifications;

class countriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CountriesDatatable $Countries)
    {
        $notifications = new Notifications();
        return $Countries->render('admin.countries.index', ['title' => trans('admin.countries_control'), 'notifications' => $notifications]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $notifications = new Notifications();
        return view('admin.countries.create', ['notifications' => $notifications]);
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
            'logo' => 'required|image',
            'code' => 'required',
            'mob' => 'required',
            'currency' => 'required',
        ]);

        if(request()->has('logo')){
            $data['logo'] = Up::upload([
                'file' => 'logo',
                'uploadType' => 'single',
                'path' => 'countries',
                'deleteFile' => ''
            ]);
        }

        Country::create($data);

        session()->flash('success', trans('admin.record_created_successfully'));

        return redirect('admin/countries');
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
        $country = Country::find($id);
        $notifications = new Notifications();
        return view('admin.countries.edit', ['country' => $country, 'notifications' => $notifications]);
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
            'logo' => 'required|image',
            'code' => 'required',
            'mob' => 'required',
            'currency' => 'required',
        ]);

        if(request()->has('logo')){
            $data['logo'] = Up::upload([
                'file' => 'logo',
                'uploadType' => 'single',
                'path' => 'countries',
                'deleteFile' => Country::find($id)->logo
            ]);
        }

        Country::where('id', $id)->update($data);

        session()->flash('success', trans('admin.record_updated_successfully'));

        return redirect('admin/countries');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $country = Country::find($id);
        Storage::delete($country->logo);
        $country->delete();
        session()->flash('success', trans('admin.record_deleted_successfully'));
        return back();
    }

    public function multiDelete(){
        foreach(request('delete') as $id){
            $country = Country::find($id);
            Storage::delete($country->logo);
            $country->delete();
        }
        session()->flash('success', trans('admin.records_deleted_successfully'));
        return back();
    }
}