<?php

class IbgeAPI
{
    public static function getAllMunicipios(): array
    {
        $url = "https://servicodados.ibge.gov.br/api/v1/localidades/municipios";

        $response = @file_get_contents($url);

        if ($response === false) {
            throw new Exception("Erro ao acessar a API do IBGE");
        }

        $data = json_decode($response, true);

        $municipios = [];

        foreach ($data as $m) {

            $uf = $m['microrregiao']['mesorregiao']['UF']['sigla'] ?? null;
            $regiao = $m['microrregiao']['mesorregiao']['UF']['regiao']['nome'] ?? null;

            if (!$uf || !$regiao) {
                continue;
            }

            $municipios[] = [
                'id'     => $m['id'],
                'nome'   => $m['nome'],
                'uf'     => $uf,
                'regiao' => $regiao
            ];
        }

        return $municipios;
    }
}
