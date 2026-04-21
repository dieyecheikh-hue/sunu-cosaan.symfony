<?php

namespace App\Trait;

trait TimeZoneTrait
{
    // Changer le fuseau horaire du site

    public function changeTimeZone(string $timeZoneid): void
    {
        date_default_timezone_set($timeZoneid);
    }
}
