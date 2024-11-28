<!DOCTYPE html>
<html>
<head>
    <title>Stripe Checkout</title>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
    <form action="/charge" method="POST" id="payment-form">
        @csrf
        <div id="card-element"></div>
        <button id="submit">Submit Payment</button>
    </form>

    <script>
        var stripe = Stripe('{{ $intentClientSecret }}');
        var elements = stripe.elements();
        var cardElement = elements.create('card');
        cardElement.mount('#card-element');

        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            stripe.confirmCardPayment('{{ $intentClientSecret }}', {
                payment_method: {
                    card: cardElement,
                    billing_details: {
                        name: 'Jenny Rosen',
                    }
                }
            }).then(function(result) {
                if (result.error) {
                    // Handle error
                } else {
                    // Handle success
                    window.location.href = '/success';
                }
            });
        });
    </script>
</body>
</html>
