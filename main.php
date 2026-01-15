<?php

require_once 'utils/ConsultCsv.php';
require_once 'services/IbgeAPI.php';
require_once 'utils/Matcher.php';
require_once 'utils/Statistics.php';
require_once 'services/SubmitStats.php';

$token = 'eyJhbGciOiJIUzI1NiIsImtpZCI6ImR0TG03UVh1SkZPVDJwZEciLCJ0eXAiOiJKV1QifQ.eyJpc3MiOiJodHRwczovL215bnhsdWJ5a3lsbmNpbnR0Z2d1LnN1cGFiYXNlLmNvL2F1dGgvdjEiLCJzdWIiOiIwMzg4MGRhYy1mMmVlLTQzYTItOTY0Ni02NzQwMTllYzczNzEiLCJhdWQiOiJhdXRoZW50aWNhdGVkIiwiZXhwIjoxNzY4NTEyNTIwLCJpYXQiOjE3Njg1MDg5MjAsImVtYWlsIjoibWF0aGV1c3ByYWZhcmlhQGdtYWlsLmNvbSIsInBob25lIjoiIiwiYXBwX21ldGFkYXRhIjp7InByb3ZpZGVyIjoiZW1haWwiLCJwcm92aWRlcnMiOlsiZW1haWwiXX0sInVzZXJfbWV0YWRhdGEiOnsiZW1haWwiOiJtYXRoZXVzcHJhZmFyaWFAZ21haWwuY29tIiwiZW1haWxfdmVyaWZpZWQiOnRydWUsIm5vbWUiOiJNYXRoZXVzIFBlcmVpcmEgZGUgRmFyaWEiLCJwaG9uZV92ZXJpZmllZCI6ZmFsc2UsInN1YiI6IjAzODgwZGFjLWYyZWUtNDNhMi05NjQ2LTY3NDAxOWVjNzM3MSJ9LCJyb2xlIjoiYXV0aGVudGljYXRlZCIsImFhbCI6ImFhbDEiLCJhbXIiOlt7Im1ldGhvZCI6InBhc3N3b3JkIiwidGltZXN0YW1wIjoxNzY4NTA4OTIwfV0sInNlc3Npb25faWQiOiI2ZWQ1ZWNkNy1jOGZhLTRmMmUtYjVkNy1hMmZiZWE0ZDk4Y2EiLCJpc19hbm9ueW1vdXMiOmZhbHNlfQ.ioV8eDmrsjHuF4fDvtdOWeC0Dcvdr3zQmrdfw1_Ad9Q';

$input = ConsultCsv::read('input.csv');
$ibgeData = IbgeAPI::getAllMunicipios();

$resultado = [];
$chosenUFs = [];

foreach ($input as $row) {
    $match = Matcher::findBestMatch($row['municipio'], $ibgeData, $chosenUFs);

    if ($match === null) {
        $resultado[] = [
            $row['municipio'],
            $row['populacao'],
            '', '', '', '', 'NAO_ENCONTRADO'
        ];
    } else {
        $resultado[] = [
            $row['municipio'],
            $row['populacao'],
            $match['nome'],
            $match['uf'],
            $match['regiao'],
            $match['id'],
            'OK'
        ];
    }
}

ConsultCsv::write('resultado.csv', $resultado);

$stats = Statistics::calculate($resultado);
$response = SubmitStats::send($stats, $token);

echo "\nUser_id: {$response['score']}\n";
echo "Email: {$response['email']}\n";
echo "Score: {$response['score']}\n";
echo "Feedback: {$response['feedback']}\n";
