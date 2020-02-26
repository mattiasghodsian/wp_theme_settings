# 2. Getting Started

Call wp_theme_settings class in your theme or plugin file like below

```php
$wpts = new wp_theme_settings(
  [
    'general' => [],
    'settingsID' => '',
    'settingFields' => [], 
    'tabs' => [],
    'badge' => []
  ]
);
```

## 2.1. Options

Read more about [capability](https://codex.wordpress.org/Roles_and_Capabilities#manage_options) with WordPress

| Key         | Type    | Required                | Default                    | Options                                  |
| :---------- | :------ | :---------------------- | :------------------------- | :--------------------------------------- |
| title       | string  | no                      | @theme-name Theme Settings |                                          |
| description | string  | no                      |                            |                                          |
| menu_title  | string  | no                      | Theme Settings             |                                          |
| menu_slug   | string  | no                      | wp-theme-settings          |                                          |
| menu_type   | string  | no                      | theme                      | theme options submenu                    |
| menu_parent | string  | if menu_type is submenu |                            |                                          |
| capability  | string  | no                      | manage_options             |                                          |
| toolbar     | array   | no                      | false                      | toolbar_title toolbar_image toolbar_href |
| notice      | boolean | no                      | true                       | false                                    |

## 2.2. Settings ID & Fields

**settingsID** is a settings group name (required).

```php
'settingsID' => 'my-theme-settings'
```

**settingFields** contains an array with option names (required if building tabs manually).

```php
'settingFields' => []
```

## 2.3. Tabs

Read more about WordPress [dashicons](https://developer.wordpress.org/resource/dashicons/)

```php
'tabs' => [
    'general' => []
        'text' => 'General', 
        'dashicon' => 'dashicons-admin-generic',
        'tabFields' => []
      ],
  )
```

Tab with Sections

```php
'tabs' => [
    'general' => [
        'text' => 'General', 
        'dashicon' => 'dashicons-admin-generic',
        'tabFields' => [],
        'sections' => [
            'hooks' => [
              'text' => 'Hooks', 
              'tabFields' => []
            ],
          ],
      ],
  ]
```

## 2.4. Badge

Badge is nice way to display your logo (optional).

| Key      | Type           | Required | Default   | Comment                   |
| :------- | :------------- | :------- | :-------- | :------------------------ |
| bg-image | uri            | no       | WPTS logo | Uri to your logo          |
| bg-color | Hex Color Code | no       | #1d6b8e   | You must include the hash |
| version  | string         | no       | true      | true or false             |

Example

```php
'badge' => [
    'bg-image' => 'http://i.imgur.com/AvANSYy.png',
    'bg-color' => '#1d6b8e'
]
```

**Go to step >> [3. Fields](Fields.md)**