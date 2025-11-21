@extends('layouts.app')

@section('title', 'Equipment - Sports Club Management')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Sports Equipment</h1>
    </div>

    @if($equipment->count() > 0)
        <div class="row">
            @foreach($equipment as $item)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            {{ $item->name }}
                            @if($item->category)
                                <span class="badge badge-info float-right">{{ $item->category }}</span>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-6">
                                    <strong>Total Quantity:</strong><br>
                                    <span class="text-primary">{{ $item->total_quantity }}</span>
                                </div>
                                <div class="col-6">
                                    <strong>Available:</strong><br>
                                    <span class="equipment-status 
                                        @if($item->available_quantity > 5) status-available
                                        @elseif($item->available_quantity > 0) status-low
                                        @else status-unavailable @endif">
                                        <span class="status-dot"></span>
                                        {{ $item->available_quantity }}
                                    </span>
                                </div>
                            </div>

                            @if($item->condition)
                                <p><strong>Condition:</strong> {{ ucfirst($item->condition) }}</p>
                            @endif

                            @if($item->asset_tag)
                                <p><strong>Asset Tag:</strong> {{ $item->asset_tag }}</p>
                            @endif

                            @if($item->available_quantity > 0)
                                <div class="mt-3">
                                    <button type="button" 
                                            class="btn btn-success" 
                                            @click="$refs.reserveModal{{ $item->id }}.style.display = 'flex'">
                                        Reserve Equipment
                                    </button>
                                </div>
                            @else
                                <div class="alert alert-warning mt-3 mb-0">
                                    Currently unavailable
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Reserve Modal -->
                    @auth
                        <div class="modal" x-ref="reserveModal{{ $item->id }}" style="display: none;">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Reserve {{ $item->name }}</h5>
                                    <button type="button" class="close" 
                                            @click="$refs.reserveModal{{ $item->id }}.style.display = 'none'">
                                        &times;
                                    </button>
                                </div>
                                <form action="{{ route('equipment.reserve') }}" method="POST">
                                    @csrf
                                    <div class="modal-body">
                                        <input type="hidden" name="equipment_id" value="{{ $item->id }}">
                                        
                                        <div class="form-group">
                                            <label for="quantity" class="form-label">Quantity</label>
                                            <select name="quantity" class="form-control" required>
                                                @for($i = 1; $i <= min($item->available_quantity, 5); $i++)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="reserved_from" class="form-label">From</label>
                                            <input type="datetime-local" 
                                                   name="reserved_from" 
                                                   class="form-control" 
                                                   min="{{ now()->format('Y-m-d\TH:i') }}" 
                                                   required>
                                        </div>

                                        <div class="form-group">
                                            <label for="reserved_to" class="form-label">To</label>
                                            <input type="datetime-local" 
                                                   name="reserved_to" 
                                                   class="form-control" 
                                                   required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" 
                                                @click="$refs.reserveModal{{ $item->id }}.style.display = 'none'">
                                            Cancel
                                        </button>
                                        <button type="submit" class="btn btn-success">Reserve</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endauth
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $equipment->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <h3>No equipment available</h3>
            <p class="text-muted">Check back later for available equipment!</p>
        </div>
    @endif
</div>
@endsection