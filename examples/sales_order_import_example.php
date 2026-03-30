<?php

require_once __DIR__.'/../app/Modules/SalesOrderImporter.php';

// Example usage of the SalesOrderImporter
try {
    // Path to the .xls file (which is actually HTML)
    $filePath = __DIR__.'/../storage/imports/sales_order.xls';

    // Create importer instance
    $importer = new \App\Modules\SalesOrderImporter($filePath);

    // Import the sales order
    $saleId = $importer->import();

    if ($saleId !== false) {
        echo "Sales order imported successfully. Sale ID: {$saleId}".PHP_EOL;
    } else {
        echo 'Failed to import sales order.'.PHP_EOL;
    }
} catch (Exception $e) {
    echo 'Error: '.$e->getMessage().PHP_EOL;
}
