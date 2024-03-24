<script>
    const checkout = new window.YooMoneyCheckoutWidget({
        confirmation_token: '{{ $token }}',
        return_url: 'https://yookassa.ru/',
        error_callback: function(error) {
            console.log(error)
        }
    });

    checkout.render('payment-form');
</script>
