# Configuration
Values are defined through XML-files in the folder `app/design/adminhtml/default/default/template/producttemplates/`.
The folder contains some example files. At a minimum you need to create a `default.xml` file. 
Optionally, you can create XML-files per product-type, like `simple.xml`.

An XML file `default.xml` might look like this:

```xml
<?xml version="1.0"?>
<product>
    <status>1</status>    
    <tax_class_id>2</tax_class_id>    
    <weight>1</weight>    
    <cb_status>unknown_pending</cb_status>    
</product>
```
