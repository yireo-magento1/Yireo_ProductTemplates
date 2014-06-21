Yireo ProductTemplates
=======================
This Magento extension allows you to supply default values for new products that you create in the Magento backend.
When you create a new product, this extension allows you to load default values for product attributes, 
like the product status, product title, tax class, weight. 

Values are defined through XML-files in the folder `app/design/adminhtml/default/default/template/producttemplates/`.
The folder contains some example files. At a minimum you need to create a `default.xml` file. 
Optionally, you can create XML-files per product-type, like `simple.xml`.

More information: https://www.yireo.com/software/magento-extensions/producttemplates

You can install this module in various ways:

1) Download the MagentoConnect package from our site and upload it into your own Magento
Downloader application.

2) Download the Magento source archive from our site, extract the files and upload the
files to your Magento root. Make sure to flush the Magento cache. Make sure to logout 
once you're done.

3) Use modman to install the git repository for you:

    modman init
    modman clone https://github.com/yireo/Yireo_ProductTemplates
    modman update Yireo_ProductTemplates

