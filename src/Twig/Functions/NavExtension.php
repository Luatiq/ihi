<?php

namespace App\Twig\Functions;

use Symfony\Component\Yaml\Yaml;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class NavExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('navItems', [$this, 'getNav']),
        ];
    }

    public function getNav(): mixed
    {
        return Yaml::parseFile(__DIR__.'/../../nav.yaml');
    }
}
