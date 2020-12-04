<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data = Customer::all();
            return view('customer.index', ['customers' => $data]);
        } catch (Exception $e) {
            Log::debug('CustomerController index Error:');
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
            return view('customer.create');
        } catch (Exception $e) {
            Log::debug('CustomerController create Error:');
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
            $params['password'] = bcrypt($params['password']);
            Customer::create($params);
            DB::commit();
            return redirect('/customers')->with('success', 'Customer created success!');
        } catch (Exception $e) {
            DB::rollBack();
            Log::debug('CustomerController store Error:');
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
            $data = Customer::findOrFail($id);
            return view('customer.show', $data);
        } catch (Exception $e) {
            Log::debug('CustomerController show Error:');
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
            $data = Customer::findOrFail($id);
            return view('customer.edit', ['customer' => $data]);
        } catch (ModelNotFoundException $e) {
            Log::debug('CustomerController edit Error:');
            Log::debug($e);
            return redirect()->back()->with('error', 'Customer not found with this id.');
        } catch (Exception $e) {
            Log::debug('CustomerController edit Error:');
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
            $data = Customer::findOrFail($params['id']);
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
            DB::commit();
            return redirect('/customers')->with('success', 'Customer updated success!');
        } catch (Exception $e) {
            DB::rollBack();
            Log::debug('CustomerController update Error:');
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
            $msg = 'Customer destroyed success!';
            $data = Customer::findOrFail($id);
            if (Order::where('customer_id', $data->id)->count() > 0) {
                $data->active = false;
                $data->save();
                $msg = 'Customer successfully inactivated!';
            } else {
                $data->delete();
            }
            DB::commit();
            return redirect('/customers')->with('success', $msg);
        } catch (QueryException $e) {
            DB::rollBack();
            switch ($e->getCode()) {
                case 23000:
                    $msg = 'Cannot delete a product linked to sale.';
                    break;

                default:
                    $msg = $e->getMessage();
                    break;
            }
            Log::debug('ProductController destroy Error:');
            Log::debug($e);
            return redirect()->back()->with('error', $msg);
        } catch (ModelNotFoundException $e) {
            Log::debug('CustomerController destroy Error:');
            Log::debug($e);
            return redirect()->back()->with('error', 'Customer not found with this id.');
        } catch (Exception $e) {
            Log::debug('CustomerController destroy Error:');
            Log::debug($e);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
