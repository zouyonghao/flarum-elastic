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
use Illuminate\Support\Collection;
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

    public function index(Model $model)
    {
        return $this->client->index([
            'index' => $this->index->name(),
            'id' => $model->getKey(),
            'type' => $model->getTable(),
            'body' => $model->toArray()
        ]);
    }

    /**
     * Delete Flarum Post document from indexing
     * @see Post
     * @param Post $post
     * @return array|callable
     */
    public function delete(Post $post)
    {
        return $this->client->delete([
            'index' => $this->index->name(),
            'id' => $post->getKey()
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

        return Results::make(collect($response['hits']['hits'])->map(function ($hit) {
            return $hit;
        }), collect($this->dynamicEntities));
    }
}
