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

use Flarum\Post\Event\Deleted;
use Flarum\Post\Event\Hidden;
use Flarum\Post\Event\Posted;
use Flarum\Post\Event\Restored;
use Flarum\Post\Post;
use Illuminate\Events\Dispatcher;

/**
 * Temporary not used
 * Subscriptions on Post events for efficiently indexing
 * @package Rrmode\FlarumES\Event
 */
class PostSubscriber extends AbstractSubscriber
{
    /**
     * Subscription on all events related to Post
     * @see Post
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(Deleted::class, [$this, 'onDeleted']);
        $events->listen(Hidden::class, [$this, 'onHidden']);
        $events->listen(Posted::class, [$this, 'onPosted']);
        $events->listen(Restored::class, [$this, 'onRestored']);
    }

    public function onDeleted(Deleted $event): void
    {
        $this->search->delete($event->post);
    }

    public function onHidden(Hidden $event): void
    {
        $this->search->delete($event->post);
    }

    public function onPosted(Posted $event): void
    {
        $this->search->index($event->post);
    }

    public function onRestored(Restored $event): void
    {
        $this->search->index($event->post);
    }
}
