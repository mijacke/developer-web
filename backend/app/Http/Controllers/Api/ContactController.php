<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    /**
     * Handle contact form submission
     * POST /api/contact
     */
    public function store(ContactRequest $request): JsonResponse
    {
        // Store the message in database
        $message = ContactMessage::create([
            'first_name' => $request->input('firstName'),
            'last_name' => $request->input('lastName'),
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => $request->message,
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'message' => 'Ďakujeme za vašu správu! Budeme vás kontaktovať.',
            'id' => $message->id,
        ], 201);
    }
}
