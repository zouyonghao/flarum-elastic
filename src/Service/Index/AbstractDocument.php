<?php

/*
 * This file is part of rrmode/flarum-elasticsearch.
 *
 * Copyright (c) 2021 rrmode.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Rrmode\FlarumES\Service\Index;

use Rrmode\FlarumES\Service\IndexParameters;

/**
 * Abstraction for easiest access to some helpful properties
 * @package Rrmode\FlarumES\Service\Index
 */
abstract class AbstractDocument extends IndexParameters
{
    /**
     * Elasticsearch model mapping
     * @return array
     */
    abstract public function mappings(): array;

    /**
     * Registering model::document() macro
     */
    abstract public static function registerDocumentMacro(): void;
}
