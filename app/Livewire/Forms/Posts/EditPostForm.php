<?php

namespace App\Livewire\Forms\Posts;

use App\Models\Post;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;


class EditPostForm extends Form
{
    public ?Post $post=null;

    #[Validate(['required', 'string', 'min:3', 'max:60'])]
    public string $titulo="";

    #[Validate(['required', 'string', 'min:10', 'max:250'])]
    public string $contenido="";

    #[Validate(['required', 'in:Hardware,Software'])]
    public string $categoria="";

    #[Validate(['required', 'in:Publicado,Borrador'])]
    public string $estado="";

    #[Validate(['nullable', 'image', 'max:2048'])]
    public ?TemporaryUploadedFile $imagen=null;

    public function setPost(Post $post): void{
        $this->post=$post;
        $this->titulo=$post->titulo;
        $this->contenido=$post->contenido;
        $this->categoria=$post->categoria;
        $this->estado=$post->estado;
    }
    public function editPostForm(){
        $datos=$this->validate();
        if($this->imagen){
            $datos['imagen']=$this->imagen->store('imagenes/posts');
        }else{
            unset($datos['imagen']);
        }
        $this->post->update($datos);
    }


    public function resetForm(){
        $this->resetValidation();
        $this->reset();
    }
}
