<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Notification;

class NotificationsController extends Controller
{
    public function getNew(){
        $this->validate(request(), [
            'id' => 'required|numeric',
            'not_id' => 'required|numeric',
        ]);

        $newNotifications = Notification::orderBy('id', 'desc')->where([['owner_id', request('id')], ['relation', 'admin'], ['id', '>', request('not_id')], ['new', 1]])->limit(3)->get();

        if(request()->ajax()){
            $id = count($newNotifications) > 0 ? $newNotifications->last()->id : 0;
            $count = count($newNotifications);
            return response()->json(['count' => $count, 'id' => $id, 'html' => view('admin.notifications.ajax.new_notifications', ['notifications' => $newNotifications])->render()]);
        }

        return back();
    }

    public function makeOld(){
        $this->validate(request(), [
            'id' => 'required|numeric',
        ]);

        Notification::where([['owner_id', request('id')], ['relation', 'admin'], ['new', 1]])
                    ->update(['new' => 0]);

        if(request()->ajax()){
            return response(['data' => 'olded']);
        }

        return back();
    }
}
