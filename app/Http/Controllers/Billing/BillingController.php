<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Plan;
class BillingController extends Controller
{
    public function index()
    {
        $plans = Plan::all();
        $currentPlan = auth()->user()->subscription('default') ?? NULL;
        $paymentMethods = auth()->user()->paymentMethods();
        $defaultPaymentMethod = auth()->user()->defaultPaymentMethod();
        // dd($defaultPaymentMethod);
        return view('billing.index',compact('plans','currentPlan','paymentMethods','defaultPaymentMethod'));
    }

    public function cancel(){
    	auth()->user()->subscription('default')->cancel();
    	return redirect()->route('billing');
    }

    public function resume()
    {
    	auth()->user()->subscription('default')->resume();
    	return redirect()->route('billing');
    }
}
