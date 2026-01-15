<?php

class Statistics
{
    public static function calculate(array $rows): array
    {
        $stats = [
            'total_municipios'     => count($rows),
            'total_ok'             => 0,
            'total_nao_encontrado' => 0,
            'total_erro_api'       => 0,
            'pop_total_ok'         => 0,
            'medias_por_regiao'    => []
        ];

        $regioes = [];
        $vistos = [];

        foreach ($rows as $r) {
            $id = $r[5];

            if (isset($vistos[$id])) {
                continue;
            }

            $vistos[$id] = true;
            $status = $r[6];

            if ($status === 'OK') {
                $stats['total_ok']++;
                $pop = (float) $r[1];
                $stats['pop_total_ok'] += $pop;
                $regioes[$r[4]][] = $pop;
            } else if ($status === 'NAO_ENCONTRADO') {
                $stats['total_nao_encontrado']++;
            }
        }

        foreach ($regioes as $regiao => $valores) {
            $media = array_sum($valores) / count($valores);
            $stats['medias_por_regiao'][$regiao] = round($media, 2);
        }

        $stats['pop_total_ok'] = round($stats['pop_total_ok'], 2);

        return $stats;
    }
}
