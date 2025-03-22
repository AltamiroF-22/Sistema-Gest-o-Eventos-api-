<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function store(Request $request) {
        try {
            // Validando os dados de entrada e definindo mensagens personalizadas
            $validatedData = $request->validate([
                'name' => 'required|string|min:4|max:100',  // Nome deve ser uma string entre 4 e 100 caracteres
                'email' => 'required|string|email|unique:users,email',  // Email deve ser único e válido
                'password' => 'required|string|min:6',  // Senha deve ter pelo menos 6 caracteres
                'role' => 'nullable|in:participant,organizer',  // O valor de 'role' deve ser 'participant' ou 'organizer', mas é opcional
            ], [
                // Mensagens de erro personalizadas
                'name.required' => 'O nome é obrigatório.',
                'name.string' => 'O nome deve ser uma string válida.',
                'name.min' => 'O nome deve ter pelo menos 4 caracteres.',
                'name.max' => 'O nome não pode exceder 100 caracteres.',
                
                'email.required' => 'O email é obrigatório.',
                'email.string' => 'O email deve ser uma string válida.',
                'email.email' => 'O email deve ser um endereço de email válido.',
                'email.unique' => 'Este email já está em uso.',
                
                'password.required' => 'A senha é obrigatória.',
                'password.string' => 'A senha deve ser uma string válida.',
                'password.min' => 'A senha deve ter pelo menos 6 caracteres.',
                
                'role.in' => 'O campo role deve ser "participant" ou "organizer".',
            ]);

            // Atribuindo o valor padrão de 'role' se não for fornecido
            // Se o campo 'role' não for enviado, será atribuído automaticamente o valor 'participant'
            $validatedData['role'] = $validatedData['role'] ?? 'participant';  

            // Criando o usuário com os dados validados
            // A senha é criptografada usando o Hash do Laravel
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),  // Criptografando a senha
                'role' => $validatedData['role'],
            ]);

            // Retornando a resposta com o status de sucesso e os dados do usuário criado
            return response()->json([
                'status' => true,
                'message' => 'Usuário criado com sucesso',
                'user' => $user
            ], 201);

        } catch (ValidationException $e) {
            // Caso ocorra algum erro de validação, retorna um erro com as mensagens de validação
            return response()->json([
                'status' => false,
                'message' => 'Erro de validação.',
                'errors' => $e->errors()  // Captura e retorna os erros de validação
            ], 422);
        }
    }
}