<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use App\Notification;

class Notifications
{
	private $notifications;
	private $countAll = 0;
	private $countNew = 0;
	private $limit = 3;

	public function __construct(){
		$relation = $this->relation();
		$guard = isManager() || isCompany() ? 'web' : 'admin';
		$this->notifications = Notification::orderBy('id', 'desc')->where([['relation', $relation], ['owner_id', auth()->guard($guard)->user()->id]])->limit($this->limit)->get();
		$this->countAll = count($this->notifications);
		$this->setCountNew($this->notifications);
	}

	private function relation(){
		$relation = 'admin';
		if(isManager()) $relation = 'manager';
		if(isCompany()) $relation = 'company';
		return $relation;
	}

	private function setCountNew($notifications = []){
		foreach ($notifications as $key => $notification) {
			if($notification->new == 1){
				$this->countNew++;
			}
		}
	}

	public function getNotifications(){
		return $this->notifications;
	}

	public function getCountAll(){
		return $this->countAll;
	}

	public function getCountNew(){
		return $this->countNew;
	}
}
