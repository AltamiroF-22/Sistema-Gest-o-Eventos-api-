<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{

    public function registerEvent(Event $event)
    {
        // Verifica se o usuário está autenticado
        if (Auth::check()) {
            // Pega o ID do usuário autenticado
            $userId = Auth::id();
    
            // Verifica se o usuário já está inscrito no evento
            $existingRegistration = Registration::where('user_id', $userId)
                                                 ->where('event_id', $event->id)
                                                 ->first();
    
            // Se o usuário já estiver inscrito, retorna uma resposta informando
            if ($existingRegistration) {
                return response()->json([
                    'status' => false,
                    'message' => 'Você já está inscrito neste evento.'
                ], 400);
            }
    
            // Cria a inscrição do usuário no evento
            $registration = Registration::create([
                'user_id' => $userId,
                'event_id' => $event->id,
            ]);
    
            // Retorna uma resposta indicando que a inscrição foi bem-sucedida
            return response()->json([
                'status' => true,
                'message' => 'Você foi inscrito no evento com sucesso.',
                'registration' => $registration
            ], 200);
        }
    
        // Caso o usuário não esteja autenticado
        return response()->json([
            'status' => false,
            'message' => 'Usuário não autenticado'
        ], 401);
    }

    public function unsubEvent(Event $event)
    {
        if (Auth::check()) {
            $userId = Auth::id();
    
            // Verifica se o usuário já está inscrito no evento
            $existingRegistration = Registration::where('user_id', $userId)
                                                 ->where('event_id', $event->id)
                                                 ->first();
    
            // Se o usuário não estiver inscrito, retorna uma resposta informando
            if (!$existingRegistration) {
                return response()->json([
                    'status' => false,
                    'message' => 'Você não está inscrito neste evento.'
                ], 400);
            }
    
            // Exclui a inscrição do evento
            $existingRegistration->delete();
    
            // Retorna uma resposta indicando que a inscrição foi cancelada com sucesso
            return response()->json([
                'status' => true,
                'message' => 'Você cancelou sua inscrição no evento com sucesso.',
            ], 200);
        }
    
        // Caso o usuário não esteja autenticado
        return response()->json([
            'status' => false,
            'message' => 'Usuário não autenticado.'
        ], 401);
    }
    
    

    /**
     * Retorna todos os eventos de um usuário específico.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserEvents()
    {
        // Verifica se o usuário está autenticado
        if (Auth::check()) {
            // Pega o ID do usuário autenticado
            $userId = Auth::id();

            // Pega todos os eventos criados pelo usuário
            $events = Event::where('organizer_id', $userId)->paginate(10);

            // Retorna os eventos encontrados
            return response()->json([
                'status' => true,
                'events' => $events
            ], 200);
        }

        // Caso o usuário não esteja autenticado
        return response()->json([
            'status' => false,
            'message' => 'Usuário não autenticado'
        ], 401);
    }

    /**
     * Retorna todos os eventos que um usuário está inscrito.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserRegistrations()
    {
        // Verifica se o usuário está autenticado
        if (Auth::check()) {
            // Pega o ID do usuário autenticado
            $userId = Auth::id();
    
            // Pega todos os eventos onde o usuário está inscrito, com paginação
            $events = Registration::where('user_id', $userId)
                                  ->with('event')  // Carrega o evento relacionado
                                  ->paginate(10);  // Aplica paginação nos registros de inscrição
    
            // Retorna os eventos aos quais o usuário está inscrito
            return response()->json([
                'status' => true,
                'events' => $events
            ], 200);
        }
    
        // Caso o usuário não esteja autenticado
        return response()->json([
            'status' => false,
            'message' => 'Usuário não autenticado'
        ], 401);
    }
    
}