<?php

/*
 * This file is part of rrmode/flarum-elasticsearch.
 *
 * Copyright (c) 2021 rrmode.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Rrmode\FlarumES\Search;

use Flarum\Discussion\Discussion;
use Rrmode\FlarumES\AbstractElasticGambit;

/**
 * Elastic Discussion Gambit
 * @package Rrmode\FlarumES\Search
 */
class DiscussionGambit extends AbstractElasticGambit
{

    public function getModelClass(): string
    {
        return Discussion::class;
    }
}
