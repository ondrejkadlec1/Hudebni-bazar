<?php

namespace Ondra\App\Shared\Infrastructure;

trait CET
{
    private function toCET(\DateTime $date): string {
        $date->setTimezone(new \DateTimeZone('Europe/Prague'));
        return $date->format('j. n. Y');
    }
}