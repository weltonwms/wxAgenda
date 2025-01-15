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
            return response()->json(['success'=>false, 'message' => 'Parâmetro data não encontrado'], 400);
        }
        // Descriptografa os dados usando a chave secreta
        try {
            $decryptedData = CriptografiaHelper::decryptData($encryptedData, "{$this->secretKey}");

        } catch (\Exception $e) {
            return response()->json(['success'=>false, 'message' => 'Falha na descriptografia'], 400);
        }

        // Verifica se o payload contém os campos necessários
        if (!isset($userData['email'])) {
            return response()->json(['success'=>false, 'message' => 'Email não fornecido'], 400);
        }
        $student = Student::where('email', $decryptedData['email'])->first();

        if (!$student) {
            return response()->json(['success'=>false, 'message' => 'Aluno nao encontrado'], 400);
        }

        // Gera um sessionId temporário (UUID)
        $sessionId = Str::uuid()->toString();

        // Armazena o sessionId no cache com tempo de expiração de 1 minutos
        Cache::put("session_{$sessionId}", $student->id, 60);

        //Encrypta Data de Response
        $encryptedDataResponse = CriptografiaHelper::encryptData(
            ['sessionId' => $sessionId, 'email' => $student->email],
            $this->secretKey
        );
        $verificationUrl = route('sso.verify', [
            'data' => urlencode($encryptedDataResponse)
        ]);

        // Retorna a URL de verificação com o sessionId
        return response()->json(['success' => true, 'url' => $verificationUrl]);
    }

    public function verify(Request $request)
    {
        // Recebe o parâmetro 'data' criptografado na URL
        $encryptedData = $request->query('data');
        if (!$encryptedData) {
            return response()->json(['success'=>false, 'message' => 'Parâmetro data não encontrado'], 400);
        }
        // Descriptografa os dados usando a chave secreta
        try {
            $encryptedData = urldecode($encryptedData);
            $decryptedData = CriptografiaHelper::decryptData($encryptedData, "{$this->secretKey}");           
        } catch (\Exception $e) {
            return response()->json(['success'=>false, 'message' => 'Falha na descriptografia'], 400);
        }
        // Verifica se tem sessionId
        if (!isset($decryptedData['sessionId'])) {
            return response()->json(['success'=>false, 'message' => 'Session ID não encontrado'], 400);
        }

        $sessionId = $decryptedData['sessionId'];
        $studentId = Cache::get("session_{$sessionId}");

        if (!$studentId) {
            return response()->json(['success'=>false, 'message' => 'Session ID expirado ou inválido'], 400);
        }
        $student = Student::find($studentId);
        if (!$student) {
            return response()->json(['success'=>false, 'message' => 'Aluno nao encontrado'], 400);
        }

        //autenticar usuário
        Auth::login($student->user);
        // Limpa o sessionId do cache para evitar reutilização
        Cache::forget("session_{$sessionId}");
        return redirect('/home');       
    }

    public function teste()
    {
        return response()->json(['success' => true, 'message' => "Teste"]);
    }
}
