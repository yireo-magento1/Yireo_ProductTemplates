/**
 * Yireo ProductTemplates for Magento
 *
 * @package     Yireo_ProductTemplates
 * @author      Yireo (https://www.yireo.com/)
 * @copyright   Copyright 2016 Yireo (https://www.yireo.com/)
 * @license     Open Source License (OSL v3)
 */

var ProductTemplates = (function () {
    var values = {};

    var setValues = function (values) {
        this.values = values;
        return this;
    };

    var parse = function () {
        console.log(this.values.category_ids);

        if (this.values.stock_data.use_config_manage_stock == 0) {
            document.getElementById('inventory_use_config_manage_stock').checked = false;
        }

        if (this.values.stock_data.manage_stock == 1) {
            document.getElementById('inventory_manage_stock').disabled = false;
        }

        return this;
    };

    return {
        setValues: setValues,
        parse: parse
    };
}());