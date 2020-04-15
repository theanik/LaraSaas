@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (auth()->user()->trial_ends_at)
                        <div class="alert alert-info">
                            your have {{ now()->diffInDays(auth()->user()->trial_ends_at)}} day's trial
                        </div>
                    @endif
                    @if(session('message'))
                        <div class="alert alert-info">{{ session('message') }}</div>
                    @endif
                    @if(is_null($currentPlan))
                        You are in free trile
                    @elseif($currentPlan->trial_ends_at )
                        your trial ends on {{ $currentPlan->trial_ends_at->toDateString() }} and your card will be charged.
                    @endif
                <div class="row">
                    
                @foreach($plans as $plan)
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">{{ $plan->name }}</h5>
                                <p class="card-text">Price : {{ number_format($plan->price / 100,2) }}</p>
                                    @if($currentPlan)
                                        @if($plan->stripe_plan_id == $currentPlan->stripe_plan)
                                            Your current paln.
                                            @if(!$currentPlan->onGracePeriod())
                                                <a href="{{ route('cancel') }}" onclick="return comfirm('Are you sure?')"
                                                class="btn btn-danger">Cancel</a>
                                            @else
                                                Your subscription will end on {{ $currentPlan->ends_at->toDateString() }}
                                                <a href="{{ route('resume') }}" onclick="return comfirm('Are you sure?')"
                                                class="btn btn-primary">Resume</a>
                                            @endif
                                        @endif
                                    @else
                                    <a href="{{ route('checkout',$plan->id ) }}" class="btn btn-primary">Go To Purchase</a>
                                    @endif
                                    
                            </div>
                        </div>
                    </div>
                  @endforeach
                   
                </div>

                </div>
            </div>

            @if (!is_null($currentPlan))

            <div class="card">
                <div class="card-header">
                    My Payment Method
                   
                    <a class="btn btn-primary" href="{{ route('payment.create') }}" >Add new</a>
                </div>

                <div class="card-body">
                  
                <div class="row">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Brand</th>
                                <th>Expire At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($paymentMethods as $paymentMethod)
                                <tr>
                                    <td>{{ $paymentMethod->card->brand }}</td>
                                    <td>{{ $paymentMethod->card->exp_month }}-{{ $paymentMethod->card->exp_year}}</td>
                                    <td>
                                        @if($defaultPaymentMethod->id == $paymentMethod->id)
                                            Default 
                                        @else 
                                            <a href="{{ route('payment.makeDefault',$paymentMethod->id) }}">Make it default</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            
                        </tbody>
                    </table>
                </div>

                </div>
            </div>
                
            @endif


            {{-- Begain modal --}}
                <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add New Payment Method</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </div>
                        <div class="modal-body">
                            <form id="formSubmit">
                                @csrf
                                <input type="text" placeholder="">
                            </form>
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                    </div>
                </div>
                {{-- end modal --}}
        </div>
    </div>
</div>
@endsection

@push('js')
    <script>
        $.ajaxSetup({
            headers : {
                'X-CRSF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            }
        });
        function openModal() {
            $('#createModal').modal('show')
            console.log("click")
        }
    </script>
@endpush
