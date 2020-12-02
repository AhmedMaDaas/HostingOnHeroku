<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\classes\logClass;
use App\Http\Controllers\Classes\indexClass;
use Laravel\Socialite\Facades\Socialite;

class log extends Controller
{
    
    function Log(){
    	if(session('login') || \Cookie::get('remembered')){
            //session()->flush();
    		return \Redirect::route('home.get');
    	}else{
            $indexClass = new indexClass();
            // $departmentsParents = $indexClass->getDepartmentsWithParent2();
            // $subDepartmentWithoutParent = $indexClass->getSubDepsDontHaveParent();
            // $sumQuantityAndTotalCost = $indexClass->checkLogin();

            /*
            *
            *   this elements is necassery for all pages in web site
            */
            $arr = $indexClass->getPrimaryElementForAllPages('login');

            // $arr1 = [
            //     // 'departmentsParents' => $departmentsParents,
            //     // 'subDepartmentWithoutParent' => $subDepartmentWithoutParent,
            //     //'sumQuantity' => $sumQuantityAndTotalCost['sumQuantity'],
            //     //'active' => 'login',

            // ];
    		return view('user_layouts.login',$arr);
    	}
    }


    function Reg(){
    	if(session('login') || \Cookie::get('remembered')){
    		return \Redirect::route('home.get');
    	}else{
            $indexClass = new indexClass();
            // $departmentsParents = $indexClass->getDepartmentsWithParent2();
            // $subDepartmentWithoutParent = $indexClass->getSubDepsDontHaveParent();
            // $sumQuantityAndTotalCost = $indexClass->checkLogin();

            /*
            *
            *   this elements is necassery for all pages in web site
            */
            $arr = $indexClass->getPrimaryElementForAllPages('register');

            // $arr1 = [
            //     // 'departmentsParents' => $departmentsParents,
            //     // 'subDepartmentWithoutParent' => $subDepartmentWithoutParent,
            //     // 'sumQuantity' => $sumQuantityAndTotalCost['sumQuantity'], 
            //     //'active' => 'register',  

            // ];
    		return view('user_layouts.register',$arr);
    	}
    }


    function postLog(Request $request , logClass $logClass){

        if(Request()->ajax()){

            if($request->input('button') == 'facebook' || $request->input('button') == 'google'){
                $id = $request->input('id');
                $name = $request->input('name');
                $email = $request->input('email');
                $picture = $request->input('profile');
                $remember_me = $request->input('remember_me');
                //$userData = json_decode($request->input('userData'));

                $state = $logClass->checkLoginByService($request->input('button'),$id,$name,$email,$picture,$remember_me);
                if(!$state)return response()->json(['operation' => 'failed','errors' => ['something happens'] ]);
                return response()->json(['operation' => 'success']);
            }   

            $email = $request->input('email');
            $password = $request->input('password');
            $remember_me = $request->input('remember_me');
            $validator = \Validator::make($request->all(), [
                                    'email' => 'required|email',
                                    'password' => 'required'
                                    ]);
            if($validator->fails())
            return response()->json(['operation' => 'failed','errors' => $validator->errors() ]);

            if($logClass->checkIfRegister($email,$password,$remember_me)){
                return response()->json(['operation' => 'success']);
            }
            return response()->json(['operation' => 'failed','errors' => ['invalid information'] ]);

        }else{

            $remember_me = $request->has('remember_me');

            /*
            * using session remember_me to check it when service return callback method
            *
            *
            */
            if(isset($_POST['facebook'])){
                return $this->redirectToService('facebook',$remember_me);
                // session(['remember_me'=>$remember_me]);
                // return Socialite::driver('facebook')->redirect();
            }

            if(isset($_POST['google'])){
                return $this->redirectToService('google',$remember_me);
                // session(['remember_me'=>$remember_me]);
                // return Socialite::driver('google')->redirect();
            }

            $data = $this->validate(request(),[
                'email'=>'required|email',
                'password'=>'required'
            ]);

        
            //$logClass = new logClass();
            $email = $request->input('email');
            $password = $request->input('password');
            //$hashedPassword = \Hash::make($password);
            if($logClass->checkIfRegister($email,$password,$remember_me)){
                if(session('back'))return \Redirect::to(session('back'));//->withCookie(\Cookie::forget('login'));
                return \Redirect::route('home.get');//->withCookie(\Cookie::forget('login'));
            }
            return \Redirect::route('login')->with('failed','insert correct information');
        }
    }

    function postReg(Request $request){
        if(Request()->ajax()){
            //return response()->json(['operation' => 'failed','errors' => ['invalid information'] ]);
            $fname = $request->input('fname');
            $lname = $request->input('lname');
            $email = $request->input('email');
            $password = $request->input('password');
            $hashedPassword = \Hash::make($password);
            $confirmpassword = $request->input('confirmpassword');
            $hashedConfirmPassword = \Hash::make($confirmPassword);
            $phone = $request->input('phone');

            $validator = \Validator::make($request->all(), [
                                    'fname' => 'required|min:3|max:12',
                                    'lname' =>'required|min:1',
                                    'email' => 'required|email|unique:users',
                                    'password' => 'required_with:confirmpassword|same:confirmpassword|min:7',
                                    'phone'=>'required|numeric',
                                    ]);
            if($validator->fails())
            return response()->json(['operation' => 'failed','errors' => $validator->errors() ]);

            $reg = $logClass->register($email,$hashedPassword,$hashedConfirmPassword,$firstName,$lastName,$phone);
                if($reg == true){
                    return response()->json(['operation' => 'success']);
                }

        }else{
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
            if($reg == true){
                if(session('back'))return \Redirect::to(session('back'));
                return \Redirect::route('home.get');
            }
        }
    }
        

    function redirectToService($service,$remember_me){
        session(['remember_me'=>$remember_me]);
        return Socialite::driver($service)->redirect(); 
    }

    function callback($service ,logClass $logClass){
        try {

            $user = Socialite::with($service)->user();

            // $request = new Request([
            //     'email' => $user->getEmail(),
            // ]);

            // $this->validate($request, [
            //     'email' => 'unique:users',
            // ]);

            $state = $logClass->checkLoginByService($service,$user->getId(),$user->getName(),$user->getEmail(),$user->getAvatar(),session('remember_me'));
            session()->forget('remember_me');
            if(!$state)return \Redirect::route('login')->with('failed', 'this email is founded');
            if(session('back'))return \Redirect::to(session('back'));
            return \Redirect::route('home.get');


        } catch (Exception $e) {


            return \Redirect::route('login')->with('failed', 'something its get failed');


        }
    }


    function logout(){
        //\Auth::logout();
        session()->flush();
        //\Cookie::forget('login');
        return \Redirect::route('login')->withCookie(\Cookie::forget('remembered'));
    }
}
