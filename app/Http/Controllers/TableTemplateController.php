<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TableTemplateController extends Controller
{
    public function store(Request $request) {
        dd($request->all());
    }
}
