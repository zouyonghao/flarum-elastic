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

use Flarum\User\User;
use Illuminate\Database\Eloquent\Builder;

/**
 * @package Rrmode\FlarumES\Service\Index
 */
class UserDocument extends AbstractDocument
{
    protected static $model = User::class;

    /**
     * {@inheritDoc}
     * @return array
     */
    public function mappings(): array
    {
        return [
            'properties' => [
                'username' => [

                ]
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
