@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                <div class="row">
                    @if(session('message'))
                        <div class="alert alert-info">{{ session('message') }}</div>
                    @endif
                @foreach($plans as $plan)
                    <div class="col-md-4">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">{{ $plan->name }}</h5>
                                <p class="card-text">Price : {{ number_format($plan->price / 100,2) }}</p>
                                <a href="{{ route('checkout',$plan->id ) }}" class="btn btn-primary">Go To Purchase</a>
                            </div>
                        </div>
                    </div>
                  @endforeach
                   
                </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
