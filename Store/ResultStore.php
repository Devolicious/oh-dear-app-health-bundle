<?php
declare(strict_types=1);

namespace Devolicious\OhDearAppHealthBundle\Store;

interface ResultStore
{
    public function save(string $identifier, StoredResult $result): void;

    public function fetchLastResult(string $identifier): ?StoredResult;
}
