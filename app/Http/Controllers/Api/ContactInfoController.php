<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactInfo;

class ContactInfoController extends Controller
{
    public function index()
    {
        $contactInfo = ContactInfo::first();
        return response()->json(data: $contactInfo);
    }
}