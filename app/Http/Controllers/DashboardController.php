<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\User;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }
}