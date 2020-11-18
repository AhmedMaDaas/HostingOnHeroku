<?php

namespace App\Http\Controllers\Shipping;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Mail\ShippingRessetPassword;
use App\User;
use DB;
use Carbon\Carbon;
use Password;
use Mail;

class ShippingAuth extends Controller
{
    //

    public function login(){
    	return view('shipping.login');
    }

    public function doLogin(){
    	$remember = request('remember') == 1 ? true : false;
    	if(auth()->guard('web')->attempt(['email'=>request('email'), 'password'=>request('password'), 'level' => 'company'], $remember)){
            return redirect('shipping/home');
    	}
    	else{
    		session()->flash('error', trans('admin.error_information_login'));
    		return redirect('shipping/login');
    	}
    }

    public function logout(){
    	auth()->guard('web')->logout();
    	return redirect('shipping/login');
    }

    public function ressetPassword(){
    	return view('shipping.resset_password');
    }

    public function ressetPasswordPost(){
    	$shipping = User::where([['email', '=', request('email')], ['level', '=', 'company']])->first();
    	
    	if(!empty($shipping)){

            $token = Password::broker('users')->createToken($shipping);

    		$data = DB::table('password_resets')->insert([
    			'email' => $shipping->email,
    			'token' => $token,
    			'created_at' => Carbon::now(),
    		]);

    		Mail::to($shipping->email)->send(new ShippingRessetPassword(['shipping'=>$shipping, 'token'=>$token]));
            session()->flash('success', trans('admin.resset_link_sent_successfully'));
    	}

    	return back();
    }

    public function ressetPasswordToken($token){
        $checkToken = DB::table('password_resets')->where('token', $token)->where('created_at', '>', Carbon::now()->subHour(2))->first();
        if(!empty($checkToken)){
            return view('shipping.resset_password_token', ['email'=> $checkToken->email]);
        }
        else{
            return redirect('shipping/resset/password');
        }
    }

    public function ressetPasswordTokenPost($token){
        $checkToken = DB::table('password_resets')->where('token', $token)->where('created_at', '>', Carbon::now()->subHour(2))->first();
        if(!empty($checkToken)){
            User::where('email', $checkToken->email)->update(['email'=>$checkToken->email, 'password'=> bcrypt(request('password'))]);
            DB::table('password_resets')->where('token', $token)->where('created_at', '>', Carbon::now()->subHour(2))->delete();
            auth()->guard('web')->attempt(['email'=>$checkToken->email, 'password'=>request('password'), 'level' => 'company'], true);
            return redirect('shipping/home');
        }
        else{
            return redirect('shipping/resset/password');
        }
    }
}
