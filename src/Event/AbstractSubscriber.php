<?php

/*
 * This file is part of rrmode/flarum-elasticsearch.
 *
 * Copyright (c) 2021 rrmode.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Rrmode\FlarumES\Event;

use Rrmode\FlarumES\Service\Search;

/**
 * Subscriber routines
 * @package Rrmode\FlarumES\Event
 */
class AbstractSubscriber
{
    public $search;

    public function __construct(Search $search)
    {
        $this->search = $search;
    }
}
