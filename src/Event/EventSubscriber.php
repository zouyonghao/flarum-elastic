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

use Flarum\Discussion\Discussion;
use Flarum\Discussion\Event\Hidden as DiscussionHidden;
use Flarum\Discussion\Event\Restored as DiscussionRestored;
use Flarum\Post\Event\Deleted;
use Flarum\Post\Event\Hidden;
use Flarum\Post\Event\Posted;
use Flarum\Post\Event\Restored;
use Flarum\Post\Event\Saving;
use Flarum\Post\Post;
use Illuminate\Events\Dispatcher;
use Rrmode\FlarumES\Service\Search;

class EventSubscriber
{
    private $search;

    public function __construct(Search $search)
    {
        $this->search = $search;
    }

    /**
     * Subscription to Flarum events
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(Saving::class, [$this, 'onPostSaving']);
        $events->listen(Deleted::class, [$this, 'onPostDeleted']);
        $events->listen(Posted::class, [$this, 'onPostCreated']);
        $events->listen(Hidden::class, [$this, 'onPostHidden']);
        $events->listen(Restored::class, [$this, 'onPostRestored']);
        $events->listen(DiscussionHidden::class, [$this, 'onDiscussionHidden']);
        $events->listen(DiscussionRestored::class, [$this, 'onDiscussionRestored']);
    }

    /**
     * Flarum discussion restored event handler
     * @see Discussion
     * @param DiscussionRestored $event
     */
    public function onDiscussionRestored(DiscussionRestored $event): void
    {
        $event->discussion->posts->each(function (Post $post) {
            $this->search->indexPost($post);
        });
    }

    /**
     * Flarum discussion hidden event handler
     * @see Discussion
     * @param DiscussionHidden $event
     */
    public function onDiscussionHidden(DiscussionHidden $event): void
    {
        $event->discussion->posts->each(function (Post $post) {
            $this->search->delete($post);
        });
    }

    /**
     * Flarum post restored event handler
     * @see Post
     * @param Restored $event
     */
    public function onPostRestored(Restored $event): void
    {
        $this->search->indexPost($event->post);
    }

    /**
     * Flarum post hidden event handler
     * @see Post
     * @param Hidden $event
     */
    public function onPostHidden(Hidden $event): void
    {
        $this->search->delete($event->post);
    }

    /**
     * Flarum post created event handler
     * @see Post
     * @param Posted $event
     */
    public function onPostCreated(Posted $event): void
    {
        $this->search->indexPost($event->post);
    }

    /**
     * Flarum post saving event handler
     * @see Post
     * @param Saving $event
     */
    public function onPostSaving(Saving $event): void
    {
        $this->search->delete($event->post);
        $this->search->indexPost($event->post);
    }

    /**
     * Flarum post deleting event handler
     * @see Post
     * @param Deleted $event
     */
    public function onPostDeleted(Deleted $event): void
    {
        $this->search->delete($event->post);
    }
}
