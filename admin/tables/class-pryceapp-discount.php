<?php

if (!class_exists('Link_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

/**
 * TODO: Create a table class to render all discounts
 */
class Discount_List_Table extends WP_List_Table
{
    public function __construct()
    {
        parent::__construct([
            'singular'  => 'discount',
            'plural'    => 'discounts',
            'ajax'      => false
        ]);
    }

    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'name':
            case 'code':
            case 'is_cumulative':
            case 'is_active':
                return "<strong>" . $item[$column_name] . "</strong>";
            default:
                return print_r($item, true);
        }
    }

    public function get_sortable_columns()
    {
        return [
            'is_cumulative' => array('is_cumulative', true),
            'is_active'     => array('is_active', true)
        ];
    }

    public function get_hidden_columns()
    {
        return [];
    }

    function first_column_name($item)
    {
        $actions = array(
            'edit'      => sprintf('<a href="?page=pryceapp&discount=%s">Edit</a>', $item['id']),
            'trash'     => sprintf('<a href="?page=pryceapp&action=trash&discount=%s">Trash</a>', $item['id']),
        );
        return sprintf('%1$s %2$s', $item['first_column_name'], $this->row_actions($actions));
    }
}
