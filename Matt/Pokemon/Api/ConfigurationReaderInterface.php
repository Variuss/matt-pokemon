<?php

declare(strict_types=1);

namespace Matt\Pokemon\Api;

interface ConfigurationReaderInterface
{
    /**
     * @return bool
     */
    public function isEnabled(): bool;

    /**
     * @return string
     */
    public function getApiUrl(): string;
}
