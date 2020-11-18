<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Admin;
use App\Mail\AdminRessetPassword;
use DB;
use Carbon\Carbon;
use Password;
use Mail;

class adminAuth extends Controller
{
    //

    public function login(){
    	return view('admin.login');
    }

    public function doLogin(){
    	$remember = request('remember') == 1 ? true : false;

        $this->validate(request(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

    	if(auth()->guard('admin')->attempt(['email'=>request('email'), 'password'=>request('password')], $remember)){
    		return redirect('admin/home');
    	}
    	else{
    		session()->flash('error', trans('admin.error_information_login'));
    		return redirect('admin/login');
    	}
    }

    public function logout(){
    	auth()->guard('admin')->logout();
    	return redirect('admin/login');
    }

    public function ressetPassword(){
    	return view('admin.resset_password');
    }

    public function ressetPasswordPost(){
    	$admin = Admin::where('email', request('email'))->first();
    	
    	if(!empty($admin)){

            $token = Password::broker('admins')->createToken($admin);

    		$data = DB::table('password_resets')->insert([
    			'email' => $admin->email,
    			'token' => $token,
    			'created_at' => Carbon::now(),
    		]);

    		Mail::to($admin->email)->send(new AdminRessetPassword(['admin'=>$admin, 'token'=>$token]));
            session()->flash('success', trans('admin.resset_link_sent_successfully'));
    	}

    	return back();
    }

    public function ressetPasswordToken($token){
        $checkToken = DB::table('password_resets')->where('token', $token)->where('created_at', '>', Carbon::now()->subHour(2))->first();
        if(!empty($checkToken)){
            return view('admin.resset_password_token', ['email'=> $checkToken->email]);
        }
        else{
            return redirect('admin/resset/password');
        }
    }

    public function ressetPasswordTokenPost($token){
        $checkToken = DB::table('password_resets')->where('token', $token)->where('created_at', '>', Carbon::now()->subHour(2))->first();
        if(!empty($checkToken)){
            Admin::where('email', $checkToken->email)->update(['email'=>$checkToken->email, 'password'=> bcrypt(request('password'))]);
            DB::table('password_resets')->where('token', $token)->where('created_at', '>', Carbon::now()->subHour(2))->delete();
            auth()->guard('admin')->attempt(['email'=>$checkToken->email, 'password'=>request('password')], true);
            return redirect('admin/');
        }
        else{
            return redirect('admin/resset/password');
        }
    }
}
