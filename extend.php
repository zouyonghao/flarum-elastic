<?php

/*
 * This file is part of rrmode/flarum-elasticsearch.
 *
 * Copyright (c) 2021 rrmode.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Rrmode\FlarumES;

use Elasticsearch\Client;
use Flarum\Api\Controller\ListDiscussionsController;
use Flarum\Discussion\Discussion;
use Flarum\Extend;
use Flarum\Post\Post;
use Illuminate\Container\Container;
use Rrmode\FlarumES\Controller\DiscussionsListController;
use Rrmode\FlarumES\Event\EventSubscriber;
use Rrmode\FlarumES\Provider\Elasticsearch;

return [

    /**
     * Front-end assets registering
     */

    (new Extend\Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js')
        ->css(__DIR__.'/resources/less/forum.less'),

    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js')
        ->css(__DIR__.'/resources/less/admin.less'),

    new Extend\Locales(__DIR__ . '/resources/locale'),

    /**
     * Back-end stuff registering
     */

    /**
     * Service provider
     * Registers very useful macro for Flarum Post model
     * and Elasticsearch client Container binding
     * @see Post
     * @see Client
     * @see Container
     */
    (new Extend\ServiceProvider())
        ->register(Elasticsearch::class),

    /**
     * API route for real-time searching
     * @see Post
     * @see ListDiscussionsController
     */
    (new Extend\Routes('api'))
        ->get('/es/discussions', 'es.posts', DiscussionsListController::class),

    /**
     * Events subscriber
     * Tracks Posts and Discussions updates
     * @see Post
     * @see Discussion
     */
    (new Extend\Event())
        ->subscribe(EventSubscriber::class),
];
