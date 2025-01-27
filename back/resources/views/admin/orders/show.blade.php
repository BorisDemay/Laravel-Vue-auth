@extends('layouts.admin')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Détails de la commande</h2>
            <a href="{{ route('orders.index') }}" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Retour
            </a>
        </div>

        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">Informations</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-600">Référence:</p>
                    <p class="font-medium">{{ $order->uuid }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Email Client:</p>
                    <p class="font-medium">{{ $order->client_email }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Statut:</p>
                    <p class="font-medium">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $order->status === 'completed' ? 'Complétée' : 'En attente' }}
                        </span>
                    </p>
                </div>
                <div>
                    <p class="text-gray-600">Date:</p>
                    <p class="font-medium">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">Produits</h3>
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left">Produit</th>
                        <th class="px-6 py-3 bg-gray-50 text-left">Quantité</th>
                        <th class="px-6 py-3 bg-gray-50 text-left">Prix unitaire</th>
                        <th class="px-6 py-3 bg-gray-50 text-left">Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($order->products as $product)
                    <tr>
                        <td class="px-6 py-4">{{ $product->name }}</td>
                        <td class="px-6 py-4">{{ $product->pivot->quantity }}</td>
                        <td class="px-6 py-4">{{ number_format($product->pivot->price, 2, ',', ' ') }} €</td>
                        <td class="px-6 py-4">{{ number_format($product->pivot->price * $product->pivot->quantity, 2, ',', ' ') }} €</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-right font-semibold">Total:</td>
                        <td class="px-6 py-4 font-semibold">{{ number_format($order->total_price, 2, ',', ' ') }} €</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection 