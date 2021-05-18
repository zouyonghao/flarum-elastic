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

/**
 * Create index command for Flarum CLI script
 * @package Rrmode\FlarumES\Console
 */
class CreateIndexCommand extends AbstractIndexCommand
{
    protected function configure(): void
    {
        $this
            ->setName('index:create')
            ->setDescription("Create index");
    }

    /**
     * Create index
     *
     * @inheritDoc
     */
    protected function fire(): void
    {
        $this->index->createIndex();
        $this->info("Index {$this->index->name()} created");
    }
}
