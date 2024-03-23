<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFunctions(): ?array
    {
        return [
            new TwigFunction('pluralize', [$this, 'getPluralizedString']),
        ];
    }

    /**
     * @url https://github.com/TomodomoCo/twig-pluralize-extension/blob/master/src/Pluralize.php
     */
    public function getPluralizedString(int $count, string $one, string $many, ?string $none = null): string
    {
        // If the option for $none is null, use the option for $many
        $none = $none ?? $many;

        // Choose the correct string
        switch ($count) {
            case 0:
                $string = $none;
                break;
            case 1:
                $string = $one;
                break;
            default:
                $string = $many;
                break;
        }

        // Return the result
        return sprintf($string, $count);
    }
}
