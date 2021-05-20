<?php

/*
 * This file is part of rrmode/flarum-elasticsearch.
 *
 * Copyright (c) 2021 rrmode.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Rrmode\FlarumES;

use Flarum\Search\GambitInterface;
use Flarum\Search\SearchState;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Rrmode\FlarumES\Service\Search;

/**
 * Common ES Gambit abstraction
 * @package Rrmode\FlarumES
 */
abstract class AbstractElasticGambit implements GambitInterface
{
    /**
     * @var Search Search service
     */
    protected $es;

    public function __construct(Search $search)
    {
        $this->es = $search;
    }

    /**
     * @inheritDoc
     */
    public function apply(SearchState $search, $bit): bool
    {
        $results = $this->es->find($bit)->model($this->getModelClass())->get();

        if ($results->isEmpty()) {
            return false;
        }

        $search->getQuery()->whereIn('id', $results->map(function (Model $model) {
            return $model->getKey();
        }));
        return true;
    }

    abstract public function getModelClass(): string;
}
