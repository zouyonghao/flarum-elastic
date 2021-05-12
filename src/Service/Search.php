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
use Illuminate\Database\Eloquent\Collection;

class Search
{
    /**
     * @var Client Elasticsearch client
     */
    private $client;

    /**
     * @var Index Index operations
     */
    private $index;

    public function __construct(Client $client, Index $index)
    {
        $this->client = $client;
        $this->index = $index;
    }

    /**
     * Destroy old index, create new and add all Flarum Posts
     * @see Post
     */
    public function reindexAll(): void
    {
        $this->index->regenerateIndex();
        Post::all()->each(function (Post $post) {
            $this->indexPost($post);
        });
    }

    /**
     * Add Flarum Post document for indexing
     * @see Post
     * @param Post $post
     * @return array|callable
     */
    public function indexPost(Post $post)
    {
        return $this->client->index([
            'index' => $this->index->name(),
            'id' => $post->getKey(),
            'type' => 'post',
            'body' => [
                'content' => $post->content,
                'comment_id' => $post->getKey(),
                'discussion_id' => $post->discussion_id,
                'count' => $post->discussion->posts->count(),
                'created_at' => $post->created_at,
                'started_at' => $post->discussion->created_at
            ]
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
            'type' => 'post',
            'id' => $post->getKey()
        ]);
    }

    /**
     * Search Flarum Posts by text
     * @see Post
     * @param $text
     * @return Collection of Posts
     */
    public function find($text): Collection
    {
        $response = $this->client->search([
            'index' => $this->index->name(),
            'type' => 'post',
            'body' => [
                'query' => [
                    'match' => [
                        'content' => $text
                    ]
                ]
            ]
        ]);

        if (!isset($response['hits']['hits'])) {
            return new Collection();
        }

        $modelIds = collect($response['hits']['hits'])->map(function ($hit) {
            return $hit['_source']['comment_id'];
        });

        return Post::whereIn('id', $modelIds->all())->get();
    }
}
