<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Setting;
use Up;
use App\Http\Controllers\Notifications;

class settings extends Controller
{
    public function settings(){
        $settings = settings();
        $notifications = new Notifications();
        return view('admin.settings.show', ['notifications' => $notifications, 'settings' => $settings]);
    }

    public function save(){
        $data = $this->validate(request(), [
            'logo' => 'image|mimes:jpg,png,gif,jpeg,bmp',
            'icon' => 'image|mimes:jpg,png,gif,jpeg,bmp',
            'sitename_ar' => '',
            'sitename_en' => '',
            'email' => 'required|email',
            'description' => '',
            'keywords' => '',
            'main_lang' => '',
            'status' => '',
            'message_maintenance' => '',
        ]);

        if(request()->hasFile('logo')){
            $data['logo'] = Up::upload([
                'file' => 'logo',
                'path' => 'settings',
                'uploadType' => 'single',
                'deleteFile' => settings()->logo
            ]);
        }

        if(request()->hasFile('icon')){
            $data['icon'] = Up::upload([
                'file' => 'icon',
                'path' => 'settings',
                'uploadType' => 'single',
                'deleteFile' => settings()->icon
            ]);
        }

        Setting::orderBy('id', 'desc')->update($data);
        session()->flash('success', trans('admin.record_updated_successfully'));
        return back();
    }
}
