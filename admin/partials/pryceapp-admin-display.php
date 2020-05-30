<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.pryce.app
 * @since      1.0.0
 *
 * @package    Pryceapp
 * @subpackage Pryceapp/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
    <h1 class="wp-heading-inline"><?= __('Discounts'); ?></h1>
    <a href="#!" class="page-title-action"><?= __('Adicionar novo'); ?></a>
    <hr class="wp-header-end">
    <table class="wp-list-table widefat fixed striped posts">
        <thead>
            <tr>
                <td id="cb" class="manage-column column-cb check-column">
                    <label class="screen-reader-text" for="cb-select-all-1">Selecionar todos</label>
                    <input id="cb-select-all-1" type="checkbox">
                </td>
                <th scope="col" id="name" class="manage-column column-name column-primary sortable desc">
                    <a href="#!">
                        <span><?= __('Nome') ?></span>
                        <span class="sorting-indicator"></span>
                    </a>
                </th>
                <th scope="col" id="sku" class="manage-column column-sku"><span><?= __('Código') ?></span></th>
                <th scope="col" id="is_in_stock" class="manage-column column-is_in_stock"><?= __('Acumulativo') ?></th>
                <th scope="col" id="is_in_stock" class="manage-column column-is_in_stock"><?= __('Ativo') ?></th>
                <th scope="col" id="price" class="manage-column column-price sortable desc">
                    <a href="#!">
                        <span><?= __('Tipo desconto') ?></span>
                        <span class="sorting-indicator"></span>
                    </a>
                </th>
                <th scope="col" id="product_cat" class="manage-column"><?= __('Valor do desconto') ?></th>
                <th scope="col" id="product_tag" class="manage-column column-date"><?= __('Início') ?></th>
                <th scope="col" id="date" class="manage-column column-date"><?= __('Término') ?></th>
            </tr>
        </thead>

        <tbody id="the-list">
            <?php foreach ($discounts as $discount) : ?>
                <tr class="iedit author-self level-0">
                    <th scope="row" class="check-column">
                        <label class="screen-reader-text" for="cb-select-31">
                            Selecionar <?= $discount->name ?>
                        </label>
                        <input id="cb-select-31" type="checkbox" name="post[]" value="">
                    </th>
                    <td class="name column-name has-row-actions column-primary" data-colname="Nome">
                        <strong>
                            <a class="row-title" href="#">
                                <?= $discount->name ?>
                            </a>
                        </strong>
                        <div class="row-actions">
                            <span class="edit">
                                <a href="#!" aria-label=""><?= __('Editar') ?></a> |
                            </span>
                            <span class="trash">
                                <a href="#!" class="submitdelete" aria-label="">
                                    <?= __('Lixeira') ?>
                                </a> |
                            </span>
                            <span class="view">
                                <a href="#1" rel="bookmark" aria-label="">
                                    <?= __('Desativar') ?>
                                </a>
                            </span>
                        </div>
                        <button type="button" class="toggle-row">
                            <span class="screen-reader-text"><?= __('Mostrar mais detalhes') ?></span>
                        </button>
                    </td>
                    <td class="sku column-sku" data-colname="REF"><?= $discount->code ?></td>
                    <td class="sku column-sku">
                        <?= $discount->is_cumulative ? __('Sim') : __('Não') ?>
                    </td>
                    <td class="is_in_stock">
                        <mark class="instock"><?= $discount->is_active ? __('Sim') : __('Não') ?></mark>
                    </td>
                    <td class="product_cat column-product_cat" data-colname="discount_type">
                        <?= $discount->discount_type ?>
                    </td>
                    <td class="price column-price" data-colname="Preço">
                        <span class="woocommerce-Price-amount amount">
                            <span class="woocommerce-Price-currencySymbol">R$ </span><?= $discount->discount_value ?>
                        </span>
                    </td>
                    <td class="date column-date" data-colname="Data">
                        <?php $start = date_format(date_create($discount->start_at), 'd/m/Y') ?>
                        <span title="<?= $start ?>"><?= $start ?></span>
                    </td>
                    <td class="date column-date" data-colname="Data">
                        <?php $end = date_format(date_create($discount->end_at), 'd/m/Y') ?>
                        <span title="<?= $end ?>"><?= $end ?></span>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>

        <tfoot>
            <tr>
                <td class="manage-column column-cb check-column">
                    <label class="screen-reader-text" for="cb-select-all-2">Selecionar todos</label>
                    <input id="cb-select-all-2" type="checkbox">
                </td>
                <th scope="col" class="manage-column column-name column-primary sortable desc">
                    <a href="#!">
                        <span><?= __('Nome') ?></span>
                        <span class="sorting-indicator"></span>
                    </a>
                </th>
                <th scope="col" class="manage-column column-sku sortable desc">
                    <a href="http://localhost:8888/wordpress/wp-admin/edit.php?post_type=product&amp;orderby=sku&amp;order=asc">
                        <span><?= __('Código') ?></span>
                        <span class="sorting-indicator"></span>
                    </a>
                </th>
                <th scope="col" id="is_in_stock" class="manage-column column-is_in_stock"><?= __('Acumulativo') ?></th>
                <th scope="col" class="manage-column column-is_in_stock"><?= __('Ativo') ?></th>
                <th scope="col" class="manage-column column-price sortable desc">
                    <a href="#!">
                        <span><?= __('Tipo desconto') ?></span>
                        <span class="sorting-indicator"></span>
                    </a>
                </th>
                <th scope="col" class="manage-column column-product_cat"><?= __('Valor do desconto') ?></th>
                <th scope="col" class="manage-column column-date"><?= __('Início') ?></th>
                <th scope="col" class="manage-column column-date"><?= __('Término') ?></th>
            </tr>
        </tfoot>
    </table>
</div>