<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\contact;

use Illuminate\Validation\ValidationException;

use Illuminate\Support\Facades\Validator;
class ContactController extends Controller
{
    
    public function addContact(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'=>'required',
            'email'=>'required | email',
            'state'=>'required',
            'phone'=>'required | min:8',
            'description'=>'required',
         ]);

         if ($validator->fails()) {
           return $validator->errors();
        }
         
        $Contact = Contact::create($request->all());
    }   

   
}
