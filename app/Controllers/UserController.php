<?php

namespace App\Controllers;

//TODO Endpunkte hier rein
use Illuminate\Http\JsonResponse;

class UserController{
    //TODO GET User (Log in)
    function getUser(){
        //check if User exists
        //authenticate user?
        //get the good stuff
    }

//TODO POST User (register)

//check if email already exists
//do we need token?
//post the good stuff

//TODO PATCH User
//check if user exists
//authenticate!
//check if email is changed -> check if its not already in use
//patch that user

//TODO DELETE User
//check if User exists
//check permission (authenticate)
//delete user
//does delete have to check if every connection to that user is deleted ->
//delete reviews from that user
}
