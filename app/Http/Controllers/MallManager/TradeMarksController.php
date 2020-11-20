<?php

namespace App\Http\Controllers\MallManager;

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
        return $tradeMarks->render('mall_manager.tradeMarks.index', ['title' => trans('admin.trade_mark_control'), 'notifications' => $notifications]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $notifications = new Notifications();
        return view('mall_manager.tradeMarks.create', ['notifications' => $notifications]);
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

        $data['owner'] = auth()->guard('web')->user()->id;

        TradeMark::create($data);

        session()->flash('success', trans('admin.record_created_successfully'));

        return redirect('mall-manager/tradeMarks');
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
        $allowedTradeMarks = $this->getAllowedTradeMarks();
        if(!$this->checkTradeMarkExist($id, $allowedTradeMarks)){
            session()->flash('error', trans('admin.manager_delete_or_update_error'));
            return redirect('mall-manager/tradeMarks');
        }

        $tradeMark = TradeMark::find($id);
        $notifications = new Notifications();
        return view('mall_manager.tradeMarks.edit', ['notifications' => $notifications, 'tradeMark' => $tradeMark]);
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
        $allowedTradeMarks = $this->getAllowedTradeMarks();
        if(!$this->checkTradeMarkExist($id, $allowedTradeMarks)){
            session()->flash('error', trans('admin.manager_delete_or_update_error'));
            return redirect('mall-manager/tradeMarks');
        }

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

        $data['owner'] = auth()->guard('web')->user()->id;

        TradeMark::where('id', $id)->update($data);

        session()->flash('success', trans('admin.record_updated_successfully'));

        return redirect('mall-manager/tradeMarks');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $allowedTradeMarks = $this->getAllowedTradeMarks();
        if(!$this->checkTradeMarkExist($id, $allowedTradeMarks)){
            session()->flash('error', trans('admin.manager_delete_or_update_error'));
            return redirect('mall-manager/tradeMarks');
        }

        $tradeMark = TradeMark::find($id);
        Storage::delete($tradeMark->logo);
        $tradeMark->delete();
        session()->flash('success', trans('admin.record_deleted_successfully'));
        return back();
    }

    public function multiDelete(){
        $allowedTradeMarks = $this->getAllowedTradeMarks();
        foreach(request('delete') as $id){
            if(!$this->checkTradeMarkExist($id, $allowedTradeMarks)){
                session()->flash('error', trans('admin.manager_delete_or_update_error'));
                return redirect('mall-manager/tradeMarks');
            }

            $tradeMark = TradeMark::find($id);
            Storage::delete($tradeMark->logo);
            $tradeMark->delete();
        }
        session()->flash('success', trans('admin.records_deleted_successfully'));
        return back();
    }

    private function getAllowedTradeMarks(){
        $allowedTradeMarks = TradeMark::selectRaw('id as id')
                                         ->where([['owner', auth()->guard('web')->user()->id]])
                                         ->get('id');
        return $allowedTradeMarks;
    }

    private function checkTradeMarkExist($id, $allowedTradeMarks){
        foreach ($allowedTradeMarks as $key => $tradeMark) {
            if($tradeMark->id == $id){
                return true;
            }
        }
        return false;
    }
}
