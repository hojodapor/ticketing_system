@extends('layouts.admin')
@php use Illuminate\Support\Str; @endphp

@section('title', 'Admin Dashboard - Tickets')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-4">Ticket Management</h1>
    </div>
    <div class="row">
        <div class="col-12">
            
            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Total Tickets</h5>
                            <h3>{{ $tickets->total() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h5 class="card-title">Open</h5>
                            <h3>{{ $tickets->where('status', 'Open')->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h5 class="card-title">In Progress</h5>
                            <h3>{{ $tickets->where('status', 'In Progress')->count() }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">Resolved</h5>
                            <h3>{{ $tickets->where('status', 'Resolved')->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Tickets Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">All Tickets</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Subject</th>
                                    <th>Status</th>
                                    <th>Priority</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tickets as $ticket)
                                <tr>
                                    <td>{{ $ticket->id }}</td>
                                    <td>{{ $ticket->name }}</td>
                                    <td>{{ $ticket->email }}</td>
                                    <td>{{ Str::limit($ticket->subject, 50) }}</td>
                                    <td>
                                        @php
                                            $statusColor = match($ticket->status) {
                                                'Open' => 'danger',
                                                'In Progress' => 'warning',
                                                'Resolved' => 'info',
                                                'Closed' => 'dark',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $statusColor }}">
                                            {{ $ticket->status }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $priorityColor = match(strtolower($ticket->priority)) {
                                                'critical' => 'dark',
                                                'urgent' => 'danger',
                                                'high' => 'warning',
                                                'medium' => 'info',
                                                'low' => 'secondary',
                                                'lowest' => 'light',
                                                default => 'secondary',
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $priorityColor }}">
                                            {{ ucfirst($ticket->priority) }}
                                        </span>
                                    </td>
                                    <td>{{ $ticket->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary view-ticket" 
                                                data-id="{{ $ticket->id }}" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#ticketModal">
                                            View
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $tickets->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Ticket Modal -->
<div class="modal fade" id="ticketModal" tabindex="-1" aria-labelledby="ticketModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ticketModalLabel">Ticket Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="ticketDetails">
                    <!-- Ticket details will be loaded here via AJAX -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    const csrfToken = '{{ csrf_token() }}';

    function getStatusBadge(status) {
        switch(status) {
            case 'Open': return 'danger';
            case 'In Progress': return 'warning';
            case 'Resolved': return 'info';
            case 'Closed': return 'dark';
            default: return 'secondary';
        }
    }
    
    function getPriorityBadge(priority) {
        switch(priority) {
            case 'Critical': return 'dark';
            case 'Urgent': return 'danger';
            case 'High': return 'warning';
            case 'Medium': return 'info';
            case 'Low': return 'secondary';
            case 'Lowest': return 'light';
            default: return 'secondary';
        }
    }

    $('.view-ticket').click(function() {
        const ticketId = $(this).data('id');
        
        $.ajax({
            url: `/admin/tickets/${ticketId}`,
            type: 'GET',
            success: function(response) {
                const ticket = response.ticket;
                let html = `
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Name:</strong> ${ticket.name}</p>
                            <p><strong>Email:</strong> ${ticket.email}</p>
                            <p><strong>Subject:</strong> ${ticket.subject}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Status:</strong> 
                                <select class="form-select form-select-sm status-select" data-ticket-id="${ticket.id}" style="width: auto; display: inline-block;">
                                    <option value="Open" ${ticket.status === 'Open' ? 'selected' : ''}>Open</option>
                                    <option value="In Progress" ${ticket.status === 'In Progress' ? 'selected' : ''}>In Progress</option>
                                    <option value="Resolved" ${ticket.status === 'Resolved' ? 'selected' : ''}>Resolved</option>
                                    <option value="Closed" ${ticket.status === 'Closed' ? 'selected' : ''}>Closed</option>
                                </select>
                            </p>
                            <p><strong>Priority:</strong> 
                                <select class="form-select form-select-sm priority-select" data-ticket-id="${ticket.id}" style="width: auto; display: inline-block;">
                                    <option value="Lowest" ${ticket.priority === 'Lowest' ? 'selected' : ''}>Lowest</option>
                                    <option value="Low" ${ticket.priority === 'Low' ? 'selected' : ''}>Low</option>
                                    <option value="Medium" ${ticket.priority === 'Medium' ? 'selected' : ''}>Medium</option>
                                    <option value="High" ${ticket.priority === 'High' ? 'selected' : ''}>High</option>
                                    <option value="Urgent" ${ticket.priority === 'Urgent' ? 'selected' : ''}>Urgent</option>
                                    <option value="Critical" ${ticket.priority === 'Critical' ? 'selected' : ''}>Critical</option>
                                </select>
                            </p>
                            <p><strong>Created:</strong> ${ticket.created_at}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <p><strong>Message:</strong></p>
                            <p>${ticket.message}</p>
                        </div>
                    </div>
                `;
                
                $('#ticketDetails').html(html);
            },
            error: function() {
                $('#ticketDetails').html('<p>Error loading ticket details.</p>');
            }
        });
    });

    $(document).on('change', '.status-select', function(e) {
        e.preventDefault();
        const $select = $(this);
        const ticketId = $select.data('ticket-id');
        const newStatus = $select.val();
        
        if ($select.prop('disabled')) return;
        $select.prop('disabled', true);

        const originalText = $select.find('option:selected').text();
        $select.find('option:selected').text('Updating...');
        
        $.ajax({
            url: `/admin/tickets/${ticketId}/status`,
            type: 'PUT',
            data: {
                _token: csrfToken,
                status: newStatus
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(`Status updated to ${newStatus}`);
                    const tableRow = $(`.view-ticket[data-id="${ticketId}"]`).closest('tr');
                    const statusBadge = tableRow.find('td:nth-child(5) .badge');
                    
                    statusBadge.text(newStatus);
                    statusBadge.removeClass('bg-danger bg-warning bg-info bg-secondary bg-dark');
                    statusBadge.addClass(`bg-${getStatusBadge(newStatus)}`);
                }
            },
            error: function() {
                toastr.error('Failed to update status. Please try again.');
                $select.val($select.data('previous-value') || $select.val());
            },
            complete: function() {
                $select.prop('disabled', false);
                $select.find('option:selected').text(originalText);
            }
        });
    });

    $(document).on('change', '.priority-select', function(e) {
        e.preventDefault();
        const $select = $(this);
        const ticketId = $select.data('ticket-id');
        const newPriority = $select.val();
        
        if ($select.prop('disabled')) return;
        $select.prop('disabled', true);

        const originalText = $select.find('option:selected').text();
        $select.find('option:selected').text('Updating...');
        
        $.ajax({
            url: `/admin/tickets/${ticketId}/priority`,
            type: 'PUT',
            data: {
                _token: csrfToken,
                priority: newPriority
            },
            success: function(response) {
                if (response.success) {
                    toastr.success(`Priority updated to ${newPriority}`);
                    const tableRow = $(`.view-ticket[data-id="${ticketId}"]`).closest('tr');
                    const priorityBadge = tableRow.find('td:nth-child(6) .badge');
                    
                    priorityBadge.text(newPriority);
                    priorityBadge.removeClass('bg-danger bg-warning bg-info bg-secondary bg-dark bg-light');
                    priorityBadge.addClass(`bg-${getPriorityBadge(newPriority)}`);
                }
            },
            error: function() {
                toastr.error('Failed to update priority. Please try again.');
                $select.val($select.data('previous-value') || $select.val());
            },
            complete: function() {
                $select.prop('disabled', false);
                $select.find('option:selected').text(originalText);
            }
        });
    });

    $(document).on('focus', '.status-select, .priority-select', function() {
        $(this).data('previous-value', $(this).val());
    });

    $('#ticketModal').on('hidden.bs.modal', function() {
        $('.status-select, .priority-select').prop('disabled', false);
    });
});
</script>
@endpush

@endsection
