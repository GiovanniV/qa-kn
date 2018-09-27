<?php
/**
 *
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Born\Customapi\Api;

/**
 * @api
 */
interface ProductManagementInterface
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
