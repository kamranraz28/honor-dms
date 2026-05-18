<?php

namespace App\Services;

use App\Product;
use DB;
use File;
use Illuminate\Support\Facades\Storage;

class ProductService
{

    /**
     * Get all products for index page
     */
    public function index()
    {
        return Product::with(['brand', 'cat'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function store(array $data)
    {
        // ---------- File upload ----------
        if (isset($data['image']) && $data['image']) {

            $image = $data['image'];

            $image_name = time()
                . mt_rand()
                . substr(
                    $image->getClientOriginalName(),
                    strripos($image->getClientOriginalName(), '.')
                );

            Storage::put($image_name, file_get_contents($image));

            $data['photo'] = $image_name;
        } else {
            $data['photo'] = null;
        }

        unset($data['image']);

        // ---------- Create Product ----------
        return Product::create($data);
    }

    public function getById($id)
    {
        return Product::with(['brand', 'cat'])->findOrFail($id);
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {

            $product = Product::findOrFail($id);

            // Handle image upload
            if (isset($data['image']) && $data['image']) {
                $file = $data['image'];
                $filename = time().'_'.$file->getClientOriginalName();
                $file->move(storage_path('app/product'), $filename);
                $data['photo'] = $filename;
            }

            unset($data['image']);

            $product->update($data);

            return $product;
        });
    }

    public function destroy($id)
    {
        return DB::transaction(function () use ($id) {

            $product = Product::findOrFail($id);

            // ---------- Delete image if exists ----------
            if ($product->photo) {
                $path = storage_path('app/product/' . $product->photo);

                if (File::exists($path)) {
                    File::delete($path);
                }
            }

            // ---------- Delete product ----------
            $product->delete();

            return true;
        });
    }
}
