<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RiwayatDataController extends Controller
{
     public function index() {
        return view('admin.riwayat.index');
     }
}
