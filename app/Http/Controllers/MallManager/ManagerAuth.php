<?php

namespace App\Http\Controllers\MallManager;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Mail\ManagerRessetPassword;
use App\User;
use DB;
use Carbon\Carbon;
use Password;
use Mail;

class ManagerAuth extends Controller
{
    //

    public function login(){
    	return view('mall_manager.login');
    }

    public function doLogin(){
    	$remember = request('remember') !== null ? true : false;
    	if(auth()->guard('web')->attempt(['email'=>request('email'), 'password'=>request('password'), 'level' => 'mall'], $remember)){
            return redirect('mall-manager/home');
    	}
    	else{
    		session()->flash('error', trans('admin.error_information_login'));
    		return redirect('mall-manager/login');
    	}
    }

    public function logout(){
    	auth()->guard('web')->logout();
    	return redirect('mall-manager/login');
    }

    public function ressetPassword(){
    	return view('mall_manager.resset_password');
    }

    public function ressetPasswordPost(){
    	$manager = User::where([['email', '=', request('email')], ['level', '=', 'mall']])->first();
    	
    	if(!empty($manager)){

            $token = Password::broker('users')->createToken($manager);

    		$data = DB::table('password_resets')->insert([
    			'email' => $manager->email,
    			'token' => $token,
    			'created_at' => Carbon::now(),
    		]);

    		Mail::to($manager->email)->send(new ManagerRessetPassword(['manager'=>$manager, 'token'=>$token]));
            session()->flash('success', trans('admin.resset_link_sent_successfully'));
    	}

    	return back();
    }

    public function ressetPasswordToken($token){
        $checkToken = DB::table('password_resets')->where('token', $token)->where('created_at', '>', Carbon::now()->subHour(2))->first();
        if(!empty($checkToken)){
            return view('mall_manager.resset_password_token', ['email'=> $checkToken->email]);
        }
        else{
            return redirect('mall-manager/resset/password');
        }
    }

    public function ressetPasswordTokenPost($token){
        $checkToken = DB::table('password_resets')->where('token', $token)->where('created_at', '>', Carbon::now()->subHour(2))->first();
        if(!empty($checkToken)){
            User::where('email', $checkToken->email)->update(['email'=>$checkToken->email, 'password'=> bcrypt(request('password'))]);
            DB::table('password_resets')->where('token', $token)->where('created_at', '>', Carbon::now()->subHour(2))->delete();
            auth()->guard('web')->attempt(['email'=>$checkToken->email, 'password'=>request('password'), 'level' => 'mall'], true);
            return redirect('mall-manager/home');
        }
        else{
            return redirect('mall-manager/resset/password');
        }
    }
}
