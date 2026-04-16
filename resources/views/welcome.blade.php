<x-layouts::app1 :title="__('Inicio')">
    <div class="max-w-4xl mx-auto py-8">
        <!-- Título -->
        <flux:heading size="xl" class="mb-6">
            Últimos posts
        </flux:heading>
        <!-- Lista -->
        <div class="space-y-6">

            <!-- POST EJEMPLO -->
            @foreach ($posts as $post)
                <flux:card class="overflow-hidden">

                    <!-- Imagen -->
                    <img src="{{ Storage::url($post->imagen) }}" class="w-full h-48 object-cover" alt="Imagen del post">

                    <div class="p-5 space-y-4">

                        <!-- Categoría + Fecha -->
                        <div class="flex items-center justify-between text-sm text-gray-500">

                            <div class="flex items-center gap-2">
                                <flux:icon name="tag" class="w-4 h-4" />
                                <span @class([
                                    'font-semibold',
                                    'text-green-600'=>$post->categoria=='Software',
                                    'text-red-600'=>$post->categoria!='Software',
                                ])
                                >{{ $post->categoria }}</span>
                            </div>

                            <div class="flex items-center gap-2">
                                <flux:icon name="calendar" class="w-4 h-4" />
                                <span class="text-blue-500">{{ $post->updated_at->format('d/m/Y h:i:s') }}</span>
                            </div>

                        </div>

                        <!-- Título -->
                        <flux:heading size="lg">
                            {{ $post->titulo }}
                        </flux:heading>

                        <!-- Contenido -->
                        <flux:text>
                            {{ $post->contenido }}
                        </flux:text>

                        <!-- Footer -->
                        <div class="flex items-center justify-between pt-4 border-t text-sm text-gray-500">

                            <div class="flex items-center gap-2">
                                <flux:icon name="user" class="w-4 h-4" />
                                <span class="italic font-semibold text-purple-500">{{ $post->user->email }}</span>
                            </div>



                        </div>

                    </div>

                </flux:card>
            @endforeach
            <!-- FIN POST -->

        </div>
    </div>
    </x-layouts::app>
