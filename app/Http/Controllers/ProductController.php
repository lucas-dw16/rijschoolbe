<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $productModel;

    public function __construct()
    {
        $this->productModel = new Product();
    }

    public function index()
    {
        $producten = $this->paginateArray($this->productModel->sp_GetAllProducten(), 4);

        return view('producten.index', [
            'title' => 'Producten',
            'producten' => $producten,
        ]);
    }

    public function create()
    {
        return view('producten.create', [
            'title' => 'Nieuw product toevoegen',
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'naam' => 'required|string|max:100',
            'beschrijving' => 'required|string|max:255',
            'prijs' => 'required|numeric|min:0',
        ]);

        $newId = $this->productModel->sp_CreateProduct(
            $data['naam'],
            $data['beschrijving'],
            $data['prijs']
        );

        return redirect()->route('producten.index')
            ->with('success', 'Product succesvol toegevoegd met id ' . $newId);
    }

    public function edit($id)
    {
        $product = $this->productModel->sp_GetProductById($id);
        abort_if(! $product, 404);

        return view('producten.edit', [
            'title' => 'Product wijzigen',
            'product' => $product,
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'naam' => 'required|string|max:100',
            'beschrijving' => 'required|string|max:255',
            'prijs' => 'required|numeric|min:0',
        ]);

        $result = $this->productModel->sp_UpdateProduct(
            $id,
            $data['naam'],
            $data['beschrijving'],
            $data['prijs']
        );

        if ($result > 0) {
            return redirect()->route('producten.index')
                ->with('success', 'Product succesvol gewijzigd');
        }

        return back()->withInput()->with('error', 'Product is niet gewijzigd');
    }

    public function destroy($id)
    {
        $result = $this->productModel->sp_DeleteProduct($id);

        if ($result > 0) {
            return redirect()->route('producten.index')
                ->with('success', 'Product succesvol verwijderd');
        }

        return redirect()->route('producten.index')
            ->with('error', 'Product is niet verwijderd');
    }

    private function paginateArray(array $items, int $perPage): LengthAwarePaginator
    {
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $collection = collect($items);
        $results = $collection->slice(($currentPage - 1) * $perPage, $perPage)->values();

        return new LengthAwarePaginator(
            $results,
            $collection->count(),
            $perPage,
            $currentPage,
            [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
            ]
        );
    }
}
