<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    // Display listing
    public function index(Request $request)
    {
        $query = Product::query();

    // Search by name
    if ($request->has('search'))
        $query->where('name', 'like', '%'.$request->search.'%');


    // Filter by price range
    if ($request->has('min_price')) {
        $query->where('price', '>=', $request->min_price);
    }
    if ($request->has('max_price')) {
        $query->where('price', '<=', $request->max_price);
    }

    // Filter by quantity
    if ($request->has('min_quantity')) {
        $query->where('quantity', '>=', $request->min_quantity);
    }

    $products = $query->latest()->paginate(10);
        // $products = Product::latest()->paginate(10);
        return view('products.index', compact('products'));
    }


    // Show create form
    public function create()
    {
        return view('products.create');
    }

    // Store new product
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive,out_of_stock',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    // Show single product
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    // Show edit form
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    // Update product
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive,out_of_stock',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    // Delete product
    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
    // BULK DELETE

//     public function bulkDelete(Request $request)
// {
//     $request->validate([
//         'ids' => 'required|array',
//         'ids.*' => 'exists:products,id'
//     ]);

//     $products = Product::whereIn('id', $request->ids)->get();

//     foreach ($products as $product) {
//         if ($product->image) {
//             Storage::disk('public')->delete($product->image);
//         }
//         $product->delete();
//     }

//     return back()->with('success', 'Selected products deleted successfully.');
// }
    // BULK DELETE


public function export()
{
    return Excel::download(new ProductsExport, 'products.xlsx');
}
}
