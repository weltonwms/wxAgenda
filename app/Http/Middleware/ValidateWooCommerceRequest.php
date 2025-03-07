<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ValidateWooCommerceRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
       
        file_put_contents(storage_path('logs/payment.log'), "\n=============================================================================================================\n", FILE_APPEND);
        Log::channel('payment')->info("Request Captured Before Middleware:\n" . json_encode($request->all(), JSON_PRETTY_PRINT));

        if ($request->getContent() == '') {
            // Retornar uma resposta de erro caso o corpo esteja vazio
            return response()->json(['success' => false, 'message' => 'Nenhum body enviado'], 400);
        }
       
         $webhookSecret = env('WC_WEBHOOK_SECRET');

         if (!$webhookSecret) {
             return response()->json(['success' => false, 'message' => 'Webhook secret not configured'], 500);
         }
 
         // Obtém a assinatura enviada pelo WooCommerce no cabeçalho
         $signature = $request->header('X-WC-Webhook-Signature');
 
         if (!$signature) {
            Log::channel('payment')->warning('Requisição sem assinatura HMAC.');
             return response()->json(['success' => false, 'message' => 'Missing signature'], 401);
         }
 
         // Gera a assinatura esperada com HMAC SHA256
         $calculatedSignature = base64_encode(hash_hmac('sha256', $request->getContent(), $webhookSecret, true));

         // Compara a assinatura recebida com a esperada
         if (!hash_equals($calculatedSignature, $signature)) {
            Log::channel('payment')->warning('Assinatura HMAC inválida.', ['received' => $signature, 'expected' => $calculatedSignature]);
             return response()->json(['success' => false, 'message' => 'Invalid signature'], 403);
         }
        return $next($request);
    }
}
