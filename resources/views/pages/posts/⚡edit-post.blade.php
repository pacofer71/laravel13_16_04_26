<?php

use Livewire\Component;
use App\Models\Post;
use Livewire\WithFileUploads;
use App\Livewire\Forms\Posts\EditPostForm;



new class extends Component {
    //public Post $post;
    use WithFileUploads;
    public EditPostForm $eform;
    public function mount(Post $post)
    {
        $this->post = $post;
        $this->eform->setPost($post);
    }
    public function cancelar(){
        $this->eform->resetForm();
        return redirect()->route('posts.show');
    }
    public function update(){
        $this->authorize('update', $this->eform->post);
        $this->eform->editPostForm();
        $this->cancelar();
    }
};
?>

<div class="p-8 bg-white rounded-lg shadow-lg">
    <h2 class="text-lg font-semibold text-gray-800 text-center my-4">
        Editar Post
    </h2>
    <!-- Título -->
    <flux:input label="Título" placeholder="Título..." wire:model="eform.titulo" />
    <!-- Contenido -->
    <flux:label class="mt-4">Contenido</flux:label>
    <div wire:ignore x-data x-on:trix-change="$wire.set('eform.contenido', $refs.trix.value, false)" class="mb-4">
        <input id="mi_contenido" type="hidden" name="contenido" value="{{$eform->contenido}}" />
        <trix-editor x-ref="trix" input="mi_contenido" class="min-h-96"></trix-editor>
    </div>
    @error('eform.contenido')
        <div class="mt-1 text-sm text-red-500 font-medium">{{ $message }}</div>
    @enderror
    <!-- Categoria -->
    <flux:radio.group wire:model="eform.categoria" label="Categoría" variant="cards" class="w-96">
        <flux:radio value="Hardware" label="Hardware" />
        <flux:radio value="Software" label="Software" />
    </flux:radio.group>
    <div class="mb-4"></div>
    <!-- estado -->
    <flux:radio.group wire:model="eform.estado" label="Estado" variant="cards" class="w-96">
        <flux:radio value="Publicado" label="Publicado" />
        <flux:radio value="Borrador" label="Borrador" />
    </flux:radio.group>
    <!-- Imagen -->
    <flux:label class="mt-4">Imagen</flux:label>
    <div class="mt-4 w-full h-96 relative rounded-lg bg-gray-200">
        <input type="file" accept="image/*" wire:model="eform.imagen" class="hidden" id="eimagen" />
        <label
            class="flex items-center absolute bottom-2 right-2 p-2 rounded-xl bg-gray-600 hover:bg-gray-800 text-white font-semibold"
            for="eimagen">
            <x-heroicon-o-photo class="w-5 h-5 mr-2" /><span>SUBIR</span>
        </label>
        @if ($eform->imagen)
            <img src="{{ $eform->imagen->temporaryUrl() }}" class="h-full w-auto object-contain mx-auto" />
        @else
            <img src="{{ Storage::url($eform->post->imagen) }}" class="h-full w-auto object-contain mx-auto" />
        @endif
    </div>
    @error('eform.imagen')
        <div class="mt-1 text-sm text-red-500 font-medium">{{ $message }}</div>
    @enderror
    <!-- Botones -->
    <div class="mt-4 w-full flex flex-row-reverse gap-2">
        <flux:button icon="paper-airplane" variant="primary" wire:click="update" wire:loading.attr="disabled">EDITAR</flux:button>
        <flux:button icon="x-mark" variant="danger" wire:click="cancelar">CANCELAR</flux:button>
    </div>

</div>
