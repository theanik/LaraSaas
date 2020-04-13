<?php

namespace App\Http\Controllers\Checkout;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Plan;
class CheckoutController extends Controller
{
    public function index($plan_id)
    {
        $plan = Plan::findOrFail($plan_id);
        $intent = auth()->user()->createSetupIntent();
        $currentPlan = auth()->user()->subscription('default')->stripe_plan ?? NULL;
        if(!is_null($currentPlan) && $currentPlan != $plan->stripe_plan_id){
            auth()->user()->subscription('default')->swap($plan->stripe_plan_id);
            return redirect()->route('billing');
        }
        if($plan){
            return view('billing.checkout',compact('plan','intent'));
        }
    }


    public function process(Request $req){
        // dd($req->all());
        $plan = Plan::findOrFail($req->input('billing_plan_id'));
        try{
            $payment_method = $req->input('payment-method');
            auth()->user()->newSubscription($plan->name,$plan->stripe_plan_id)->create($payment_method,[]);
            return redirect()->route('billing')->withMessage('Payment Successfully Done!!!');
        }catch(\Exception $e){
            return redirect()->back()->withError($e->getMessage());
        }
        
    }
}
