{{--

In this file you have the following variables available:

$item - It stores the currently selected item as the model instance.
$payments - It stores the payments settings of the laralum payments module.
$settings - It stores the application settings of the laralum settings module.

--}}
<!DOCTYPE html>
<html>
<head>
    <title>Item Example: {{ $item->name }}</title>
</head>
<body>
    <a href="{{ route('laralum_public::shop.index') }}">All Products</a>
    <a href="{{ route('laralum_public::shop.cart') }}">Shopping Card</a>
    <a href="{{ route('laralum_public::shop.orders') }}">My Orders</a>
    <h1>{{ $item->name }}</h1>
    @if(session('success') or session('error'))
        <p>
            {{ session('success') ? session('success') : session('error') }}
        </p>
    @endif
    <p>
        Price: <b class="money">{{ $item->price }}</b>
        Stock: <b>{{ $item->showStock() }}</b>
    </p>
    <p>{{ $item->description }}</p>
    <p>
        @if($item->available())
            <form action="{{ route('laralum_public::shop.cart.add', ['item' => $item->id]) }}" method="POST">
                {{ csrf_field() }}
                <input name="amount" type="number" value="1" min="1" max="{{ $item->stock }}" /> {{-- If an amount field is submited it will multiply the number of this item added --}}
                <input type="submit" value="Add to cart"/>
            </form>
        @else
            <p>
                <i>No Stock left.</i>
            </p>
        @endif
    </p>

    <script src="https://cdn.bootcss.com/currencyformatter.js/1.0.4/currencyFormatter.min.js"></script>
    <script>
        OSREC.CurrencyFormatter.formatAll({
            selector: '.money',
            currency: '{{ \Laralum\Shop\Models\Settings::first()->currency }}'
        });
    </script>
</body>
</html>
