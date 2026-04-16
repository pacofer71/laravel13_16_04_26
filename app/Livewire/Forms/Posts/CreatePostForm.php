<?php

namespace App\Livewire\Forms\Posts;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Form;


class CreatePostForm extends Form
{
    #[Validate(['required', 'string', 'min:3', 'max:60'])]
    public string $titulo="";

    #[Validate(['required', 'string', 'min:10', 'max:250'])]
    public string $contenido="";

    #[Validate(['required', 'in:Hardware,Software'])]
    public string $categoria="";

    #[Validate(['required', 'in:Publicado,Borrador'])]
    public string $estado="Borrador";

    #[Validate(['nullable', 'image', 'max:2048'])]
    public ?TemporaryUploadedFile $imagen=null;

    public function createPostForm(){
        $datos=$this->validate();
        $datos['imagen']=$this->imagen?->store('imagenes/posts') ?? 'imagenes/posts/noimage.jpg';
        $datos['user_id']=Auth::id();
        Post::create($datos);
    }
    public function resetForm(){
        $this->resetValidation();
        $this->reset();
    }

}
