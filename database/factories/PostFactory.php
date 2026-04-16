<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        fake()->addProvider(new \Mmo\Faker\PicsumProvider(fake()));
        $users=User::all();
        return [
            'titulo'=>fake()->realText(60),
            'contenido'=>fake()->realText(),
            'estado'=>fake()->randomElement(['Publicado', 'Borrador']),
            'categoria'=>fake()->randomElement(['Hardware', 'Software']),
            'user_id'=>$users->random()->id,
            'imagen'=>'imagenes/posts/'.fake()->picsum(dir: 'public/storage/imagenes/posts', width: 640, height: 480, fullPath: false, imageExtension: 'jpg'),
        ];
    }
}
