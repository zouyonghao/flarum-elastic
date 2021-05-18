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

use Flarum\Post\Post;
use Illuminate\Database\Eloquent\Builder;

/**
 * @package Rrmode\FlarumES\Service\Index
 */
class PostDocument extends AbstractDocument
{
    protected static $model = Post::class;

    public function mappings(): array
    {
        return [
            'properties' => [
                'content' => [
                    'type' => 'text',
                    'analyzer' => $this->textAnalyzer,
                    'search_analyzer' => $this->textSearchAnalyzer
                ],

                'comment_id' => [
                    'type' => 'integer'
                ],

                'discussion_id' => [
                    'type' => 'integer',
                ],

                'created_at' => [
                    'type' => 'date'
                ],
            ]
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
