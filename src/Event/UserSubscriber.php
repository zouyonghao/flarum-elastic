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

use Flarum\User\Event\Activated;
use Flarum\User\Event\AvatarChanged;
use Flarum\User\Event\AvatarDeleting;
use Flarum\User\Event\AvatarSaving;
use Flarum\User\Event\Deleted;
use Illuminate\Events\Dispatcher;

/**
 * Subscriptions on User events for efficiently indexing
 * @package Rrmode\FlarumES\Event
 */
class UserSubscriber extends AbstractSubscriber
{
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(Activated::class, [$this, 'onActivated']);
        $events->listen(AvatarChanged::class, [$this, 'onAvatarChanged']);
        $events->listen(AvatarDeleting::class, [$this, 'onAvatarDeleting']);
        $events->listen(AvatarSaving::class, [$this, 'onAvatarSaving']);
        $events->listen(Deleted::class, [$this, 'onDeleted']);
    }
}
