<?php

namespace App\Http\Controllers\MallManager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\WeightsDatatable;
use App\Weight;
use Up;
use Storage;
use App\Http\Controllers\Notifications;

class weightsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(WeightsDatatable $weights)
    {
        $notifications = new Notifications();
        return $weights->render('mall_manager.weights.index', ['title' => trans('admin.weights_control'), 'notifications' => $notifications]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $notifications = new Notifications();
        return view('mall_manager.weights.create', ['notifications' => $notifications]);
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
        ]);

        $data['owner'] = auth()->guard('web')->user()->id;

        Weight::create($data);

        session()->flash('success', trans('admin.record_created_successfully'));

        return redirect('mall-manager/weights');
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
        $allowedWeights = $this->getAllowedWeights();
        if(!$this->checkWeightExist($id, $allowedWeights)){
            session()->flash('error', trans('admin.manager_delete_or_update_error'));
            return redirect('mall-manager/weights');
        }

        $notifications = new Notifications();

        $weight = Weight::find($id);
        return view('mall_manager.weights.edit', ['notifications' => $notifications, 'weight' => $weight]);
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
        $allowedWeights = $this->getAllowedWeights();
        if(!$this->checkWeightExist($id, $allowedWeights)){
            session()->flash('error', trans('admin.manager_delete_or_update_error'));
            return redirect('mall-manager/weights');
        }

        $data = $this->validate(request(), [
            'name_ar' => 'required',
            'name_en' => 'required',
        ]);

        $data['owner'] = auth()->guard('web')->user()->id;

        Weight::where('id', $id)->update($data);

        session()->flash('success', trans('admin.record_updated_successfully'));

        return redirect('mall-manager/weights');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $allowedWeights = $this->getAllowedWeights();
        if(!$this->checkWeightExist($id, $allowedWeights)){
            session()->flash('error', trans('admin.manager_delete_or_update_error'));
            return redirect('mall-manager/weights');
        }

        $weight = Weight::find($id);
        $weight->delete();
        session()->flash('success', trans('admin.record_deleted_successfully'));
        return back();
    }

    public function multiDelete(){
        $allowedWeights = $this->getAllowedWeights();
        foreach(request('delete') as $id){
            if(!$this->checkWeightExist($id, $allowedWeights)){
                session()->flash('error', trans('admin.manager_delete_or_update_error'));
                return redirect('mall-manager/weights');
            }

            $weight = Weight::find($id);
            $weight->delete();
        }
        session()->flash('success', trans('admin.records_deleted_successfully'));
        return back();
    }

    private function getAllowedWeights(){
        $allowedWeights = Weight::selectRaw('id as id')
                                         ->where([['owner', auth()->guard('web')->user()->id]])
                                         ->get('id');
        return $allowedWeights;
    }

    private function checkWeightExist($id, $allowedWeights){
        foreach ($allowedWeights as $key => $weight) {
            if($weight->id == $id){
                return true;
            }
        }
        return false;
    }
}
