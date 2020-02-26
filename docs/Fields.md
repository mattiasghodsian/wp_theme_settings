# 3. Fields

Tab Fields is more or less an autobuilder, define your input types and it will generate a table with all your inputs.

## 3.1. Text Box

| Type (Required) | Label             | Name              | Class             | Description     | Tooltip         |
| :-------------- | :---------------- | :---------------- | :---------------- | :-------------- | :-------------- |
| text            | string (Optional) | string (Required) | string (Optional) | text (Optional) | text (Optional) |

Example

```php
[
    'type' => 'text', 
    'label' => '',
    'name' => '' ,
    'class' => '',
    'description' => '',
    'tooltip' => ''
]
```

## 3.2. Color Picker

| Type (Required) | Label             | Name              | Class             | Description     |
| :-------------- | :---------------- | :---------------- | :---------------- | :-------------- |
| color           | string (Optional) | string (Required) | string (Optional) | text (Optional) |

Example

```php
[
    'type' => 'color', 
    'label' => '',
    'name' => '',
    'class' => '',
    'description' => ''
]
```

## 3.3. Select (Dropdown)

| Type (Required) | Label             | Name              | Options          | Class             | Description     | Tooltip         |
| :-------------- | :---------------- | :---------------- | :--------------- | :---------------- | :-------------- | :-------------- |
| select          | string (Optional) | string (Required) | array (Required) | string (Optional) | text (Optional) | text (Optional) |

Example

```
[
    'type' => 'select', 
    'label' => '',
    'name' => '' ,
    'options' => array('key' => 'name'),
    'class' => '',
    'description' => '',
    'tooltip' => ''
]
```

## 3.4. Radio 

| Type (Required) | Label             | Name              | Options          | Description     |
| :-------------- | :---------------- | :---------------- | :--------------- | :-------------- |
| radio           | string (Optional) | string (Required) | array (Required) | text (Optional) |

Example

```php
[
    'type' => 'radio', 
    'label' => '',
    'name' => '' ,
    'options' => array('key' => 'name'),
    'description' => ''
]
```

## 3.5. Checkbox

| Type (Required) | Label             | Name              | Text              | Value             | Description     |
| :-------------- | :---------------- | :---------------- | :---------------- | :---------------- | :-------------- |
| checkbox        | string (Optional) | string (Required) | string (Required) | string (Required) | text (Optional) |

Example

```php
[
    'type' => 'checkbox', 
    'label' => '', 
    'name' => '',
    'text' => '',
    'value' => 1,
    'description' => '' 
]
```

## 3.6. Toggle Button

| Type (Required) | Label             | Name              | Value             | Description     | Tooltip         |
| :-------------- | :---------------- | :---------------- | :---------------- | :-------------- | :-------------- |
| checkbox        | string (Optional) | string (Required) | string (Required) | text (Optional) | text (Optional) |

Example

```php
[
  'type' => 'toggle', 
  'label' => '',
  'name' => '',
  'value' => 1,
  'description' => '',
  'tooltip' => ''
]
```

## 3.7. FontAwesome Icon Picker

| Type (Required) | Label             | Name              | Class             | Description     |
| :-------------- | :---------------- | :---------------- | :---------------- | :-------------- |
| fa              | string (Optional) | string (Required) | string (Optional) | text (Optional) |

Example

```php
[
  'type' => 'fa', 
  'label' => '', 
  'name' => '' ,
  'class' => '', 
  'description' =>  '' 
]
```

## 3.8. File (Choose/upload)

| Type (Required) | Label             | Name              | Class             | Preview            | Description     |
| :-------------- | :---------------- | :---------------- | :---------------- | :----------------- | :-------------- |
| file            | string (Optional) | string (Required) | string (Optional) | boolean (Optional) | text (Optional) |

Example

```php
[
    'type' => 'file', 
    'label' => '', 
    'name' => '',
    'class' => '',
    'preview' => false,
    'description' => ''
]
```

**Go to step >> [4. Hooks](Hooks.md)**