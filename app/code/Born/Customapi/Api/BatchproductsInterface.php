<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Born\Customapi\Api;


/**
 * Gateway vault payment token repository interface.
 *
 * @api
 * @since 100.1.0
 */
 interface BatchproductsInterface
{
    /**
     * Return a list of IDs with SKU association
     *
     * @api
     * @return int The sum of the SKUs.
     */
    public function add();
 
    /**
     * Return a true/false after update with list of SKU associations
     *
     * @api
     * @return int The sum of the SKUs.
     */
    public function update();
}