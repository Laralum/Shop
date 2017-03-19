{{--

In this file you have the following variables available:

$orders - It stores the current logged in user orders as the model instance.

--}}
<!DOCTYPE html>
<html>
<head>
    <title>Orders Example</title>
</head>
<body>
    <a href="{{ route('laralum_public::shop.index') }}">All Products</a>
    <a href="{{ route('laralum_public::shop.cart') }}">Shopping Card</a>
    <a href="{{ route('laralum_public::shop.orders') }}">My Orders</a>
    <h1>My Orders</h1>
    @if(session('success') or session('error'))
        <p>
            {{ session('success') ? session('success') : session('error') }}
        </p>
    @endif
    @foreach($orders as $order)
        <h3>Order #{{ $order->id }}</h3>
        Total order price: <b class="money">{{ $order->price() }}</b> -
        <a href="{{ route('laralum_public::shop.order', ['order' => $order->id]) }}">More details</a>
    @endforeach
    <script src="https://cdn.bootcss.com/currencyformatter.js/1.0.4/currencyFormatter.min.js"></script>
    <script>
        OSREC.CurrencyFormatter.formatAll({
            selector: '.money',
            currency: 'EUR'
        });
    </script>
</body>
</html>
