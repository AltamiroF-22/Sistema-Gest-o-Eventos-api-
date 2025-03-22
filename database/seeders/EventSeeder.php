<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use Illuminate\Support\Facades\Http;

class EventSeeder extends Seeder
{
    public function run()
    {
        // Criando 10 eventos
        foreach (range(1, 10) as $index) {
            // Gerando um título e descrição simples
            $title = "Evento #{$index} - Diversão e Conhecimento";
            $description = "Descrição do evento #{$index}, onde você vai aprender e se divertir muito!";

            // Buscando uma imagem grátis de acordo com o título/descrição usando uma URL fixa (você pode usar uma API para gerar dinamicamente)
            $imageUrl = $this->getRandomImageUrl();

            // Criando o evento
            Event::create([
                'title' => $title,
                'description' => $description,
                'date' => now()->addDays(rand(1, 30)),  // Data aleatória entre 1 e 30 dias
                'location' => 'Local do evento #'.$index,
                'organizer_id' => 1,  // Definindo o organizer_id como 1
                'main_image' => $imageUrl,  // A URL da imagem principal
                'other_images' => json_encode([$imageUrl, $imageUrl]),  // Adicionando outras imagens
            ]);
        }
    }

    /**
     * Função para retornar uma URL de imagem aleatória
     */
    private function getRandomImageUrl()
    {
        $images = [
            'https://source.unsplash.com/400x300/?event',
            'https://source.unsplash.com/400x300/?party',
            'https://source.unsplash.com/400x300/?conference',
            'https://source.unsplash.com/400x300/?music',
            'https://source.unsplash.com/400x300/?meeting'
        ];

        // Retorna uma imagem aleatória da lista
        return $images[array_rand($images)];
    }
}