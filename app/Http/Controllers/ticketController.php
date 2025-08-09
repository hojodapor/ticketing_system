<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Ticket;

class ticketController extends Controller
{
    public function showForm(): View
    {
        return view('tickets');
    }

    public function processForm(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        Ticket::create([
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'Open',
            'priority' => 'Medium'
        ]);

        return redirect()->back()->with('success', 'Your ticket has been submitted successfully!');
    }
}
