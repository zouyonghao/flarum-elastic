<?php

/*
 * This file is part of rrmode/flarum-elasticsearch.
 *
 * Copyright (c) 2021 rrmode.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Rrmode\FlarumES\Console;

use Flarum\Console\AbstractCommand;
use Rrmode\FlarumES\Service\Index;
use Rrmode\FlarumES\Service\Search;

/**
 * Simple access to Index and Search services from AbstractCommand context
 * @see AbstractCommand
 * @see Index
 * @see Search
 * @package Rrmode\FlarumES\Console
 */
abstract class AbstractIndexCommand extends AbstractCommand
{
    /**
     * @var Index Elasticsearch indexing service
     */
    protected $index;

    /**
     * @var Search Elasticsearch searching service
     */
    protected $search;

    public function __construct(Index $index, Search $search, string $name = null)
    {
        parent::__construct($name);
        $this->index = $index;
        $this->search = $search;
    }
}
