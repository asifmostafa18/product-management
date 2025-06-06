@extends('layouts.app')

@section('content')
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Products</h1>
            <a href="{{ route('products.create') }}" class="btn btn-primary">Add Product</a>
            {{-- <a href="{{ route('products.export') }}" class="btn btn-success btn-sm">Export to Excel</a> --}}

            {{-- BULK DELETE BUTTON --}}
            {{-- <form id="bulk-form" action="{{ route('products.bulk-delete') }}" method="POST">
        @csrf

            <div>
                <button type="button" class="btn btn-danger btn-md" onclick="confirmBulkDelete()">Delete Selected</button>
            </div> --}}

            {{-- BULK DELETE BUTTON --}}
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
    {{-- avobe the table --}}

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('products.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Search by name" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="number" step="0.01" name="min_price" class="form-control" placeholder="Min price" value="{{ request('min_price') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="number" step="0.01" name="max_price" class="form-control" placeholder="Max price" value="{{ request('max_price') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="min_quantity" class="form-control" placeholder="Min quantity" value="{{ request('min_quantity') }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{-- avobe the table --}}
        <div class="card">
            <div class="card-body">
                <table class="table table-striped" id="products-table">
                    <thead>
                        <tr>
                            {{-- Adding BULK DELETE --}}
                            {{-- <th width="50">
                                <input type="checkbox" id="select-all">
                            </th> --}}

                            {{-- Adding BULK DELETE --}}
                            <th>ID</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            {{-- <td><input type="checkbox" name="ids[]" value="{{ $product->id }}"></td> --}}
                            <td>{{ $product->id }}</td>
                            <td>
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" width="50" height="50" class="img-thumbnail">
                                @else
                                    No Image
                                @endif
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>${{ number_format($product->price, 2) }}</td>
                            <td>{{ $product->quantity }}</td>
                            {{-- Status Field --}}
                            <td>
    <span class="badge bg-{{ $product->status == 'active' ? 'success' : ($product->status == 'out_of_stock' ? 'warning' : 'secondary') }}">
        {{ ucfirst(str_replace('_', ' ', $product->status)) }}
    </span>
</td>
                            {{-- Status Field --}}
                            <td>
                                <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-info">View</a>
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#products-table').DataTable();
        });
    </script>
@endsection

<!-- Add this JavaScript -->
{{-- @section('scripts')
<script>
    $(document).ready(function() {
        $('#products-table').DataTable();

        // Select all checkboxes
        $('#select-all').click(function() {
            $('input[name="ids[]"]').prop('checked', this.checked);
        });
    });

    function confirmBulkDelete() {
        if (confirm('Are you sure you want to delete the selected products?')) {
            $('#bulk-form').submit();
        }
    }
</script>
@endsection --}}
