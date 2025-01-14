<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Helpers\CriptografiaHelper;
use App\Models\Student;

class SSOController extends Controller
{

    private $secretKey;

    public function __construct()
    {
        $this->secretKey = env('SSO_SECRET_KEY');
    }

    public function authenticate(Request $request)
    {
        // Recebe o parâmetro 'data' criptografado
        $encryptedData = $request->input('data');

        if (!$encryptedData) {
            return response()->json(['error' => 'Parâmetro data não encontrado'], 400);
        }
        // Descriptografa os dados usando a chave secreta
        try {
            $decryptedData = CriptografiaHelper::decryptData($encryptedData, "{$this->secretKey}");
            $userData = $decryptedData;
        } catch (\Exception $e) {
            return response()->json(['error' => 'Falha na descriptografia'], 400);
        }

        // Verifica se o payload contém os campos necessários
        if (!isset($userData['email'])) {
            return response()->json(['error' => 'Email não fornecido'], 400);
        }
        $student = Student::where('email', $userData['email'])->first();
        //dd($student);
        if (!$student) {
            return response()->json(['error' => 'Aluno nao encontrado'], 400);
        }

        // Gera um sessionId temporário (UUID)
        $sessionId = Str::uuid()->toString();

        // Armazena o sessionId no cache com tempo de expiração de 1 minutos
        Cache::put("session_{$sessionId}", $student->id, 60);

        // Retorna a URL de verificação com o sessionId
        $result = CriptografiaHelper::encryptData(
            ['sessionId' => $sessionId, 'email' => $student->email],
            $this->secretKey
        );
        $verificationUrl = route('sso.verify', [
            'data' => urlencode($result)
        ]);

        // Retorna a URL de verificação com o sessionId
        return response()->json(['success' => true, 'url' => $verificationUrl]);
    }

    public function verify(Request $request)
    {
        // Recebe o parâmetro 'data' criptografado na URL
        $encryptedData = $request->query('data');
        if (!$encryptedData) {
            return response()->json(['error' => 'Parâmetro data não encontrado'], 400);
        }
        // Descriptografa os dados usando a chave secreta
        try {
            $encryptedData = urldecode($encryptedData);
            $decryptedData = CriptografiaHelper::decryptData($encryptedData, "{$this->secretKey}");
            $sessionData = $decryptedData;
        } catch (\Exception $e) {
            return response()->json(['error' => 'Falha na descriptografia'], 400);
        }

        // Verifica se tem sessionId
        if (!isset($sessionData['sessionId'])) {
            return response()->json(['error' => 'Session ID não encontrado'], 400);
        }

        $sessionId = $sessionData['sessionId'];
        $userId = Cache::get("session_{$sessionId}");
    
        if (!$userId) {
            return response()->json(['error' => 'Session ID expirado ou inválido'], 400);
        }

        $student = Student::find($userId);

        if (!$student) {
            return response()->json(['error' => 'Aluno nao encontrado'], 400);
        }
       

        //autenticar usuário
        Auth::login($student->user);

        // Limpa o sessionId do cache para evitar reutilização
        Cache::forget("session_{$sessionId}");
        return redirect('/home');
        //return response()->json(['success' => true, 'student' => $student]);
    }

    public function teste()
    {
        $x = CriptografiaHelper::encryptData(
            ['nome' => "fulano", 'email' => 'fulano@gmail.br'],
            $this->secretKey
        );
        return $x;
    }
}
