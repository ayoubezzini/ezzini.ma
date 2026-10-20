<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactMessageRequest;
use App\Mail\ContactMessageMail;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Throwable;

class ContactMessagesController extends Controller
{
    public function store(StoreContactMessageRequest $request): JsonResponse
    {
        $mail = new ContactMessageMail(
            senderName: $request->validated('name'),
            senderEmail: $request->validated('email'),
            company: $request->validated('company'),
            messageBody: $request->validated('message'),
            visitorLocale: $request->validated('locale'),
        );

        try {
            Mail::to(config('resume.email'))->send($mail);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'message' => __('site.contact.errors.delivery', ['email' => config('resume.email')]),
            ], 503);
        }

        return response()->json([
            'message' => __('site.contact.sent'),
        ]);
    }
}
