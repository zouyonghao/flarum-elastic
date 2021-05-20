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

use Flarum\Discussion\Discussion;
use Flarum\Post\CommentPost;
use Flarum\Settings\SettingsRepositoryInterface;
use Flarum\User\User;

/**
 * Accurate tuning Elasticsearch index
 * @package Rrmode\FlarumES\Service
 */
class IndexParameters
{
    /**
     * @var bool Static mapping feature
     */
    protected $staticMapping;

    /**
     * @var array Dynamic mapping entities
     */
    protected $dynamicEntities = [
        User::class,
        CommentPost::class,
        Discussion::class,
    ];

    /**
     * @var string Default analyzer for text fields
     */
    protected $textAnalyzer;

    /**
     * @var string Default search analyzer for text fields
     */
    protected $textSearchAnalyzer;

    public function __construct(SettingsRepositoryInterface $settings)
    {
        $this->staticMapping = $settings->get('rrmode-elasticsearch.static_mapping', false);
        $this->textAnalyzer = $settings->get('es.analyzer', 'english');
        $this->textSearchAnalyzer = $settings->get('es.search_analyzer', 'standard');
    }

    public function setupAnalysis(): array
    {
        return [
            'index_analyzer' => $this->setupIndexAnalyzer(),
            'search_analyzer' => $this->setupSearchAnalyzer(),
            'filter' => $this->setupFilter()
        ];
    }

    public function setupIndexAnalyzer(): array
    {
        return [
            'custom_index_analyzer' => [
                'type' => 'custom',
                'tokenizer' => 'standard',
                'filter' => [
                    'lowercase',
                    'mynGram'
                ]
            ]
        ];
    }

    public function setupSearchAnalyzer(): array
    {
        return [
            'custom_search_analyzer' => [
                'type' => 'custom',
                'tokenizer' => 'standard',
                'filter' => [
                    'standard',
                    'lowercase',
                    'mynGram'
                ]
            ]
        ];
    }

    public function setupFilter(): array
    {
        return [
            'mynGram' => [
                'type' => 'nGram',
                'min_gram' => 2,
                'max_gram' => 50
            ]
        ];
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
}
