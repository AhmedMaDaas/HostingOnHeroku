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

	function checkIfRegister($email,$password){
		$user = User::where(['email'=>$email])->first();
		if(empty($user->id))return false;
		\Hash::check($password,$user->password);
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

	function checkLoginByService($service,$service_id,$name,$email,$photo){
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
			}
			session(['login'=>$user->id]);
			return true;
	}

}

?>
