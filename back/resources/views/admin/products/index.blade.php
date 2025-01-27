@extends('layouts.admin')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Produits</h2>
            <a href="{{ route('products.create') }}" 
               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Ajouter un produit
            </a>
        </div>

        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 bg-gray-50 text-left">Nom</th>
                    <th class="px-6 py-3 bg-gray-50 text-left">Prix</th>
                    <th class="px-6 py-3 bg-gray-50 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($products as $product)
                <tr>
                    <td class="px-6 py-4">{{ $product->name }}</td>
                    <td class="px-6 py-4">{{ number_format($product->price, 2, ',', ' ') }} €</td>
                    <td class="px-6 py-4">
                        <a href="{{ route('products.edit', $product) }}" 
                           class="text-blue-600 hover:text-blue-900 mr-3">Modifier</a>
                        
                        <form action="{{ route('products.destroy', $product) }}" 
                              method="POST" 
                              class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="text-red-600 hover:text-red-900"
                                    onclick="return confirm('Êtes-vous sûr ?')">
                                Supprimer
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection 