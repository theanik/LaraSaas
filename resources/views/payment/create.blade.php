@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Payment</div>

                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                   <form action="{{ route('payment.store') }}" method="POST" id="checkout-form">
                    @csrf
                    {{-- <input type="hidden" name="billing_plan_id" value="{{ $plan->id }}"> --}}
                    <input type="hidden" name="payment-method" id="payment-method" value="">
                   
                    <input id="card-holder-name" type="text" width="50%" class="form-control" placeholder="Card Holder Name">
                    <br>
                    <!-- Stripe Elements Placeholder -->
                    <div id="card-element"></div>
                    <input type="checkbox" name="default" id="" value="1"> Make as default
                   <button id="card-button" data-secret="{{ $intent->client_secret }}">
                        Add
                    </button>
                   </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://js.stripe.com/v3/"></script>
<script>
  $( document ).ready(function() {
    let stripe = Stripe("{{ env('STRIPE_KEY') }}")
    let elements = stripe.elements()
    let style = {
      base: {
        color: '#32325d',
        fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
        fontSmoothing: 'antialiased',
        fontSize: '16px',
        '::placeholder': {
          color: '#aab7c4'
        }
      },
      invalid: {
        color: '#fa755a',
        iconColor: '#fa755a'
      }
    }
    let card = elements.create('card', {style: style})
    card.mount('#card-element')
    let paymentMethod = null
    $('#checkout-form').on('submit', function (e) {
      $('#card-button').prop('disabled',true);
      if (paymentMethod) {
        return true
      }
      stripe.confirmCardSetup(
        "{{ $intent->client_secret }}",
        {
          payment_method: {
            card: card,
            billing_details: {name: $('#card-holder-name').val()}
          }
        }
      ).then(function (result) {
        if (result.error) {
          console.log(result)
          alert('error')
        } else {
          paymentMethod = result.setupIntent.payment_method
          $('#payment-method').val(paymentMethod)
          $('#checkout-form').submit()
        }
      })
      return false
    })
})
</script>

@endpush

