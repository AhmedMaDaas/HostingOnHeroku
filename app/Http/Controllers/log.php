<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\classes\logClass;
use App\Http\Controllers\Classes\indexClass;
use Laravel\Socialite\Facades\Socialite;

class log extends Controller
{
    function Log(){
    	if(session('login')){
            //session()->flush();
    		return \Redirect::route('home.get');
    	}else{
            $indexClass = new indexClass();
            $departmentsParents = $indexClass->getDepartmentsWithParent();
            $sumQuantityAndTotalCost = $indexClass->checkLogin();
            $arr = [
                'departmentsParents' => $departmentsParents[0],
                'mainDep' => $departmentsParents[1],
                'sumQuantity' => $sumQuantityAndTotalCost['sumQuantity'],
                'active' => 'login',

            ];
    		return view('user_layouts.login',$arr);
    	}
    }


    function Reg(){
    	if(session('login')){
    		return \Redirect::route('home.get');
    	}else{
            $indexClass = new indexClass();
            $departmentsParents = $indexClass->getDepartmentsWithParent();
            $sumQuantityAndTotalCost = $indexClass->checkLogin();
            $arr = [
                'departmentsParents' => $departmentsParents[0],
                'mainDep' => $departmentsParents[1],
                'sumQuantity' => $sumQuantityAndTotalCost['sumQuantity'], 
                'active' => 'register',  

            ];
    		return view('user_layouts.register',$arr);
    	}
    }


    function postLog(Request $request){

        if(isset($_POST['facebook'])){
            return Socialite::driver('facebook')->redirect();
        }

        if(isset($_POST['google'])){
            return Socialite::driver('google')->redirect();
        }

        $data = $this->validate(request(),[
                'email'=>'required',
                'password'=>'required'
            ]);

        
    	$logClass = new logClass();
    	$email = $request->input('email');
    	$password = $request->input('password');
        //$hashedPassword = \Hash::make($password);
    	if($logClass->checkIfRegister($email,$password))return \Redirect::route('home.get');
    	return \Redirect::route('login');

    }

    function postReg(Request $request){
        //required_with:password_confirmation|same:password_confirmation
        //'password_confirmation' => 'min:6'
        //  $hashed = \Hash::make($request->input('password'));
        //  $request['confirmedpassword'] = \Hash::make($request->input('confirmpassword'));
        // dd(\Hash::check($request->input('password'),$hashed));
        $data = $this->validate(request(),[
                'fname'=>'required|min:3|max:12',
                'lname'=>'required|min:1',
                'email'=>'required|email|unique:users',
                'password'=>'required_with:confirmpassword|same:confirmpassword|min:7',
                'phone'=>'required|numeric',
            ]);

    	$logClass = new logClass();
        $firstName = $request->input('fname');
        $lastName = $request->input('lname');
    	$email = $request->input('email');
    	$password = $request->input('password');
        $hashedPassword = \Hash::make($password);
    	$confirmPassword = $request->input('confirmpassword');
        $hashedConfirmPassword = \Hash::make($confirmPassword);
        $phone = $request->input('phone');

        $reg = $logClass->register($email,$hashedPassword,$hashedConfirmPassword,$firstName,$lastName,$phone);
        if($reg == true)return \Redirect::route('home.get');
    }

    // function redirectToService($service){
    //     if(isset($_POST['facebook'])){
    //         return Socialite::driver('facebook')->redirect();
    //     }
    // }

    function callback($service ,logClass $logClass){
        try {

            $user = Socialite::with($service)->user();

            // $request = new Request([
            //     'email' => $user->getEmail(),
            // ]);

            // $this->validate($request, [
            //     'email' => 'unique:users',
            // ]);

            $state = $logClass->checkLoginByService($service,$user->getId(),$user->getName(),$user->getEmail(),$user->getAvatar());
            if(!$state)return \Redirect::route('login')->with('failed', 'this email is founded');
            return \Redirect::route('home.get');


        } catch (Exception $e) {


            return \Redirect::route('login')->with('failed', 'something its get failed');


        }
    }


    function logout(){
        session()->flush();
        return \Redirect::route('login');
    }
}
