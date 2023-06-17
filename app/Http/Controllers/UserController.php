<?php

namespace App\Http\Controllers;

use App\Models\SkinType;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Enum;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum', ['except' => ['postUser']]);
    }

    //GET User
    function getUser(Request $request): JsonResponse
    {
        $user = User::findOrFail($request->user()->id);

        //sending result
        return new JsonResponse($user, 200);
    }

    //POST User (register)
    function postUser (Request $request): JsonResponse
    {
        // Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255| unique:users',
            'password' => 'required|string|min:4',  //TODO change to 8 characters
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'skinType' => [new Enum(SkinType::class), 'nullable'],
        ]);

        /*check if email already exists, create user
        $mailExists = User::where('email', '=', $request->email)->first();
        if ($mailExists !== null)
        {
            return new JsonResponse('Mail already exists', 422);
        }
        else */
        {
            $user = new User();
            $user->name = $request->name;
            $user->firstname = $request->firstname;
            $user->lastname = $request->lastname;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->skinType = ($request->skinType === null? 'none' : $request->skinType);
            $user->save();
            return new JsonResponse('user created', 201);
        }
    }

    function patchUser (Request $request): JsonResponse
    {
        error_log($request);
        // Validation
        $request->validate([
            'name' => 'string|max:255|nullable',
            'email' => 'string|email|max:255|nullable',
            'password' => 'string|min:4|nullable',  //TODO change to 8 characters
            'firstname' => 'string|nullable',
            'lastname' => 'string|nullable',
            'skinType' => [new Enum(SkinType::class), 'nullable'],
        ]);

    //check if user exists, looks for changes and changes them
       $user = $request->user();

        if ($user === null)
        {
            return new JsonResponse('This User does not exists', 404);
        }
        else if(User::whereEmail($request->email) !== null && $request->email !== $user->email)
        {
            return new JsonResponse('E-Mail-Adresse bereits vergeben', 400);
        }
        else
        {
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
            $user->save();
            return new JsonResponse('User was patched', 200);
        }
    }


    //DELETE User
    function deleteUser (Request $request): JsonResponse
    {
        //check if User exists and delete
        $user = $request->user();
        if ($user !== null)
        {
            $user->delete();
            //User::truncate();
            return new JsonResponse('User successfully deleted', 204);
        }
        else
        {
            return new JsonResponse('User doesnt have to be deleted, it never even existed', 404);
        }
        //does delete have to check if every connection to that user is deleted? ->
        //delete reviews from that user
    }
}
