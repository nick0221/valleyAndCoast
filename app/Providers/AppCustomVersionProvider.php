<?php

namespace App\Providers;
use Awcodes\FilamentVersions\Providers\Contracts\VersionProvider;


class AppCustomVersionProvider implements VersionProvider
{
    public function getName(): string
    {
        return 'OHRS';
    }

    public function getVersion(): string
    {
        return '1.5.3';
    }
}
