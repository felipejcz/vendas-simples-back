<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Exception;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data = Order::all()->load('customer');
            return view('order.index', ['orders' => $data]);
        } catch (Exception $e) {
            Log::debug('OrderController index Error:');
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
            $products = Product::all();
            $customers = Customer::all();
            return view('order.create', ['products' => $products, 'customers' => $customers]);
        } catch (Exception $e) {
            Log::debug('OrderController create Error:');
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
            DB::beginTransaction();
            $params = $request->all();
            $products = json_decode($params['order_items'], true);
            $order = Order::create([
                'customer_id' => $params['customer_id'],
                'status' => $params['status'],
                'description' => $params['description']
            ]);
            foreach ($products as $item) {
                OrderItems::create([
                    'quantity' => $item['quantity'],
                    'product_id' => $item['id'],
                    'order_id' => $order->id
                ]);
            }
            DB::commit();
            return redirect('/orders')->with('success', 'Order created success!');
        } catch (Exception $e) {
            DB::rollBack();
            Log::debug('OrderController store Error:');
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
            $data = Order::findOrFail($id);
            return view('order.show', $data);
        } catch (Exception $e) {
            Log::debug('OrderController show Error:');
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
            $order = Order::findOrFail($id);
            $order_items = OrderItems::where('order_id', $id)->get();
            $products = Product::all();
            $customers = Customer::all();
            return view('order.edit', ['order' => $order, 'order_items' => $order_items, 'products' => $products, 'customers' => $customers]);
        } catch (ModelNotFoundException $e) {
            Log::debug('OrderController edit Error:');
            Log::debug($e);
            return redirect()->back()->with('error', 'Order not found with this id.');
        } catch (Exception $e) {
            Log::debug('OrderController edit Error:');
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
            DB::beginTransaction();
            $params = $request->all();
            $products = json_decode($params['order_items'], true);
            $order = Order::findOrFail($params['id']);
            $order->customer_id = $params['customer_id'];
            $order->status = $params['status'];
            $order->description = $params['description'];
            $order->save();
            OrderItems::where('order_id', $order->id)->delete();
            foreach ($products as $item) {
                OrderItems::create([
                    'quantity' => $item['quantity'],
                    'product_id' => $item['id'],
                    'order_id' => $order->id
                ]);
            }
            DB::commit();
            return redirect('/orders')->with('success', 'Order updated success!');
        } catch (Exception $e) {
            DB::rollBack();
            Log::debug('OrderController update Error:');
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
            DB::beginTransaction();
            $data = Order::findOrFail($id);
            $data->delete();
            DB::commit();
            return redirect('/orders')->with('success', 'Order destroyed success!');
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            Log::debug('OrderController destroy Error:');
            Log::debug($e);
            return redirect()->back()->with('error', 'Order not found with this id.');
        } catch (Exception $e) {
            DB::rollBack();
            Log::debug('OrderController destroy Error:');
            Log::debug($e);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
