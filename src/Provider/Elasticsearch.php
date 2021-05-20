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
use Flarum\Settings\SettingsRepositoryInterface;

/**
 * Loading some useful things
 * @package Rrmode\FlarumES\Provider
 */
class Elasticsearch extends AbstractServiceProvider
{
    public function register(): void
    {
        $this->registerClient();
    }

    public function boot(): void
    {
        //
    }

    protected function registerClient(): void
    {
        $this->container->singleton(Client::class, static function ($app) {
            $settings = $app->make(SettingsRepositoryInterface::class);

            $builder = ClientBuilder::create();

            $username = $settings->get('rrmode-elasticsearch.username');
            $password = $settings->get('rrmode-elasticsearch.password');
            $cloud_id = $settings->get('rrmode-elasticsearch.cloud_id');
            $api_key = $settings->get('rrmode-elasticsearch.api_key');
            $api_id = $settings->get('rrmode-elasticsearch.api_id');
            $hosts = $settings->get('rrmode-elasticsearch.hosts');

            if ($cloud_id !== null && !empty($cloud_id)) {
                $builder->setElasticCloudId($cloud_id);
            }

            if (
                $username !== null &&
                !empty($username) &&
                $password !== null &&
                !empty($password)
            ) {
                $builder->setBasicAuthentication($username, $password);
            }

            if (
                $api_key !== null &&
                !empty($api_key) &&
                $api_id !== null &&
                !empty($api_id)
            ) {
                $builder->setApiKey($api_id, $api_key);
            }

            if ($hosts !== null && !empty($hosts)) {
                if (is_array($hosts) === false) {
                    $hosts = [(string)$hosts];
                }
                $builder->setHosts($hosts);

            }

            return $builder->build();
        });
    }
}
