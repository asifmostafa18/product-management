@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">Product Details</div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded">
                    @else
                        <div class="text-center py-4 bg-light">No Image Available</div>
                    @endif
                </div>
                <div class="col-md-8">
                    <h3>{{ $product->name }}</h3>
                    <p class="text-muted">${{ number_format($product->price, 2) }}</p>
                    <p>Quantity: {{ $product->quantity }}</p>
                    <hr>
                    <h5>Description</h5>
                    <p>{{ $product->description ?? 'No description available' }}</p>
                </div>
            </div>
            <div class="mt-3">
                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning">Edit</a>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </div>
    </div>
@endsection
