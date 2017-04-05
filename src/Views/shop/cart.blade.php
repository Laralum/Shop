{{--

In this file you have the following variables available:

$items - It stores the currently items in the shipping card.
    Example Format:
    [
        [
            'item' => ItemObject1, // Imagine it's price is 5
            'ammount' => 2,
            'price' => 10,
        ],
        [
            'item' => ItemObject2, // Imagine it's price is 2
            'ammount' => 1,
            'price' => 2,
        ],
    ]
$price - Stores the price in cents without taxes
$tax - Stores the tax ammount in cents
$totalPrice - Stores the addition of tax and price in cents.
$payments - It stores the payments settings of the laralum payments module.
$app_settings - It stores the application settings of the laralum settings module.
$settings - It stores the shop settings.

--}}
<!DOCTYPE html>
<html>
<head>
    <title>Cart Example</title>
</head>
<body>
    <a href="{{ route('laralum_public::shop.index') }}">All Products</a>
    <a href="{{ route('laralum_public::shop.cart') }}">Shopping Card</a>
    <a href="{{ route('laralum_public::shop.orders') }}">My Orders</a>
    <h1>Shopping Card</h1>
    @if(session('success') or session('error'))
        <p>
            {{ session('success') ? session('success') : session('error') }}
        </p>
    @endif
    @foreach($items as $item)
        <h3>{{ $item['item']->name }}</h3>
        <p>
            Price: <span class="money">{{ $item['item']->price }}</span> * {{ $item['amount'] }} = <b class="money">{{ $item['price'] }}</b>
        </p>
        <p>
            <form action="{{ route('laralum_public::shop.cart.remove', ['item' => $item['item']->id]) }}" method="POST">
                {{ csrf_field() }}
                <input type="submit" value="Remove from cart"/>
            </form>
        </p>
    @endforeach
    <p>
        @if(count($items) > 0)
            <hr />
            <p>
                Taxes: <b class="money">{{ $tax/100 }}</b>
            </p>
            <p>
                Total to pay: <span class="money">{{ $tax/100 }}</span> + <span class="money">{{ $price/100 }}</span> = <b class="money">{{ $totalPrice/100 }}</b>
            </p>
            @if(Auth::check())
                @if(!$settings->emergency)
                    <form action="{{ route('laralum_public::shop.cart.checkout') }}" method="POST">
                        {{ csrf_field() }}
                        <script
                            src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                            data-key="{{ decrypt($payments->stripe_key) }}" {{-- The key needs to be decrypted first --}}
                            data-amount="{{ $totalPrice }}"
                            data-name="{{ $app_settings->appname }} Store"
                            data-description="Enter the following data to continue"
                            data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
                            data-locale="auto"
                            data-zip-code="true"
                            data-shipping-address="true"
                            data-email="{{ Auth::user()->email }}"
                            data-currency="eur">
                        </script>
                    </form>
                @else
                    <p>
                        @lang('laralum_shop::general.emergency_on');
                    </p>
                @endif
            @else
                <p>
                    <a href="{{ route('login') }}">Login to continue</a>
                </p>
            @endif
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
