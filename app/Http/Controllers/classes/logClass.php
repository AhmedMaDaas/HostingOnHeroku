<?php

namespace App\Http\Controllers\Classes;
use App\Product;
use App\File;
use App\Department;
use App\Ad;
use App\User;
use App\Bill;
use App\BillProduct;

class logClass{
	

	function __construct()
	{
		
	}

	function checkIfRegister($email,$password,$remember_me){
		$user = User::where(['email'=>$email])->first();
		 //\Auth::login($user, $remember_me);
		//\Auth::attempt(['email' => $email, 'password' => $password], $remember_me);
		//dd( \Auth::viaRemember());
		
		if(empty($user->id)||!\Hash::check($password,$user->password))return false;
		
		if ($remember_me) {
	        $cookie =  \Cookie::queue('remembered', $user->id, time() + 31536000);
	    }else {
	        //$cookie =  Cookie::queue('username', '', time() - 100);
	    }
	        //dd($_COOKIE);
	        //dd( !\Cookie::get('remembered'));
		session(['login'=>$user->id]);
		return true;
	}


	function register($email,$password,$confirmPassword,$firstName,$lastName,$phone){
		// $user = User::where(['email'=>$email])->first();
		// if(empty($user->id)){
			// if($password == $confirmPassword){
				$user = User::create(['name'=>$firstName.' '.$lastName,'email'=>$email,'password'=>$password,'phone'=>$phone,'level'=>'user']);
				session(['login'=>$user->id]);
				return true;
			// }
			
			
		//}
	}

	function checkLoginByService($service,$service_id,$name,$email,$photo,$remember_me){
		if($service == 'facebook'){

			$coloum = 'facebook_id';

		}elseif($service == 'google'){

			$coloum = 'google_id';
		}
		
		$user = User::where($coloum,$service_id)->first();
			if(empty($user)){
				$foundUser = User::where('email',$email)->first();
				if(!empty($foundUser))return false;
				$user = User::create(['name'=>$name,'email'=>$email,$coloum=>$service_id,'photo'=>$photo,'level'=>'user']);
			}else{
				User::where('id',$user->id)->update(['name'=>$name,'email'=>$email,$coloum=>$service_id,'photo'=>$photo,'level'=>'user']);
			}

			if ($remember_me) {
		        $cookie =  \Cookie::queue('remembered', $user->id, time() + 31536000);
		    }else {
		        //$cookie =  Cookie::queue('username', '', time() - 100);
		    }

			session(['login'=>$user->id]);
			return true;
	}

}

?>
