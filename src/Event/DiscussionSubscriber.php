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

use Flarum\Discussion\Event\Deleting;
use Flarum\Discussion\Event\Hidden;
use Flarum\Discussion\Event\Renamed;
use Flarum\Discussion\Event\Restored;
use Flarum\Discussion\Event\Saving;
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
        $events->listen(Deleting::class, [$this, 'onDeleting']);
        $events->listen(Hidden::class, [$this, 'onHidden']);
        $events->listen(Renamed::class, [$this, 'onRenamed']);
        $events->listen(Restored::class, [$this, 'onRestored']);
        $events->listen(Saving::class, [$this, 'onSaving']);
        $events->listen(Started::class, [$this, 'onStarted']);
    }

}
