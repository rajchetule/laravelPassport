<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function registration(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('laravelPassport')->accessToken;
        $success['name'] = $user->name;

        return $this->sendResponse($success,'User Register Successfully');
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password ])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('laravelPassport')->accessToken;
            $success['name'] = $user->name;

            return $this->sendResponse($success,'User Login Successfully');
        }else {
            return $this->sendError22('Unauthorize',['error'=> 'Unauthorised']);
        }
    }
}
