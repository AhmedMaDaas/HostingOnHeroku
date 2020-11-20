<?php

namespace App\Http\Controllers\Admin;

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
        return $colors->render('admin.colors.index', ['title' => trans('admin.colors_control'), 'notifications' => $notifications]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $notifications = new Notifications();
        return view('admin.colors.create', ['notifications' => $notifications]);
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

        $data['owner'] = 'admin';

        Color::create($data);

        session()->flash('success', 'Color created successfully');

        return redirect('admin/colors');
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
        $color = Color::find($id);
        $notifications = new Notifications();
        return view('admin.colors.edit', ['color' => $color, 'notifications' => $notifications]);
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
            'color' => 'required',
        ]);

        $color = Color::find($id);
        $colorOwner = $color->owner;
        $data['owner'] = $colorOwner;

        Color::where('id', $id)->update($data);

        session()->flash('success', 'Color updated successfully');

        return redirect('admin/colors');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $color = Color::find($id);
        $color->delete();
        return back();
    }

    public function multiDelete(){
        if(is_array(request('delete'))){
            foreach(request('delete') as $id){
                $color = Color::find($id);
                $color->delete();
            }
        }
        else{
            $color = Color::find(request('delete'));
            $color->delete();
        }
        return back();
    }
}
