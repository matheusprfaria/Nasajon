<?php

class SubmitStats
{
    public static function send(array $stats, string $accessToken): array
    {
        $url = "https://mynxlubykylncinttggu.functions.supabase.co/ibge-submit";

        $payload = json_encode(['stats' => $stats]);

        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer {$accessToken}",
                "Content-Type: application/json"
            ]
        ]);

        $response = curl_exec($ch);

        if ($response === false) {
            throw new Exception("Erro ao enviar estatísticas");
        }

        $data = json_decode($response, true);

        if (!$data || !isset($data['score'])) {
            throw new Exception("Resposta inválida da API");
        }

        return $data;
    }
}
