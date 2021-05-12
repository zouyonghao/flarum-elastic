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

class Index
{
    /**
     * @var Client Elasticsearch client
     */
    private $client;

    /**
     * @var string Name of index for Flarum
     */
    private $index_name;

    /**
     * @var int Number of shards
     */
    private $shards;

    /**
     * @var int Number of replicas
     */
    private $replicas;

    /**
     * @var string Analyzer for text fields
     */
    private $text_analyzer;

    /**
     * @var string Search analyzer for text fields
     */
    private $text_search_analyzer;

    public function __construct(Client $client, SettingsRepositoryInterface $settings)
    {
        $this->client = $client;

        $this->index_name = $settings->get('es.index', 'flarum');
        $this->shards = $settings->get('es.shards', 1);
        $this->replicas = $settings->get('es.replicas', 0);
        $this->text_analyzer = $settings->get('es.analyzer', 'english');
        $this->text_search_analyzer = $settings->get('es.search_analyzer', 'standard');
    }

    /**
     * Check if index already exists
     * @return bool
     */
    public function indexExists(): bool
    {
        return $this->client->indices()->exists(['index' => $this->index_name]);
    }

    /**
     * Create index without any checks
     * @return array|false
     */
    public function createIndex()
    {
        return $this->indexExists() ? false :
            $this->client->indices()->create([
                'index' => $this->index_name,
                'body' => [
                    'settings' => $this->indexSettings(),
                    'mappings' => $this->indexMappings()
                ]
            ]);
    }

    /**
     * Drop index
     * @return array|false
     */
    public function dropIndex()
    {
        return $this->indexExists() ? $this->client->indices()->delete(['index' => $this->index_name])
            : false;
    }

    /**
     * Drop index if exists and recreate
     * @return array|false
     */
    public function regenerateIndex()
    {
        if ($this->indexExists()) {
            $this->dropIndex();
        }
        return $this->createIndex();
    }

    /**
     * Settings array for index creating
     * @return array
     */
    public function indexSettings(): array
    {
        return [
            'number_of_shards' => $this->shards,
            'number_of_replicas' => $this->replicas
        ];
    }

    /**
     * Mappings array entrypoint
     * @return \array[][]
     */
    public function indexMappings(): array
    {
        return [
            'post' => $this->postMappings()
        ];
    }

    /**
     * Flarum Post model mappings
     * @see Post
     * @return array[]
     */
    public function postMappings(): array
    {
        return [
            'properties' => [
                'content' => [
                    'type' => 'text',
                    'analyzer' => $this->text_analyzer,
                    'search_analyzer' => $this->text_search_analyzer
                ],

                'comment_id' => [
                    'type' => 'integer'
                ],

                'discussion_id' => [
                    'type' => 'integer',
                ],

                'count' => [
                    'type' => 'integer'
                ],

                'created_at' => [
                    'type' => 'date'
                ],

                'started_at' => [
                    'type' => 'date'
                ]
            ]
        ];
    }

    /**
     * Index name for easiest access
     * from any another class
     * @return string
     */
    public function name(): string
    {
        return $this->index_name;
    }
}
