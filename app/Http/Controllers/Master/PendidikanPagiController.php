<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PendidikanPagiController extends Controller
{
    public function index () {

        return view('admin.Master.pendPagi.pendPagi');
    }
}
