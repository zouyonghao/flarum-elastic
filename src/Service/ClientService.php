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
use Flarum\Settings\SettingsRepositoryInterface;

/**
 * Background injections
 * Base parameters
 * @package Rrmode\FlarumES\Service
 */
class ClientService extends IndexParameters
{
    /**
     * @var Client Elasticsearch low-level client
     */
    protected $client;

    /**
     * @var string Base index name
     */
    protected $indexName;

    /**
     * @var int Number of index shards
     */
    protected $shardCount;

    /**
     * @var int Number of index replicas
     */
    protected $replicasCount;

    public function __construct(Client $client, SettingsRepositoryInterface $settings)
    {
        parent::__construct($settings);
        $this->client = $client;
        $this->indexName = $settings->get('es.index', 'flarum');
        $this->shardCount = $settings->get('es.shards', 1);
        $this->replicasCount = $settings->get('es.replicas', 0);
    }

}
