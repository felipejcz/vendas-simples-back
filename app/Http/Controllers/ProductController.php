<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data = Product::all();
            return view('product.index', ['products' => $data]);
        } catch (Exception $e) {
            Log::debug('ProductController index Error:');
            Log::debug($e);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            return view('product.create');
        } catch (Exception $e) {
            Log::debug('ProductController create Error:');
            Log::debug($e);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $params = $request->all();
            Product::create($params);
            return redirect('/products')->with('success', 'Product created success!');
        } catch (Exception $e) {
            Log::debug('ProductController store Error:');
            Log::debug($e);
            return redirect()->back()->with('error', $e->getMessage())->withInput($request->all());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $data = Product::findOrFail($id);
            return view('product.show', $data);
        } catch (Exception $e) {
            Log::debug('ProductController show Error:');
            Log::debug($e);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $data = Product::findOrFail($id);
            return view('product.edit', ['product' => $data]);
        } catch (ModelNotFoundException $e) {
            Log::debug('ProductController edit Error:');
            Log::debug($e);
            return redirect()->back()->with('error', 'Product not found with this id.');
        } catch (Exception $e) {
            Log::debug('ProductController edit Error:');
            Log::debug($e);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            $params = $request->all();
            $data = Product::findOrFail($params['id']);
            $data->name = $params['name'];
            $data->description = $params['description'];
            $data->price = $params['price'];
            $data->stock = $params['stock'];
            $data->active = $params['active'];
            $data->save();
            return redirect('/products')->with('success', 'Product updated success!');
        } catch (Exception $e) {
            Log::debug('ProductController update Error:');
            Log::debug($e);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $data = Product::findOrFail($id);
            $data->delete();
            return redirect('/products')->with('success', 'Product destroyed success!');
        } catch (ModelNotFoundException $e) {
            Log::debug('ProductController destroy Error:');
            Log::debug($e);
            return redirect()->back()->with('error', 'Product not found with this id.');
        } catch (Exception $e) {
            Log::debug('ProductController destroy Error:');
            Log::debug($e);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
