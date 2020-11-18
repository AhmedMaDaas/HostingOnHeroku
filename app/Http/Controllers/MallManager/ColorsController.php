<?php

namespace App\Http\Controllers\MallManager;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\ColorsDatatable;
use App\Color;
use Up;
use Storage;
use App\Http\Controllers\Notifications;

class colorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ColorsDatatable $colors)
    {
        $notifications = new Notifications();
        return $colors->render('mall_manager.colors.index', ['title' => trans('admin.colors_control'), 'notifications' => $notifications]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $notifications = new Notifications();
        return view('mall_manager.colors.create', ['notifications' => $notifications]);
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
            'color' => 'required',
        ]);

        $data['owner'] = auth()->guard('web')->user()->id;

        Color::create($data);

        session()->flash('success', trans('admin.record_created_successfully'));

        return redirect('mall-manager/colors');
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
        $allowedColors = $this->getAllowedColors();
        if(!$this->checkColorExist($id, $allowedColors)){
            session()->flash('error', trans('admin.manager_delete_or_update_error'));
            return redirect('mall-manager/colors');
        }

        $color = Color::find($id);
        $notifications = new Notifications();
        return view('mall_manager.colors.edit', ['notifications' => $notifications, 'color' => $color]);
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
        $allowedColors = $this->getAllowedColors();
        if(!$this->checkColorExist($id, $allowedColors)){
            session()->flash('error', trans('admin.manager_delete_or_update_error'));
            return redirect('mall-manager/colors');
        }

        $data = $this->validate(request(), [
            'name_ar' => 'required',
            'name_en' => 'required',
            'color' => 'required',
        ]);

        $data['owner'] = auth()->guard('web')->user()->id;

        Color::where('id', $id)->update($data);

        session()->flash('success', trans('admin.record_updated_successfully'));

        return redirect('mall-manager/colors');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $allowedColors = $this->getAllowedColors();
        if(!$this->checkColorExist($id, $allowedColors)){
            session()->flash('error', trans('admin.manager_delete_or_update_error'));
            return redirect('mall-manager/colors');
        }

        $color = Color::find($id);
        $color->delete();
        session()->flash('success', trans('admin.record_deleted_successfully'));
        return back();
    }

    public function multiDelete(){
        $allowedColors = $this->getAllowedColors();
        foreach(request('delete') as $id){
            if(!$this->checkColorExist($id, $allowedColors)){
                session()->flash('error', trans('admin.manager_delete_or_update_error'));
                return redirect('mall-manager/colors');
            }

            $color = Color::find($id);
            $color->delete();
        }
        session()->flash('success', trans('admin.records_deleted_successfully'));
        return back();
    }

    private function getAllowedColors(){
        $allowedColors = Color::selectRaw('id as id')
                                         ->where([['owner', auth()->guard('web')->user()->id]])
                                         ->get('id');
        return $allowedColors;
    }

    private function checkColorExist($id, $allowedColors){
        foreach ($allowedColors as $key => $color) {
            if($color->id == $id){
                return true;
            }
        }
        return false;
    }
}
