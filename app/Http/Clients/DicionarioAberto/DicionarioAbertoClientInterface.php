<?php

namespace App\Http\Clients\DicionarioAberto;

interface DicionarioAbertoClientInterface
{
    /**
     * Searches for a word using DicionarioAberto's API.
     *
     * @throws DicionarioAbertoClientException
     */
    public function search(string $word): array;
}
