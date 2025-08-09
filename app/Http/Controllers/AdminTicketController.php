<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class AdminTicketController extends Controller
{
    public function index(): View
    {
        $tickets = Ticket::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.tickets.index', compact('tickets'));
    }

    public function show(Ticket $ticket): JsonResponse
    {
        return response()->json([
            'ticket' => $ticket->toArray()
        ]);
    }

    public function updatePriority(Request $request, Ticket $ticket): JsonResponse
    {
        $validated = $request->validate([
            'priority' => [
                'required',
                'string',
                'in:Lowest,Low,Medium,High,Urgent,Critical',
                function ($attribute, $value, $fail) {
                    $validPriorities = ['Lowest', 'Low', 'Medium', 'High', 'Urgent', 'Critical'];
                    if (!in_array($value, $validPriorities)) {
                        $fail('The selected priority is invalid. Please choose from: ' . implode(', ', $validPriorities));
                    }
                }
            ],
        ], [
            'priority.required' => 'Priority is required.',
            'priority.string' => 'Priority must be a string.',
            'priority.in' => 'Invalid priority value. Please select a valid priority.',
        ]);

        try {
            $oldPriority = $ticket->priority;
            $ticket->priority = $validated['priority'];
            $ticket->save();

            return response()->json([
                'success' => true,
                'priority' => $ticket->priority,
                'message' => 'Priority updated successfully',
                'old_priority' => $oldPriority,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update priority: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function updateStatus(Request $request, Ticket $ticket): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|string|in:Open,In Progress,Resolved,Closed',
        ]);

        $ticket->status = $validated['status'];
        $ticket->save();

        return response()->json([
            'success' => true,
            'status' => $ticket->status,
        ]);
    }
}
