<?php

/*
 * This file is part of rrmode/flarum-elasticsearch.
 *
 * Copyright (c) 2021 rrmode.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Rrmode\FlarumES\Provider;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Flarum\Foundation\AbstractServiceProvider;
use Flarum\Post\Post;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class Elasticsearch extends AbstractServiceProvider
{
    public function register(): void
    {
        $this->registerClient();
        $this->registerPostMacro();
    }

    public function boot(): void
    {
        //
    }

    protected function registerPostMacro(): void
    {
        Builder::macro('document', function () {
            $model = $this->getModel();

            if ($model instanceof Post) {
                return [
                    'content' => $model->content,
                    'discussion_id' => $model->discussion_id,
                    'count' => $model->discussion->posts->count(),
                    'created_at' => $model->created_at,
                    'started_at' => $model->discussion->created_at
                ];
            }
            unset(static::$macros['document']);
            throw new \Exception("Document macro not declared for $model class");
        });
    }

    protected function registerClient(): void
    {
        $this->container->singleton(Client::class, static function ($app) {
            $settings = $app->make(SettingsRepositoryInterface::class);

            $builder = ClientBuilder::create();

            $username = $settings->get('es.username');
            $password = $settings->get('es.password');
            $cloud_id = $settings->get('es.cloud_id');
            $api_key = $settings->get('es.api_key');
            $api_id = $settings->get('es.api_id');
            $hosts = $settings->get('es.hosts', '198.51.101.10:9200');

            if ($cloud_id !== null) {
                $builder->setElasticCloudId($cloud_id);
            }

            if ($username !== null && $password !== null) {
                $builder->setBasicAuthentication($username, $password);
            }

            if ($api_key !== null && $api_id !== null) {
                $builder->setApiKey($api_id, $api_key);
            }

            if ($hosts !== null) {
                if (is_array($hosts) === false) {
                    $hosts = [(string)$hosts];
                }
                $builder->setHosts($hosts);
            }

            return $builder->build();
        });
    }
}
