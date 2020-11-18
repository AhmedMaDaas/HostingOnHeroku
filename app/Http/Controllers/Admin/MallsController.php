<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\MallsDatatable;
use App\DataTables\MallsSalesDatatable;
use App\DataTables\MallSalesDatatable;
use App\Mall;
use Up;
use Storage;
use App\Rules\PhoneNumber;
use App\Http\Controllers\Notifications;


class mallsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MallsDatatable $malls)
    {
        $notifications = new Notifications();
        return $malls->render('admin.malls.index', ['title' => trans('admin.malls_control'), 'notifications' => $notifications]);
    }

    public function mallsSales(MallsSalesDatatable $sales){
        $notifications = new Notifications();
        return $sales->render('admin.malls.sales', ['title' => trans('admin.slaes'), 'notifications' => $notifications]);
    }

    public function mallSales($id, MallSalesDatatable $mallSales){
        $notifications = new Notifications();
        return $mallSales->with('id', $id)->render('admin.malls.sales', ['title' => trans('admin.mall_sales'), 'notifications' => $notifications]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $notifications = new Notifications();
        return view('admin.malls.create', ['notifications' => $notifications]);
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
            'mobile' => ['required', new PhoneNumber()],
            'email' => 'required|email',
            'address' => 'sometimes|nullable',
            'user_id' => 'required',
            'facebook' => 'sometimes|nullable|url',
            'twitter' => 'sometimes|nullable|url',
            'website' => 'sometimes|nullable|url',
            'contact_name' => 'sometimes|nullable|string',
            'lat' => 'sometimes|nullable',
            'lng' => 'sometimes|nullable',
            'icon' => 'sometimes|nullable|image',
        ]);

        if(request()->has('icon')){
            $data['icon'] = Up::upload([
                'file' => 'icon',
                'uploadType' => 'single',
                'path' => 'malls',
                'deleteFile' => ''
            ]);
        }

        Mall::create($data);

        session()->flash('success', trans('admin.record_created_successfully'));

        return redirect('admin/malls');
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
        $mall = Mall::find($id);
        $notifications = new Notifications();
        return view('admin.malls.edit', ['mall' => $mall, 'notifications' => $notifications]);
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
            'mobile' => ['required', new PhoneNumber()],
            'email' => 'required|email',
            'address' => 'sometimes|nullable',
            'user_id' => 'required',
            'facebook' => 'sometimes|nullable|url',
            'twitter' => 'sometimes|nullable|url',
            'website' => 'sometimes|nullable|url',
            'contact_name' => 'sometimes|nullable|string',
            'lat' => 'sometimes|nullable',
            'lng' => 'sometimes|nullable',
            'icon' => 'sometimes|nullable|image',
        ]);

        if(request()->has('icon')){
            $data['icon'] = Up::upload([
                'file' => 'icon',
                'uploadType' => 'single',
                'path' => 'malls',
                'deleteFile' => Mall::find($id)->icon
            ]);
        }

        Mall::where('id', $id)->update($data);

        session()->flash('success', trans('admin.record_updated_successfully'));

        return redirect('admin/malls');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $mall = Mall::find($id);
        Storage::delete($mall->icon);
        $mall->delete();
        session()->flash('success', trans('admin.record_deleted_successfully'));
        return back();
    }

    public function multiDelete(){
        foreach(request('delete') as $id){
            $mall = Mall::find($id);
            Storage::delete($mall->icon);
            $mall->delete();
        }
        session()->flash('success', trans('admin.records_deleted_successfully'));
        return back();
    }
}
