<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\InventoryProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;


class InventoryController extends Controller
{

    public function create()
    {
        $customers =  Customer::all();
        $products = Product::all();
        return view('pos.create',['customers'=>$customers,'products'=>$products]);
    }

    public function findPrice(Request $request)
    {
        $id = $request->id;
       $product  = DB::table('products')->where('id',$id)->first();
       return response()->json($product);

    }


    public function store(Request $request)
    {
        $inventory =  new Inventory();
        $inventory->date = $request->date;
        $inventory->billNo = mt_rand(100000,999999);
        $inventory->customer_id = $request->customer_id;
        $inventory->totalBillAmount	= $request->totalBillAmount;
        $inventory->totalDiscount	= $request->totalDiscount;
        $inventory->paidAmount	= $request->paidAmount;
        $inventory->dueAmount	= $request->dueAmount;
        $inventory->save();

        foreach ( $request->product_id as $key => $product_id){

            $inventoryProduct  = new InventoryProduct();
            $inventoryProduct->inventory_id = $inventory->id;
            $inventoryProduct->product_id = $request->product_id[$key];
            $inventoryProduct->rate = $request->price[$key];
            $inventoryProduct->qty = $request->qty[$key];
            $inventoryProduct->discount = $request->dis[$key];
            $inventoryProduct->save();
        }

        return response()->json(['success' => true]);


    }

    public function show()
    {
        $customers =  Customer::all();
        $products = Product::all();
        return view('pos.edit',['customers'=>$customers,'products'=>$products]);
    }

    public function find(Request $request)
    {
        $billNo = $request->billNo;

        $data = Inventory::with('inventoryProducts')->where('billNo',$billNo)->get();
        dd($data);
    }

}
