<?php

namespace App\Http\Clients\Dicio;

use DOMDocument;

interface DicioClientInterface
{
    /**
     * Searches for an entry on Dicio's website.
     *
     * @throws DicioClientException
     */
    public function search(string $word): DOMDocument;
}
