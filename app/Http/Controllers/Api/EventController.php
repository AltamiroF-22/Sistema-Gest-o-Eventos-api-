<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class EventController extends Controller
{
    
    public function store(Request $request)
    {
        try {
            // Verifica se o usuário está autenticado
            if (!Auth::check()) {
                return response()->json([
                    'error' => 'Usuário não autenticado'
                ], 401);
            }
        
            // Validando os dados de entrada e definindo mensagens personalizadas
            $validatedData = $request->validate([
                'title' => 'required|string|min:4|max:100',  // Título do evento
                'description' => 'required|string|min:10',  // Descrição do evento
                'date' => 'required|date',  // Data do evento
                'location' => 'required|string|min:3|max:255',  // Local do evento
                'main_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validação de imagem
            ], [
                // Mensagens de erro personalizadas
                'title.required' => 'O título é obrigatório.',
                'title.string' => 'O título deve ser uma string válida.',
                'title.min' => 'O título deve ter pelo menos 4 caracteres.',
                'title.max' => 'O título não pode exceder 100 caracteres.',
                
                'description.required' => 'A descrição é obrigatória.',
                'description.string' => 'A descrição deve ser uma string válida.',
                'description.min' => 'A descrição deve ter pelo menos 10 caracteres.',
                
                'date.required' => 'A data do evento é obrigatória.',
                'date.date' => 'A data fornecida não é válida.',
                
                'location.required' => 'O local do evento é obrigatório.',
                'location.string' => 'O local deve ser uma string válida.',
                'location.min' => 'O local deve ter pelo menos 3 caracteres.',
                'location.max' => 'O local não pode exceder 255 caracteres.',
                
                'main_image.required' => 'A imagem principal do evento é obrigatória.',
                'main_image.image' => 'O arquivo enviado deve ser uma imagem.',
                'main_image.mimes' => 'A imagem deve ser dos tipos: jpeg, png, jpg, gif, svg.',
                'main_image.max' => 'A imagem não pode exceder 2MB.',
            ]);
        
            // Pega o ID do usuário logado
            $userId = Auth::id();
        
            // Armazenando a imagem principal
            $mainImagePath = $request->file('main_image')->store('events', 'public');
        
            // Gerar o link público da imagem
            $mainImageUrl = asset('storage/' . $mainImagePath);
        
            // Criando o evento com os dados validados e a imagem armazenada
            $event = Event::create([
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'date' => $validatedData['date'],
                'location' => $validatedData['location'],
                'organizer_id' => $userId,
                'main_image' => $mainImageUrl, // Armazenando o link da imagem
            ]);
        
            // Retornando a resposta com o status de sucesso e os dados do evento criado
            return response()->json([
                'status' => true,
                'message' => 'Evento criado com sucesso',
                'event' => $event
            ], 201);
        
        } catch (ValidationException $e) {
            // Caso ocorra algum erro de validação, retorna um erro com as mensagens de validação
            return response()->json([
                'status' => false,
                'message' => 'Erro de validação.',
                'errors' => $e->errors()  // Captura e retorna os erros de validação
            ], 422);
        } catch (\Exception $e) {
            // Caso ocorra outro erro inesperado
            return response()->json([
                'status' => false,
                'message' => 'Erro ao criar evento.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Exibe todos os eventos.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Pegando todos os eventos, ordenando por ID em ordem decrescente e com paginação
        $events = Event::orderBy("id", "DESC")->paginate(10);

        // Retornando os eventos com sucesso
        return response()->json([
            'status' => true,
            'message' => 'Eventos encontrados.',
            'events' => $events
        ], 200);
    }
}