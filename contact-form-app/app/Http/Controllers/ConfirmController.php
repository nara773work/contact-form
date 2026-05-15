<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConfirmController extends Controller
{
    public function store(Request $request){
        
    $validated = $request->validate([
    'first_name' => 'required',
    'last_name'  => 'required',
    'email'      => 'required',
    'tel'        => 'required',
    'address'    => 'required',
    'building'   => 'nullable',
    'detail'     => 'required',
    'gender'     => 'required',
    'category' => 'required'
]);

        return view('contact.confirm',compact('validated'));
    }



}
