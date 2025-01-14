<?php
namespace App\Helpers;
class CriptografiaHelper
{
    public static function encryptData(array $dataArray, $encryptionKey): string
    {
        $jsonData = json_encode($dataArray, JSON_THROW_ON_ERROR);
        $iv = random_bytes(16); // Geração do vetor de inicialização (IV)
        $encryptedData = openssl_encrypt(
            $jsonData,
            'AES-256-CBC',
            $encryptionKey,
            0,
            $iv
        );

        if ($encryptedData === false) {
            throw new \RuntimeException('Erro ao criptografar os dados.');
        }

        // Codifica o IV e os dados criptografados em Base64 para enviar na URL
        $result = base64_encode($iv . $encryptedData);
        return $result;
        //return urlencode($result);
    }

    public static function decryptData(string $encryptedData, $encryptionKey): array
    {
        // Decodifica os dados Base64
        $decodedData = base64_decode($encryptedData, true);

        if ($decodedData === false) {
            throw new \RuntimeException('Erro ao decodificar os dados.');
        }

        // Extrai o IV (16 primeiros bytes) e os dados criptografados
        $iv = substr($decodedData, 0, 16);
        $cipherText = substr($decodedData, 16);

        if (strlen($iv) !== 16) {
            throw new \RuntimeException('Vetor de inicialização (IV) inválido.');
        }

        // Descriptografa os dados
        $decryptedData = openssl_decrypt(
            $cipherText,
            'AES-256-CBC',
            $encryptionKey,
            0,
            $iv
        );

        if ($decryptedData === false) {
            throw new \RuntimeException('Erro ao descriptografar os dados.');
        }

        // Decodifica o JSON para um array
        return json_decode($decryptedData, true, 512, JSON_THROW_ON_ERROR);
    }
}