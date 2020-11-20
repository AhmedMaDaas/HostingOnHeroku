<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\TradeMarksDatatable;
use App\TradeMark;
use Up;
use Storage;
use App\Http\Controllers\Notifications;

class tradeMarksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TradeMarksDatatable $tradeMarks)
    {
        $notifications = new Notifications();
        return $tradeMarks->render('admin.tradeMarks.index', ['title' => trans('admin.trade_mark_control'), 'notifications' => $notifications]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $notifications = new Notifications();
        return view('admin.tradeMarks.create', ['notifications' => $notifications]);
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
            'logo' => 'image',
        ]);

        if(request()->has('logo')){
            $data['logo'] = Up::upload([
                'file' => 'logo',
                'uploadType' => 'single',
                'path' => 'tradeMarks',
                'deleteFile' => ''
            ]);
        }

        $data['owner'] = 'admin';

        TradeMark::create($data);

        session()->flash('success', trans('admin.record_created_successfully'));

        return redirect('admin/tradeMarks');
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
        $tradeMark = TradeMark::find($id);
        $notifications = new Notifications();
        return view('admin.tradeMarks.edit', ['tradeMark' => $tradeMark, 'notifications' => $notifications]);
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
            'logo' => 'image',
        ]);

        if(request()->has('logo')){
            $data['logo'] = Up::upload([
                'file' => 'logo',
                'uploadType' => 'single',
                'path' => 'tradeMarks',
                'deleteFile' => TradeMark::find($id)->logo
            ]);
        }

        $tradeMark = TradeMark::find($id);
        $tradeMarkOwner = $tradeMark->owner;
        $data['owner'] = $tradeMarkOwner;

        TradeMark::where('id', $id)->update($data);

        session()->flash('success', trans('admin.record_updated_successfully'));

        return redirect('admin/tradeMarks');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tradeMark = TradeMark::find($id);
        Storage::delete($tradeMark->logo);
        $tradeMark->delete();
        session()->flash('success', trans('admin.record_deleted_successfully'));
        return back();
    }

    public function multiDelete(){
        foreach(request('delete') as $id){
            $tradeMark = TradeMark::find($id);
            Storage::delete($tradeMark->logo);
            $tradeMark->delete();
        }
        session()->flash('success', trans('admin.records_deleted_successfully'));
        return back();
    }
}
