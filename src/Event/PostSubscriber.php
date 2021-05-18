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
use Flarum\Post\Event\Deleting;
use Flarum\Post\Event\Hidden;
use Flarum\Post\Event\Posted;
use Flarum\Post\Event\Restored;
use Flarum\Post\Event\Revised;
use Flarum\Post\Event\Saving;
use Flarum\Post\Post;
use Illuminate\Events\Dispatcher;

/**
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
        $events->listen(Deleting::class, [$this, 'onDeleting']);
        $events->listen(Hidden::class, [$this, 'onHidden']);
        $events->listen(Posted::class, [$this, 'onPosted']);
        $events->listen(Restored::class, [$this, 'onRestored']);
        $events->listen(Revised::class, [$this, 'onRevised']);
        $events->listen(Saving::class, [$this, 'onSaving']);
    }

    public function onDeleted(Deleted $event): void
    {

    }

    public function onDeleting(Deleting $event): void
    {

    }

    public function onHidden(Hidden $event): void
    {

    }

    public function onPosted(Posted $event): void
    {

    }

    public function onRestored(Restored $event): void
    {

    }

    public function onRevised(Revised $event): void
    {

    }

    public function onSaving(Saving $event): void
    {

    }
}
