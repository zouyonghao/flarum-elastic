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

use Flarum\Discussion\Event\Deleted;

use Flarum\Discussion\Event\Hidden;
use Flarum\Discussion\Event\Renamed;
use Flarum\Discussion\Event\Restored;
use Flarum\Discussion\Event\Started;
use Illuminate\Events\Dispatcher;

/**
 * Subscriptions on Discussion events for efficiently indexing
 * @package Rrmode\FlarumES\Event
 */
class DiscussionSubscriber extends AbstractSubscriber
{
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(Deleted::class, [$this, 'onDeleted']);
        $events->listen(Hidden::class, [$this, 'onHidden']);
        $events->listen(Renamed::class, [$this, 'onRenamed']);
        $events->listen(Restored::class, [$this, 'onRestored']);
        $events->listen(Started::class, [$this, 'onStarted']);
    }

    public function onDeleted(Deleted $event): void
    {
        $this->search->delete($event->discussion);
    }

    public function onHidden(Hidden $event): void
    {
        $this->search->delete($event->discussion);
    }

    public function onRenamed(Renamed $event): void
    {
        $this->search->delete($event->discussion);
        $this->search->index($event->discussion);
    }

    public function onRestored(Restored $event): void
    {
        $this->search->index($event->discussion);
    }

    public function onStarted(Started $event): void
    {
        $this->search->index($event->discussion);
    }
}
