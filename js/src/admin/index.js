import app from 'flarum/admin/app';

app.initializers.add('zouyonghao/flarum-es', (app) => {
  app.extensionData
    .for('zouyonghao-es')
    .registerSetting({
      setting: 'rrmode-elasticsearch.username',
      label: app.translator.trans('rrmode-elasticsearch.admin.settings.username'),
      type: 'string',
    })
    .registerSetting({
      setting: 'rrmode-elasticsearch.password',
      label: app.translator.trans('rrmode-elasticsearch.admin.settings.password'),
      type: 'password',
    })
    .registerSetting({
      setting: 'rrmode-elasticsearch.cloud_id',
      label: app.translator.trans('rrmode-elasticsearch.admin.settings.cloud_id'),
      type: 'string',
    })
    .registerSetting({
      setting: 'rrmode-elasticsearch.api_key',
      label: app.translator.trans('rrmode-elasticsearch.admin.settings.api_key'),
      type: 'string',
    })
    .registerSetting({
      setting: 'rrmode-elasticsearch.api_id',
      label: app.translator.trans('rrmode-elasticsearch.admin.settings.api_id'),
      type: 'string',
    })
    .registerSetting({
      setting: 'rrmode-elasticsearch.hosts',
      label: app.translator.trans('rrmode-elasticsearch.admin.settings.hosts'),
      type: 'string',
    })
    .registerSetting({
      setting: 'rrmode-elasticsearch.static_mapping',
      label: app.translator.trans('rrmode-elasticsearch.admin.settings.static_mapping'),
      help: app.translator.trans('rrmode-elasticsearch.admin.unavailable'),
      type: 'boolean',
      value: false,
      default: false,
      disabled: true
    })
});
