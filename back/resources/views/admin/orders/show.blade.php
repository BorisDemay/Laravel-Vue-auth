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

        <div class="mt-6">
            <form action="{{ route('orders.update-status', $order->uuid) }}" method="POST" class="inline">
                @csrf
                @method('PATCH')
                <select name="status" class="rounded-md border-gray-300 shadow-sm">
                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Complétée</option>
                </select>
                <button type="submit" class="ml-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Mettre à jour le statut
                </button>
            </form>
        </div>

        <div class="mt-6">
            <form action="{{ route('orders.send-email', $order->uuid) }}" method="POST" class="space-y-4">
                @csrf

                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label for="message" class="block text-sm font-medium text-gray-700">Message de l'email</label>
                            <button type="button" 
                                    id="preview-toggle" 
                                    class="text-sm text-blue-600 hover:text-blue-800 px-3 py-1 rounded border border-blue-600">
                                Mode aperçu
                            </button>
                        </div>

                        <!-- Quill Editor Container -->
                        <div id="editor-container">
                            <textarea
                                id="message"
                                name="message"
                                rows="12"
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-b-md min-h-[300px]"
                                placeholder="Écrivez votre message ici (supporte le markdown)"
                            ></textarea>
                        </div>

                        <!-- Hidden input to store the Markdown content -->
                        <input type="hidden" name="message" id="message">

                        <!-- Preview Container -->
                        <div id="preview-container" class="hidden mt-1">
                            <div id="preview-content" class="prose max-w-none p-4 border rounded-md bg-white min-h-[300px]">
                            </div>
                        </div>
                    </div>

                    @error('message')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center space-x-4">
                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Envoyer par email
                    </button>
                </div>
            </form>
        </div>

        @if(session('error'))
            <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif
    </div>
</div>

@push('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/github-markdown-css@5.2.0/github-markdown.min.css">
@endpush

<style>
    .ql-editor {
        min-height: 200px;
    }
</style>

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Quill
    const quill = new Quill('#editor-container', {
        theme: 'snow',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
                ['blockquote'],                                   // blocks
                [{ 'header': 1 }, { 'header': 2 }],               // headers
                [{ 'list': 'ordered' }, { 'list': 'bullet' }],    // lists
                ['clean']                                         // remove formatting
            ]
        },
        placeholder: 'Écrivez votre message ici (supporte le markdown)',
    });

    const previewToggle = document.getElementById('preview-toggle');
    const editorContainer = document.getElementById('editor-container');
    const previewContainer = document.getElementById('preview-container');
    const previewContent = document.getElementById('preview-content');
    const messageInput = document.getElementById('message');
    let isPreviewMode = false;

    // Update the hidden input with HTML content
    function updateMessageInput() {
        const html = quill.root.innerHTML;
        messageInput.value = html;
    }

    // Update the preview
    function updatePreview() {
        const html = quill.root.innerHTML;
        previewContent.innerHTML = marked.parse(html);
    }

    // Toggle between editor and preview
    previewToggle.addEventListener('click', function() {
        isPreviewMode = !isPreviewMode;
        if (isPreviewMode) {
            updatePreview();
            editorContainer.classList.add('hidden');
            previewContainer.classList.remove('hidden');
            previewToggle.textContent = 'Mode édition';
        } else {
            editorContainer.classList.remove('hidden');
            previewContainer.classList.add('hidden');
            previewToggle.textContent = 'Mode aperçu';
        }
    });

    // Update the hidden input and preview when the editor changes
    quill.on('text-change', function() {
        updateMessageInput();
        if (isPreviewMode) {
            updatePreview();
        }
    });

    // Initial update
    updateMessageInput();
    updatePreview();
});
</script>
@endpush
@endsection