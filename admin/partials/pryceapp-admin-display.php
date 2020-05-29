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
                <th scope="col" id="thumb" class="manage-column column-thumb">
                    <span class="wc-image tips">Imagem</span>
                </th>
                <th scope="col" id="name" class="manage-column column-name column-primary sortable desc">
                    <a href="#!">
                        <span>Nome</span>
                        <span class="sorting-indicator"></span>
                    </a>
                </th>
                <th scope="col" id="sku" class="manage-column column-sku sortable desc">
                    <a href="#!">
                        <span>REF</span>
                        <span class="sorting-indicator"></span>
                    </a>
                </th>
                <th scope="col" id="is_in_stock" class="manage-column column-is_in_stock">Estoque</th>
                <th scope="col" id="price" class="manage-column column-price sortable desc">
                    <a href="#!">
                        <span>Preço</span>
                        <span class="sorting-indicator"></span>
                    </a>
                </th>
                <th scope="col" id="product_cat" class="manage-column column-product_cat">Categorias</th>
                <th scope="col" id="product_tag" class="manage-column column-product_tag">Tags</th>
                <th scope="col" id="featured" class="manage-column column-featured" style="cursor: help;">
                    <span class="wc-featured parent-tips" data-tip="Destaque">Destaque</span>
                </th>
                <th scope="col" id="date" class="manage-column column-date sortable asc">
                    <a href="#!">
                        <span>Data</span>
                        <span class="sorting-indicator"></span>
                    </a>
                </th>
            </tr>
        </thead>

        <tbody id="the-list">
            <tr class="iedit author-self level-0 post-31 type-product status-publish has-post-thumbnail hentry product_cat-tshirts">
                <th scope="row" class="check-column">
                    <label class="screen-reader-text" for="cb-select-31">Selecionar T-Shirt with Logo </label>
                    <input id="cb-select-31" type="checkbox" name="post[]" value="31">
                    <div class="locked-indicator">
                        <span class="locked-indicator-icon" aria-hidden="true"></span>
                        <span class="screen-reader-text">
                            “T-Shirt with Logo” está bloqueado </span>
                    </div>
                </th>
                <td class="thumb column-thumb" data-colname="Imagem">
                    <a href="http://localhost:8888/wordpress/wp-admin/post.php?post=31&amp;action=edit">
                        <img width="150" height="150" src="http://localhost:8888/wordpress/wp-content/uploads/2020/05/t-shirt-with-logo-1-150x150.jpg" class="attachment-thumbnail size-thumbnail" alt="" srcset="http://localhost:8888/wordpress/wp-content/uploads/2020/05/t-shirt-with-logo-1-150x150.jpg 150w, http://localhost:8888/wordpress/wp-content/uploads/2020/05/t-shirt-with-logo-1-300x300.jpg 300w, http://localhost:8888/wordpress/wp-content/uploads/2020/05/t-shirt-with-logo-1-768x768.jpg 768w, http://localhost:8888/wordpress/wp-content/uploads/2020/05/t-shirt-with-logo-1-324x324.jpg 324w, http://localhost:8888/wordpress/wp-content/uploads/2020/05/t-shirt-with-logo-1-416x416.jpg 416w, http://localhost:8888/wordpress/wp-content/uploads/2020/05/t-shirt-with-logo-1-100x100.jpg 100w, http://localhost:8888/wordpress/wp-content/uploads/2020/05/t-shirt-with-logo-1.jpg 800w" sizes="(max-width: 150px) 100vw, 150px">
                    </a>
                </td>
                <td class="name column-name has-row-actions column-primary" data-colname="Nome">
                    <strong>
                        <a class="row-title" href="http://localhost:8888/wordpress/wp-admin/post.php?post=31&amp;action=edit">
                            T-Shirt with Logo
                        </a>
                    </strong>
                    <div class="row-actions">
                        <span class="id">ID: X | </span>
                        <span class="edit">
                            <a href="#!" aria-label="">Editar</a> |
                        </span>
                        <span class="trash">
                            <a href="#!" class="submitdelete" aria-label="">
                                Lixeira
                            </a> |
                        </span>
                        <span class="view">
                            <a href="#1" rel="bookmark" aria-label="">
                                Desativar
                            </a>
                        </span>
                    </div>
                    <button type="button" class="toggle-row">
                        <span class="screen-reader-text">Mostrar mais detalhes</span>
                    </button>
                </td>
                <td class="sku column-sku" data-colname="REF">Woo-tshirt-logo</td>
                <td class="is_in_stock column-is_in_stock" data-colname="Estoque">
                    <mark class="instock">Em estoque</mark>
                </td>
                <td class="price column-price" data-colname="Preço">
                    <span class="woocommerce-Price-amount amount">
                        <span class="woocommerce-Price-currencySymbol">R$</span>18,00
                    </span>
                </td>
                <td class="product_cat column-product_cat" data-colname="Categorias">
                    <a href="http://localhost:8888/wordpress/wp-admin/edit.php?product_cat=tshirts&amp;post_type=product ">
                        Tshirts
                    </a>
                </td>
                <td class="product_tag column-product_tag" data-colname="Tags">
                    <span class="na">–</span>
                </td>
                <td class="featured column-featured" data-colname="Destaque">
                    <a href="http://localhost:8888/wordpress/wp-admin/admin-ajax.php?action=woocommerce_feature_product&amp;product_id=31&amp;_wpnonce=17bef6a2f8" aria-label="Alternar destaque">
                        <span class="wc-featured not-featured tips">Não</span>
                    </a>
                </td>
                <td class="date column-date" data-colname="Data">
                    Publicado<br>
                    <span title="27/05/2020 18:20:36">40 minutos atrás</span>
                </td>
            </tr>
        </tbody>

        <tfoot>
            <tr>
                <td class="manage-column column-cb check-column">
                    <label class="screen-reader-text" for="cb-select-all-2">Selecionar todos</label>
                    <input id="cb-select-all-2" type="checkbox">
                </td>
                <th scope="col" class="manage-column column-thumb">
                    <span class="wc-image tips">Imagem</span>
                </th>
                <th scope="col" class="manage-column column-name column-primary sortable desc">
                    <a href="http://localhost:8888/wordpress/wp-admin/edit.php?post_type=product&amp;orderby=title&amp;order=asc">
                        <span>Nome</span>
                        <span class="sorting-indicator"></span>
                    </a>
                </th>
                <th scope="col" class="manage-column column-sku sortable desc">
                    <a href="http://localhost:8888/wordpress/wp-admin/edit.php?post_type=product&amp;orderby=sku&amp;order=asc">
                        <span>REF</span>
                        <span class="sorting-indicator"></span>
                    </a>
                </th>
                <th scope="col" class="manage-column column-is_in_stock">Estoque</th>
                <th scope="col" class="manage-column column-price sortable desc">
                    <a href="http://localhost:8888/wordpress/wp-admin/edit.php?post_type=product&amp;orderby=price&amp;order=asc">
                        <span>Preço</span>
                        <span class="sorting-indicator"></span>
                    </a>
                </th>
                <th scope="col" class="manage-column column-product_cat">Categorias</th>
                <th scope="col" class="manage-column column-product_tag">Tags</th>
                <th scope="col" class="manage-column column-featured" style="cursor: help;">
                    <span class="wc-featured parent-tips" data-tip="Destaque">Destaque</span>
                </th>
                <th scope="col" class="manage-column column-date sortable asc">
                    <a href="http://localhost:8888/wordpress/wp-admin/edit.php?post_type=product&amp;orderby=date&amp;order=desc">
                        <span>Data</span>
                        <span class="sorting-indicator"></span>
                    </a>
                </th>
            </tr>
        </tfoot>
    </table>
</div>