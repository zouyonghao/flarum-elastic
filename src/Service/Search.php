<?php

/*
 * This file is part of rrmode/flarum-elasticsearch.
 *
 * Copyright (c) 2021 rrmode.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Rrmode\FlarumES\Service;

use Elasticsearch\Client;
use Flarum\Post\Post;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Rrmode\FlarumES\Service\Search\Results;

/**
 * ES Search service
 * @package Rrmode\FlarumES\Service
 */
class Search extends ClientService
{
    protected $index;

    public function __construct(Client $client, SettingsRepositoryInterface $settings, Index $index)
    {
        parent::__construct($client, $settings);
        $this->index = $index;
    }

    /**
     * Destroy old index, create new and add all entities
     * @see Model
     */
    public function reindexAll(): void
    {
        $this->index->regenerateIndex();
        foreach ($this->dynamicEntities as $entity) {
            $entity::all()->each(function (Model $model) {
                $this->index($model);
            });
        }
    }

    /**
     * Index Flarum document
     * @param Model $model
     * @return array|callable
     */
    public function index(Model $model)
    {
        return $this->client->index([
            'index' => $this->index->name(),
            'id' => $model->getKey(),
            'type' => get_class($model),
            'body' => $model->toArray()
        ]);
    }

    /**
     * Delete Flarum document from indexing
     * @param Model $model
     * @return array|callable
     */
    public function delete(Model $model)
    {
        return $this->client->delete([
            'index' => $this->index->name(),
            'id' => $model->getKey()
        ]);
    }

    /**
     * Search Flarum entities by wildcard
     * @param string $text
     * @return Results of entities
     * @see Results
     */
    public function find(string $text): Results
    {
        $response = $this->client->search([
            'index' => $this->index->name(),
            'body' => [
                'query' => [
                    'query_string' => [
                        'query' => "*$text*"
                    ]
                ]
            ]
        ]);

        return new Results(collect($response['hits']['hits'])->map(function ($hit) {
            return $hit;
        }), collect($this->dynamicEntities));
    }
}
