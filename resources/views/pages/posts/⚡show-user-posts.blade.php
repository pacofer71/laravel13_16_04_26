<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Stevebauman\Purify\Facades\Purify;
use Livewire\Attributes\On;

new class extends Component {
    use WithPagination;
    public string $buscar = '';
    public string $campo = 'id';
    public string $orden = 'desc';
    public ?Post $post=null;

    public function ordenar(string $campo)
    {
        $this->orden = $this->orden == 'desc' ? 'asc' : 'desc';
        $this->campo = $campo;
    }
    public function updatingBuscar()
    {
        $this->resetPage();
    }
    public function with(): array
    {
        $posts = Post::with('user')
            ->where('user_id', Auth::id())
            ->where(function ($q) {
                $q->where('titulo', 'like', "%{$this->buscar}%")
                    ->orWhere('contenido', 'like', "%{$this->buscar}%")
                    ->orWhere('categoria', 'like', "%{$this->buscar}%")
                    ->orWhere('estado', 'like', "%{$this->buscar}%");
            })
            ->orderBy($this->campo, $this->orden)
            ->paginate(5);
        return compact('posts');
    }

    public function edit(Post $post){
        $this->authorize('update', $post);
        return redirect()->route('posts.edit', $post);
    }
    public function confirmarBorrar(Post $post){
        $this->authorize('delete', $post);
        $this->post=$post;
        //dd($post);
        $this->dispatch('evtBorrar');
    }

    #[On('evtBorrarOk')]
    public function borrar(){
        //$this->authorize('delete', $post);
        $this->post->delete();
    }
    public function cambiarEstado(Post $post){
        $this->authorize('update', $post);
        $estado=$post->estado=='Publicado' ? 'Borrador' : 'Publicado';
        $post->update(compact('estado'));
    }
};
?>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-2">

    <!-- Header -->
    <h2 class="text-lg font-semibold text-gray-800 text-center my-4">
        Posts
    </h2>
    <div class="flex w-full justify-between mb-2 p-2">
        <div class="w-1/2">
            <flux:input icon="magnifying-glass" wire:model.live="buscar" />
        </div>
        <a href="{{route('posts.create')}}" class="font-semibold inline-flex items-center gap-1 p-2 bg-gray-600 hover:bg-gray-800 text-white rounded-lg whitespace-nowrap">
            <x-heroicon-o-plus class="w-4 h-4" />
            NUEVO
        </a>

    </div>
    @if($posts->count())
    <!-- Tabla -->
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left">

            <!-- THEAD -->
            <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3 font-medium">
                        <div class="flex items-center justify-center cursor-pointer hover:text-gray-900"
                            wire:click="ordenar('titulo')">
                            <span>Título</span>
                            <x-heroicon-o-chevron-up-down class="ml-1 w-4 h-4" />
                        </div>
                    </th>
                    <th class="px-6 py-3 font-medium">
                        <div class="flex items-center justify-center cursor-pointer hover:text-gray-900"
                            wire:click="ordenar('contenido')">
                            <span>Contenido</span>
                            <x-heroicon-o-chevron-up-down class="ml-1 w-4 h-4" />
                        </div>
                    </th>
                    <th class="px-6 py-3 font-medium">
                        <div class="flex items-center justify-center cursor-pointer hover:text-gray-900"
                            wire:click="ordenar('categoria')">
                            <span>Categoría</span>
                            <x-heroicon-o-chevron-up-down class="ml-1 w-4 h-4" />
                        </div>
                    </th>
                    <th class="px-6 py-3 font-medium">
                        <div class="flex items-center justify-center cursor-pointer hover:text-gray-900"
                            wire:click="ordenar('estado')">
                            <span>Estado</span>
                            <x-heroicon-o-chevron-up-down class="ml-1 w-4 h-4" />
                        </div>
                    </th>
                    <th class="px-6 py-3 font-medium">
                        <div class="flex items-center justify-center cursor-pointer hover:text-gray-900"
                            wire:click="ordenar('updated_at')">
                            <span>Fecha</span>
                            <x-heroicon-o-chevron-up-down class="ml-1 w-4 h-4" />
                        </div>
                    </th>
                    <th class="px-6 py-3 font-medium text-right">Acciones</th>
                </tr>
            </thead>

            <!-- TBODY -->
            <tbody class="divide-y divide-gray-100">

                @foreach ($posts as $post)
                    <tr class="hover:bg-gray-50 transition">

                        <!-- Post -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">

                                <img src="{{ Storage::url($post->imagen) }}" class="w-10 h-10 rounded-lg object-cover">

                                <div>
                                    <p class=" text-gray-800">
                                        {{ $post->titulo }}
                                    </p>
                                </div>

                            </div>
                        </td>
                        <!-- Contenido -->
                        <td class="px-6 py-4 italic text-gray-600">
                            {!! Purify::clean($post->contenido) !!}
                        </td>

                        <!-- Categoría -->
                        <td class="px-6 py-4 font-semibold">
                            <span @class([
                                'text-purple-500' => $post->categoria == 'Hardware',
                                'text-orange-500' => $post->categoria == 'Software',
                            ])>
                                {{ $post->categoria }}
                            </span>
                        </td>

                        <!-- Estado -->
                        <td class="px-6 py-4 cursor-pointer" wire:click="cambiarEstado({{$post}})">
                            <span @class([
                                'inline-flex items-center px-2.5 py-1 rounded-full text-xs',
                                'bg-green-100 text-green-700' => $post->estado == 'Publicado',
                                'bg-red-100 text-red-700' => $post->estado == 'Borrador',
                            ])>
                                {{ $post->estado }}

                            </span>
                        </td>
                        <!-- Fecha -->
                        <td class="px-6 py-4 italic text-sm whitespace-nowrap text-gray-500">
                            <div class="flex flex-col gap-2 justify-between">
                                <div>
                                    <span class="font-semibold">Creado:</span> {{ $post->created_at->format('d/m/Y') }}
                                </div>
                                <div>
                                    <span class="font-semibold">Editado:</span> {{ $post->updated_at->format('d/m/Y') }}
                                </div>
                            </div>
                        </td>

                        <!-- Acciones -->
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">

                                <!-- Editar -->
                                <button wire:click="edit({{ $post }})"
                                    class="p-2 rounded-lg hover:bg-blue-50 text-blue-600 transition">
                                    <x-heroicon-o-pencil class="w-4 h-4" />
                                </button>

                                <!-- Eliminar -->
                                <button wire:click="confirmarBorrar({{ $post }})"
                                    class="p-2 rounded-lg hover:bg-red-50 text-red-600 transition">
                                    <x-heroicon-o-trash class="w-4 h-4" />
                                </button>

                                <!-- Ver -->
                            </div>
                        </td>

                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
    <div class="mt-2">
        {{ $posts->links() }}
    </div>
    @else
    No se encontró Nada&nbsp;
    <flux:button icon="x-circle" variant="primary" color="amber" wire:click="set('buscar', '')">Restablecer Búsqueda</flux:button>
    @endif
</div>
