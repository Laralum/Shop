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
    <div id="order">
        <h1>Order #{{ $order->id }}</h1>
        @if ($order->shipping_adress)
            <h4>Shipping adress</h4>
                {{ $order->shipping_adress }},
                {{ $order->shipping_zip }},
                {{ $order->shipping_city }},
                {{ $order->shipping_country }}<br />
        @endif
        <h4>Items</h4>
        <table style="width: 100%">
            <tr>
                <th>Item</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Subtotal</th>
            </tr>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->pivot->units }}</td>
                    <td><span class="money">{{ unserialize($item->pivot->item_on_buy)['price'] }}</span></td>
                    <td><span class="money">{{ bcmul(unserialize($item->pivot->item_on_buy)['price'], $item->pivot->units, 2) }}</span></td>
                </tr>
            @endforeach
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td><b>Total</b></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td><span class="money">{{ $order->totalPrice() }}</span></td>
            </tr>
        </table>
        <br />
        <p>
            Total order price: <b class="money">{{ $order->totalPrice() }}</b>
        </p>
    </div>
    <p>
        <form action="{{ route('laralum_public::pdf.download', ['name' => 'Order_'.$order->id]) }}" method="POST" id="downloadInvoice">
            {{ csrf_field() }}
            <input type="hidden" name="text" id="text" value="asd"/>
            <input type="submit" value="Download Invoice" />
        </form>
    </p>
    <script src="https://cdn.bootcss.com/currencyformatter.js/1.0.4/currencyFormatter.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.0/jquery.min.js" integrity="sha256-JAW99MJVpJBGcbzEuXk4Az05s/XyDdBomFqNlM3ic+I=" crossorigin="anonymous"></script>
    <script>
        OSREC.CurrencyFormatter.formatAll({
            selector: '.money',
            currency: '{{ \Laralum\Shop\Models\Settings::first()->currency }}'
        });
        $('.money').each(function() {
            $(this).html('<i>'+$(this).html()+'</i>');
        });
        $('table').each(function() {
            $(this).css({'border': '1px solid black', 'border-collapse': 'collapse'});
        });
        $('th').each(function() {
            $(this).css({'border': '1px solid black', 'border-collapse': 'collapse', 'padding': '5px'});
        });
        $('td').each(function() {
            $(this).css({'padding': '5px'});
            console.log($(this).html());
            if($(this).text()) {
                $(this).css({'border': '1px solid black', 'border-collapse': 'collapse'});
            }
        });
        $('#downloadInvoice').submit(function() {
            var content = $('#order').html();
            $('#text').attr('value', content);
        });
    </script>
</body>
</html>
