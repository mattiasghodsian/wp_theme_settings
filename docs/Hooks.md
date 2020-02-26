# 4. Fields

Add html or php code before/after tab or table

## 4.1. Add content before

```php
add_action('wpts_tab_[tab key here]_before' , 'general');
function general(){
    // code here
}
```

## 4.2. Add content after

```php
add_action('wpts_tab_[tab key here]_after' , 'general');
function general(){
    // code here
}
```

## 4.3. Add content before table

```php
add_action('wpts_tab_[tab key here]_table_before' , 'general_table');
function general_table(){
    // code here
}
```

Include tr/td to follow the table structure.

## 4.4. Add content after table

```php
add_action('wpts_tab_[tab key here]_table_after' , 'general_table');
function general_table(){
    // code here
}
```

Include tr/td to follow the table structure.

**Go to step >> [5. Get Option](GetOption.md)**