<?php

namespace App\Controllers;

//TODO Endpunkte hier rein
use Illuminate\Http\JsonResponse;
use App\Models\User;
use Illuminate\Http\Request;

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
     $user->password = $request->password;
     $user->skinType = $request->skinType;
     $user->save();
     return new JsonResponse('user created',201);
}
}

//TODO PATCH User
//check if user exists
//authenticate!
//check if email is changed -> check if its not already in use
//patch that user

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
