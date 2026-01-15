<?php

class Matcher
{
    public static function normalize(string $text): string
    {
        $text = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
        return strtoupper(trim($text));
    }

    public static function findBestMatch(string $input, array $ibgeData): ?array
    {
        $inputNorm = self::normalize($input);

        $bestScore = 0;
        $candidates = [];

        foreach ($ibgeData as $item) {
            $candidateNorm = self::normalize($item['nome']);
            similar_text($inputNorm, $candidateNorm, $percent);

            if ($percent > $bestScore) {
                $bestScore = $percent;
                $candidates = [ $item + ['score' => $percent] ];
            } elseif ($percent == $bestScore) {
                $candidates[] = $item + ['score' => $percent];
            }
        }

        if ($bestScore < 80) {
            return null;
        }

        usort($candidates, fn($a, $b) => $b['id'] <=> $a['id']);

        return $candidates[0];
    }
}
