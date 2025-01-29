<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { padding: 20px; }
        .product { margin-bottom: 10px; }
        .total { margin-top: 20px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        @component('mail::message')

        # Votre commande #{{ $order->uuid }}

        {!! Str::markdown($customMessage) !!}

        @component('mail::table')
        | Produit | Quantité | Prix |
        |:--------|:---------|:-----|
        @foreach($order->products as $product)
        | {{ $product->name }} | {{ $product->pivot->quantity }} | {{ number_format($product->pivot->price, 2, ',', ' ') }} € |
        @endforeach
        @endcomponent

        **Total: {{ number_format($order->total_price, 2, ',', ' ') }} €**

        À bientôt !<br>
        {{ config('app.name') }}
        @endcomponent
    </div>
</body>
</html> 