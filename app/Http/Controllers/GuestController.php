<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuestController extends Controller
{
  public function HomeView(){

  	return redirect('login');

  	return "This is Home Page";
  }

  public function Verify(){
  	return redirect()->route('guest.verifySamsungProduct');
  }
}
