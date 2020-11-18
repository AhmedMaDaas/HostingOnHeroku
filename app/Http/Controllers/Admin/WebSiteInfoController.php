<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Notifications;

use Illuminate\Http\Request;
use App\WebSiteInfo;
use App\AttractiveInformation;
use Up;
use Storage;

class WebSiteInfoController extends Controller
{
    public function edit(){
        $title = trans('admin.website_info');
        $info = getWebsiteInfo();
        $notifications = new Notifications();
        return view('admin.website_info.show', ['title' => $title, 'info' => $info, 'notifications' => $notifications]);
    }

    public function save($id){
        $info = WebSiteInfo::find($id);
        $mainPhotoRequired = isset($info->main_photo) ? 'sometimes|nullable' : 'required';
        $descPhotoRequired = isset($info->desc_photo) ? 'sometimes|nullable' : 'required';

        $data = $this->validate(request(), [
            'main_photo' => $mainPhotoRequired . '|image|mimes:jpg,png,gif,jpeg',
            'photo_title' => 'required|string',
            'photo_desc' => 'required|string',
            'desc_photo' => $descPhotoRequired . '|image|mimes:jpg,png,gif,jpeg,svg',
            'web_desc' => 'required',
            'email' => 'sometimes|nullable|url',
            'facebook' => 'sometimes|nullable|url',
            'twitter' => 'sometimes|nullable|url',
            'instagram' => 'sometimes|nullable|url',
        ]);

        if(request()->hasFile('main_photo')) {
            $data['main_photo'] = Up::upload([
                'file' => 'main_photo',
                'uploadType' => 'single',
                'path' => 'web_info',
                'deleteFile' => $info->main_photo,
            ]);
        }

        if(request()->hasFile('desc_photo')) { 
            $data['desc_photo'] = Up::upload([
                'file' => 'desc_photo',
                'uploadType' => 'single',
                'path' => 'web_info',
                'deleteFile' => $info->desc_photo,
            ]);
        }

        $info->update($data);
        session()->flash('success', trans('admin.record_updated_successfully'));
        return redirect('admin/website-info');
    }

    public function addAttrInfo(){
        $this->validate(request(), [
            'info_id' => 'required|numeric',
            'photo' => 'required|image|mimes:jpeg,jpg,png,gif,svg',
            'title' => 'required|string',
        ]);

        $info = WebSiteInfo::find(request('info_id'));

        if(isset($info))
            $this->createAttrInfo(request());
        return view('admin.website_info.attr_info_ajax', ['info' => $info])->render();
    }

    public function deleteAttrInfo(){
       $this->validate(request(), [
            'attr_id' => 'required|numeric',
        ]);

       $attr = AttractiveInformation::find(request('attr_id'));
       Storage::has($attr->photo) ? Storage::delete($attr->photo) : '';
       $attr->delete();
       return trans('admin.deleted');
    }

    private function createAttrInfo($request){
        $photo = Up::upload([
            'file' => 'photo',
            'uploadType' => 'single',
            'path' => 'web_info',
            'deleteFile' => ''
        ]);
        AttractiveInformation::create([
            'web_site_info_id' => $request->info_id,
            'photo' => $photo,
            'title' => $request->title,
        ]);
    }
}
