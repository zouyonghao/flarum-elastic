<?php

/*
 * This file is part of rrmode/flarum-elasticsearch.
 *
 * Copyright (c) 2021 rrmode.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Rrmode\FlarumES\Service\Search;

use Illuminate\Support\Collection;

/**
 * Results filtering class
 * @package Rrmode\FlarumES\Service\Search
 */
class Results
{
    /**
     * @var Collection Raw results
     */
    private $results;

    /**
     * @var Collection Searching entities
     */
    private $entities;

    public function __construct(Collection $raw, Collection $entities)
    {
        $this->results = $raw;
        $this->entities = $entities;
    }

    public static function make(Collection $raw, Collection $entities): Results
    {
        return new self($raw, $entities);
    }

    public function model(string $class): Results
    {
        return new self($this->entities->filter(function (array $rawEntity) use ($class) {

        }), $this->entities);
    }

    public function get(): Collection
    {
        return $this->results;
    }
}
