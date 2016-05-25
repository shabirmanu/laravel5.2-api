<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class PagesController extends Controller
{
    //
  public function about() {
    $first = 'Shabir';
    $last = 'Ahmad';
    $people = [];
    $people = ['Shafqat', 'Ahmad', 'Ayaz'];
    return view('pages.about')->with([
      'first' => $first, 'last' => $last, 'people' => $people
    ]);
  }
}
