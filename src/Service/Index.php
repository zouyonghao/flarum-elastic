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

use Flarum\Post\Post;

/**
 * ES Index service
 * @package Rrmode\FlarumES\Service
 */
class Index extends ClientService
{
    /**
     * Check if index already exists
     * @return bool
     */
    public function indexExists(): bool
    {
        return $this->client->indices()->exists(['index' => $this->indexName]);
    }

    /**
     * Create index without any checks
     * @return array|false
     */
    public function createIndex()
    {
        return $this->indexExists() ? false :
            $this->client->indices()->create([
                'index' => $this->indexName,
                'body' => [
                    'settings' => $this->indexSettings(),
//                    'mappings' => $this->indexMappings()
                ]
            ]);
    }

    /**
     * Drop index
     * @return array|false
     */
    public function dropIndex()
    {
        return $this->indexExists() ? $this->client->indices()->delete(['index' => $this->indexName])
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
            'number_of_shards' => $this->shardCount,
            'number_of_replicas' => $this->replicasCount,
            'analysis' => $this->setupAnalysis()
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
                    'analyzer' => $this->textAnalyzer,
                    'search_analyzer' => $this->textSearchAnalyzer
                ],

                'comment_id' => [
                    'type' => 'integer'
                ],

                'discussion_id' => [
                    'type' => 'integer',
                ],

                'created_at' => [
                    'type' => 'date'
                ],
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
        return $this->indexName;
    }
}
