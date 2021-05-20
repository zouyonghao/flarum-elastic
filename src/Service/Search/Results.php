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
    public $results;

    /**
     * @var Collection Searching entities
     */
    public $entities;

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
        return new Results($this->results->filter(function (array $rawResult) use ($class) {
            $rawType = $rawResult['_type'];
            return get_class(new $rawType) === get_class(new $class);
        }), $this->entities);
    }

    public function get(): Collection
    {
        return $this->results->map(function (array $raw) {
            $type = $raw['_type'];
            $object = new $type;
            return $object::query()->newQuery()->find($raw['_source']['id']);
        });
    }
}
