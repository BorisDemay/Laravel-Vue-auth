@extends('layouts.admin')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Commandes</h2>
        </div>

        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 bg-gray-50 text-left">Référence</th>
                    <th class="px-6 py-3 bg-gray-50 text-left">Email Client</th>
                    <th class="px-6 py-3 bg-gray-50 text-left">Prix Total</th>
                    <th class="px-6 py-3 bg-gray-50 text-left">Statut</th>
                    <th class="px-6 py-3 bg-gray-50 text-left">Date</th>
                    <th class="px-6 py-3 bg-gray-50 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($orders as $order)
                <tr>
                    <td class="px-6 py-4">{{ $order->uuid }}</td>
                    <td class="px-6 py-4">{{ $order->client_email }}</td>
                    <td class="px-6 py-4">{{ number_format($order->total_price, 2, ',', ' ') }} €</td>
                    <td class="px-6 py-4">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $order->status === 'completed' ? 'Complétée' : 'En attente' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('orders.show', $order->uuid) }}" 
                           class="text-blue-600 hover:text-blue-900">
                            Détails
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection 