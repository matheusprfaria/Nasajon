<?php

class ConsultCsv
{
    public static function read(string $file): array
    {
        $rows = [];
        $handle = fopen($file, 'r');

        if ($handle === false) {
            throw new Exception("Erro ao abrir o arquivo {$file}");
        }

        $header = fgetcsv($handle);

        while (($data = fgetcsv($handle)) !== false) {
            $rows[] = array_combine($header, $data);
        }

        fclose($handle);

        return $rows;
    }

    public static function write(string $file, array $rows): void
    {
        $handle = fopen($file, 'w');

        fputcsv($handle, [
            'municipio_input',
            'populacao_input',
            'municipio_ibge',
            'uf',
            'regiao',
            'id_ibge',
            'status'
        ]);

        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }

        fclose($handle);
    }
}
