<?php

namespace App\Contracts;

/**
 * Interface NewsApi
 * @package App\Contracts
 */
interface NewsApi
{
    /**
     * @param string $country
     * @return string|bool
     */
    public function getNews(string $country) : string|bool;
}
