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

use Flarum\Discussion\Discussion;
use Illuminate\Database\Eloquent\Builder;

/**
 * @package Rrmode\FlarumES\Service\Index
 */
class DiscussionDocument extends AbstractDocument
{
    protected static $model = Discussion::class;

    /**
     * {@inheritDoc}
     * @return array
     */
    public function mappings(): array
    {
        return [

        ];
    }

    /**
     * {@inheritDoc}
     */
    public static function registerDocumentMacro(): void
    {
        $model = self::$model;

        Builder::macro('document', function () use ($model) {
            $thisModel = $this->getModel();

            if ($thisModel instanceof $model) {
                return [

                ];
            }
            unset(static::$macros['document']);
            throw new \Exception("Document macro not declared for $model class");
        });
    }
}
