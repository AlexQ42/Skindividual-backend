<?php

namespace App\Controllers;

//TODO Endpunkte hier rein
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Js;

class UserController{
    //GET User
    function getUser(int $userId): JsonResponse
    {

        if($userId < 0) return new JsonResponse(null, 400);

        //check if User exists
        $result = User::findOrFail($userId);

        //sending result
        return new JsonResponse($result, 200);

        //authenticate user?

    }

//POST User (register)
function postUser (Request $request): JsonResponse
{
//check if email already exists, create user
    $mailExists = User::where('email','=', $request->email)->first();
    if ($mailExists !== null) {
        return new JsonResponse('Mail already exists', 422);
    }
    else {
     $user = new User;
     $user->name = $request->name;
     $user->firstname = $request->firstname;
     $user->lastname = $request->lastname;
     $user->email = $request->email;
     $user->password = Hash::make($request->password);
     $user->skinType = $request->skinType;
     $user->save();
     return new JsonResponse('user created',201);
}
}

//TODO PATCH User
function patchUser (int $userId, Request $request): JsonResponse
{

//check if user exists, looks for changes and changes them
   $user = User::findOrFail($userId, 'id')->first();

    if ($user === null)
    {
        return new JsonResponse('This User does not exists', 404);
    }
    else {
      //  if ($user->name->isDirty($request->name))
       // {
          //  $user->name = $request->name;
       // }
        if ($request->name !== null && $request->name !== '' &&$request->name !== $user->name) {
            $user->name = $request->name;
            error_log('Hi');
        }
        if ($request->firstname !== null && $request->firstname !== '' && $request->firstname !== $user->firstname) {
            $user->firstname = $request->firstname;
        }
        if ($request->lastname !== null && $request->lastname !== '' && $request->lastname !== $user->lastname) {
            $user->lastname = $request->lastname;
        }
        if ($request->email !== null && $request->email !== '' &&$request->email !== $user->email) {
            //!!!! Unique Mail or stuff??

            $user->email = $request->email;
        }
        if ($request->password !== null && $request->password !== '' &&$request->password !== $user->password) {
            $user->password = Hash::make($request->password);
        }
        if ($request->skinType !== null && $request->skinType !== '' &&$request->skinType !== $user->skinType) {
            $user->skinType = $request->skinType;
        }
        error_log('mew');
        $user->save();
        return new JsonResponse('User was patched', 200);
    }

//authenticate!
//TODO check if email is changed -> check if its not already in use
//patch that user
}
//DELETE User
function deleteUser (int $id): JsonResponse
{
//check if User exists and delete
    $user = User::find($id);
    if ($user !== null){
        $user->delete();
        //User::truncate();
        return new JsonResponse('User successfully deleted', 204);
    }
    else {
        return new JsonResponse('User doesnt have to be deleted, it never even existed', 404);
    }
//check permission (authenticate)
//does delete have to check if every connection to that user is deleted ->
//delete reviews from that user
}
}
