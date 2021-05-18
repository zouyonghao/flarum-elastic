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
use Rrmode\FlarumES\Service\Search;

/**
 * Regenerate index command for Flarum CLI script
 * @package Rrmode\FlarumES\Console
 */
class ReindexAllCommand extends AbstractIndexCommand
{
    protected function configure(): void
    {
        $this
            ->setName('index:refresh')
            ->setDescription("Drop index and recreate it with all Flarum Posts")
            ->setAliases(['index']);
    }

    /**
     * Drop index and recreate it with all Flarum Posts
     *
     * @inheritDoc
     */
    protected function fire(): void
    {
        $this->search->reindexAll();
        $this->info("Index refreshed");
    }
}
