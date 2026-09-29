<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class GuideController extends Controller
{
    /**
     * Display the role-based system user guide.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        return view('guide.index', [
            'currentUser' => $user,
            'currentRole' => $user->role->value,
        ]);
    }
}
