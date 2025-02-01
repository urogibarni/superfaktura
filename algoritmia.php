<?php

apgoritmiaTernaryOperator(16);
echo str_repeat('=', 20);
fancyPrint('');
apgoritmiaSwitch(16);
echo str_repeat('=', 20);
fancyPrint('');
apgoritmiaSwitchExtended(16);

function fancyPrint(string $output): void
{
    echo sprintf("%s%s", $output, (PHP_SAPI !== 'cli' ? '<br>' : PHP_EOL));
}

function apgoritmiaTernaryOperator(?int $maxIteration = 100): void
{
    foreach (range(1, $maxIteration) AS $number) {
 
        $output = '';
        $output .= 0 === $number % 3 ? 'Super' : '';
        $output .= 0 === $number % 5? 'Faktura' : '';
        $output .= (0 !== $number % 3 && 0 !== $number % 5) ? $number : '';

        fancyPrint($output);
    }
}

function apgoritmiaSwitch(?int $maxIteration = 100): void
{
    foreach (range(1, $maxIteration) AS $number) {
        $output = '';

        switch ($number) {
            case (0 === $number % 3):
                $output .= 'Super';
                if (0 !== $number % 15) {
                    break;
                }
            case (0 === $number % 5):
                $output .= 'Faktura';
                break;
            default:
                $output .= $number;
                break;
        }

        fancyPrint($output);
    }
}

function apgoritmiaSwitchExtended(?int $maxIteration = 100): void
{
    $output = '';

    foreach (range(1, $maxIteration) AS $number) {
        switch ($number) {
            case (0 === $number % 15):
                $output = 'AresClient';
                break;
            case (0 === $number % 3):
                $output = 'Super';
                break;
            case (0 === $number % 5):
                $output = 'Faktura';
                break;
            default:
                $output = $number;
                break;
        }

        fancyPrint($output);
    }
}
