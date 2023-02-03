<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{

    /**
     *
     * @param  UserLoginRequest  $request
     * @return JsonResponse
     */
    public function login(UserLoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();
        if(!$user){
            return new JsonResponse(['msg' => 'User is not found'], Response::HTTP_NOT_FOUND);
        }

        if(! Hash::check($request->password, $user->password)){
            return new JsonResponse(['msg' => 'User\'s password is wrong'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $token = $user->createToken('auth_token');

        return new JsonResponse(['data' => [
            'token'     =>  $token->plainTextToken,
            'user'      =>  $user,
            'msg'       =>  'User logged in successfully'
        ]], Response::HTTP_OK);
    }

    /**
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        dd($user);
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();

        return new JsonResponse(['msg' => 'User logged out successfully'], Response::HTTP_OK);
    }
}
