<?php

namespace App\Http\Controllers\Checkout;

use App\Country;
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
        $countries = Country::all();
        if(!is_null($currentPlan) && $currentPlan != $plan->stripe_plan_id){
            auth()->user()->subscription('default')->swap($plan->stripe_plan_id);
            return redirect()->route('billing');
        }
        if($plan){
            return view('billing.checkout',compact('plan','intent','countries'));
        }
    }


    public function process(Request $request){
        // dd($req->all());
        $plan = Plan::findOrFail($request->input('billing_plan_id'));
        try{
            $payment_method = $request->input('payment-method');
            auth()->user()->newSubscription('default',$plan->stripe_plan_id)
                            ->create($payment_method,[]);
            auth()->user()->update([
                'trial_ends_at' => NULL,
                'company_name' => $request->input('company_name'),
                'address_line_1' => $request->input('address_line_1'),
                'address_line_2' => $request->input('address_line_2'),
                'country_id' => $request->input('country_id'),
                'city' => $request->input('city'),
                'postcode' => $request->input('postcode'),
            ]);
            return redirect()->route('billing')->withMessage('Payment Successfully Done!!!');
        }catch(\Exception $e){
            return redirect()->back()->withError($e->getMessage());
        }
        
    }
}
