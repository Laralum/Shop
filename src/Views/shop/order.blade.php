{{--

In this file you have the following variables available:

$order - It stores the selected order as the model instance.

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
    <h1>Order #{{ $order->id }}</h1>
    <ul>
        @foreach($order->items as $item)
            <li>
                {{ $item->name }} - {{ $item->pivot->units }}
                bought for {{ unserialize($item->pivot->item_on_buy)['price'] }} each
                with a total price of:
                <b>{{ bcmul(unserialize($item->pivot->item_on_buy)['price'], $item->pivot->units, 2) }}</b>
            </li>
        @endforeach
    </ul>
    Total order price: <b>{{ $order->price() }}</b>

</body>
</html>
