<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Order;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
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
            $data = Order::all();
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
            return view('order.create');
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
            $params = $request->all();
            Order::create($params);
            return redirect('/orders')->with('success', 'Order created success!');
        } catch (Exception $e) {
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
            $data = Order::findOrFail($id);
            return view('order.edit', ['order' => $data]);
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
            $params = $request->all();
            $data = Order::findOrFail($params['id']);
            $data->name = $params['name'];
            $data->document = $params['document'];
            $data->address = $params['address'];
            $data->email = $params['email'];
            $data->active = $params['active'];
            if (!is_null($params['password'])) {
                $data->password = bcrypt($params['password']);
            } else {
                unset($params['password']);
            }
            $data->save();
            return redirect('/orders')->with('success', 'Order updated success!');
        } catch (Exception $e) {
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
            $data = Order::findOrFail($id);
            $data->delete();
            return redirect('/orders')->with('success', 'Order destroyed success!');
        } catch (ModelNotFoundException $e) {
            Log::debug('OrderController destroy Error:');
            Log::debug($e);
            return redirect()->back()->with('error', 'Order not found with this id.');
        } catch (Exception $e) {
            Log::debug('OrderController destroy Error:');
            Log::debug($e);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
