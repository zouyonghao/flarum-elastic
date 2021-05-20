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
use Flarum\Console\AbstractCommand;
use Flarum\Discussion\Discussion;
use Flarum\Discussion\Search\DiscussionSearcher;
use Flarum\Extend;
use Flarum\Post\Post;
use Flarum\User\Search\UserSearcher;
use Flarum\User\User;
use Illuminate\Container\Container;
use Rrmode\FlarumES\Console\CreateIndexCommand;
use Rrmode\FlarumES\Console\DropIndexCommand;
use Rrmode\FlarumES\Console\ReindexAllCommand;
use Rrmode\FlarumES\Event\DiscussionSubscriber;
use Rrmode\FlarumES\Event\UserSubscriber;
use Rrmode\FlarumES\Provider\Elasticsearch;
use Rrmode\FlarumES\Search\DiscussionGambit;
use Rrmode\FlarumES\Search\UserGambit;
use Rrmode\FlarumES\Service\Index;
use Rrmode\FlarumES\Service\Search;

return [

    /**
     * Localization
     */
    new Extend\Locales(__DIR__ . '/locale'),

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
     * Registers very useful macro
     * and Elasticsearch client Container binding
     * @see Post
     * @see Client
     * @see Container
     */
    (new Extend\ServiceProvider())
        ->register(Elasticsearch::class),

    /**
     * Elastic Gambit for Discussion
     * @see Discussion
     * @see DiscussionSearcher
     */
    (new Extend\SimpleFlarumSearch(DiscussionSearcher::class))
        ->addGambit(DiscussionGambit::class),

    /**
     * Elastic Gambit for User
     * @see User
     * @see UserSearcher
     */
    (new Extend\SimpleFlarumSearch(UserSearcher::class))
        ->addGambit(UserGambit::class),

    /**
     * Temporary not used
     * Post events subscriber
     * @see Post
     * @see Discussion
     */
//    (new Extend\Event())
//        ->subscribe(PostSubscriber::class),

    /**
     * Discussion events subscriber
     * @see Discussion
     */
    (new Extend\Event())
        ->subscribe(DiscussionSubscriber::class),

    /**
     * User events subscriber
     * @see User
     */
    (new Extend\Event())
        ->subscribe(UserSubscriber::class),

    /**
     * Flarum built-in console app commands
     * @see AbstractCommand
     */

    /**
     * Command: index
     * Alias: index:refresh
     *
     * Recreate index with all Flarum Posts
     * @see AbstractCommand
     * @see Search
     * @see Post
     */
    (new Extend\Console())
        ->command(ReindexAllCommand::class),

    /**
     * Command: index:create
     *
     * Create index
     * @see AbstractCommand
     * @see Index
     */
    (new Extend\Console())
        ->command(CreateIndexCommand::class),

    /**
     * Command: index:drop
     *
     * Drop index
     * @see AbstractCommand
     * @see Index
     */
    (new Extend\Console())
        ->command(DropIndexCommand::class)
];
