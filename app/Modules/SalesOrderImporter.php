<?php

namespace App\Modules;

use App\Models\Customer;
use App\Models\Sale;
use App\Models\SaleItem;
use DateTimeImmutable;
use Exception;

class SalesOrderImporter
{
    private array $tables;

    public function __construct(private string $filePath, private ?int $userId = null)
    {
        $this->tables = $this->loadAndParseFile();
    }

    /**
     * Load and parse the HTML file (which is actually an .xls file)
     *
     * @return array Array of tables, each table is a 2D array of rows/cells
     */
    private function loadAndParseFile(): array
    {
        $content = file_get_contents($this->filePath);
        if ($content === false) {
            throw new Exception("Could not read file: {$this->filePath}");
        }

        // Handle UTF-8 BOM if present
        if (substr($content, 0, 3) == pack('CCC', 0xEF, 0xBB, 0xBF)) {
            $content = substr($content, 3);
        }

        // Detect encoding and convert to UTF-8
        $encoding = mb_detect_encoding($content, ['UTF-8', 'UTF-16', 'UTF-16LE', 'UTF-16BE'], true);
        if ($encoding === false) {
            $encoding = 'UTF-8';
        }
        $content = mb_convert_encoding($content, 'UTF-8', $encoding);

        // Parse HTML to extract table data
        $dom = new \DOMDocument;
        @$dom->loadHTML($content);

        $htmlTables = $dom->getElementsByTagName('table');
        if ($htmlTables->length === 0) {
            throw new Exception('No table found in the file.');
        }

        $tables = [];
        foreach ($htmlTables as $htmlTable) {
            $rows = [];
            $tableRows = $htmlTable->getElementsByTagName('tr');

            foreach ($tableRows as $row) {
                $cells = [];
                $tableCells = $row->getElementsByTagName('td');
                foreach ($tableCells as $cell) {
                    $cells[] = $cell->nodeValue;
                }
                $rows[] = $cells;
            }
            $tables[] = $rows;
        }

        return $tables;
    }

    /**
     * Get a specific table by index (0-based)
     */
    private function getTable(int $index): array
    {
        return $this->tables[$index] ?? [];
    }

    /**
     * Find a value in a table by searching for a label
     */
    private function findValueByLabel(array $table, string $label): string
    {
        foreach ($table as $row) {
            foreach ($row as $index => $cell) {
                if (str_contains(trim($cell ?? ''), $label)) {
                    return trim($row[$index + 1] ?? '');
                }
            }
        }

        return '';
    }

    /**
     * Parse Brazilian date format (dd/mm/yyyy)
     */
    private function parseBrDate(string $date): ?DateTimeImmutable
    {
        if (empty($date)) {
            return null;
        }
        $date = trim($date);
        if (! preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $date, $matches)) {
            return null;
        }

        return new DateTimeImmutable(sprintf('%s-%s-%s', $matches[3], $matches[2], $matches[1]));
    }

    /**
     * Parse Brazilian decimal format (using comma as decimal separator)
     */
    private function parseBrDecimal(string $value): float
    {
        if (empty($value)) {
            return 0.0;
        }
        $value = trim($value);
        $value = preg_replace('/[^0-9,.-]/', '', $value);
        $value = str_replace('.', '', $value);
        $value = str_replace(',', '.', $value);

        return (float) $value;
    }

    /**
     * Find customer by CNPJ
     *
     * @return array|null Customer data or null if not found
     */
    private function findCustomerByCnpj(string $cnpj): ?array
    {
        if (empty($cnpj)) {
            return null;
        }
        $customer = Customer::where('cnpj', $cnpj)->first();

        return $customer ? $customer->toArray() : null;
    }

    /**
     * Create a new customer
     *
     * @return int The created customer ID
     */
    private function createCustomer(array $customerData): int
    {

        // dd($customerData);
        if (empty($customerData['cnpj'])) {
            throw new Exception('CNPJ do cliente não encontrado no arquivo.');
        }

        $address = explode(',', $customerData['address']);
        $mappedData = [
            'customer_type' => 'PJ',
            'company_name' => $customerData['name'] ?? '',
            'representative_name' => $customerData['representative_name'] ?? '',
            'cnpj' => $customerData['cnpj'] ?? '',
            'ie' => $customerData['state_registration'] ?? '',
            'phone' => $customerData['phone'] ?? '',
            'email' => $customerData['email'] ?? '',
            'address_street' => $address[0] ?? '',
            'address_number' => $address[1] ?? '',
            'address_type' => 'comercial',
            'address_neighborhood' => $customerData['neighborhood'] ?? '',
            'address_city' => $customerData['city'] ?? '',
            'address_state' => $customerData['state'] ?? '',
            'address_zip_code' => $customerData['zipcode'] ?? '',
            'status' => 'active',
        ];

        $customer = Customer::create($mappedData);

        return $customer->id;
    }

    /**
     * Get carrier ID by name
     */
    private function getCarrierIdByName(string $carrierName): ?int
    {
        if (empty($carrierName)) {
            return null;
        }
        $carrier = \App\Models\Carrier::where('name', 'like', "%{$carrierName}%")->first();

        return $carrier?->id;
    }

    /**
     * Get payment term ID by name
     */
    private function getPaymentTermIdByName(string $paymentTermName): ?int
    {
        if (empty($paymentTermName)) {
            return null;
        }
        $paymentTerm = \App\Models\PaymentTerm::where('name', 'like', "%{$paymentTermName}%")->first();

        return $paymentTerm?->id;
    }

    /**
     * Get product ID by code
     */
    private function getProductIdByCode(string $productCode): ?int
    {
        if (empty($productCode)) {
            return null;
        }
        $product = \App\Models\Product::where('code', $productCode)->first();

        return $product?->id;
    }

    /**
     * Get or create product by code
     */
    private function getOrCreateProductId(string $productCode, string $description): int
    {
        if (empty($productCode)) {
            return 1;
        }
        $product = \App\Models\Product::where('sku', $productCode)->first();

        if ($product) {
            return $product->id;
        }

        $newProduct = \App\Models\Product::create([
            'sku' => $productCode,
            'name' => $productCode,
            'description' => $description,
            'unit_of_measure' => 'UN',
            'base_price' => 0,
            'gross_weight' => 0,
            'net_weight' => 0,
            'cubic_volume' => 0,
        ]);

        return $newProduct->id;
    }

    /**
     * Import the sales order from the file
     *
     * @return int|false The created sale ID on success, false on failure
     */
    public function import()
    {
        try {
            $get = fn (?array $row, int $idx, string $default = '') => ($row && isset($row[$idx])) ? $row[$idx] : $default;

            $table0 = $this->getTable(0);
            $table2 = $this->getTable(2);
            $table4 = $this->getTable(4);
            $table6 = $this->getTable(6);
            $table7 = $this->getTable(7);

            $orderNumber = trim($table0[3][1]);

            // SALE DATA (Table 2 - 3rd table)
            $deliveryDate = $this->parseBrDate($table2[2][2] ?? '');
            $emissionDate = $this->parseBrDate($table2[3][2] ?? '');
            $responsible = $table2[4][2] ?? '';
            $representative = $table2[5][2] ?? '';
            $salesDivision = $table2[6][2] ?? '';
            $carrier = $table2[7][2] ?? '';
            $paymentCond = $table2[8][2] ?? '';
            $contact = $table2[10][2] ?? '';
            $purchaseOrder = $table2[11][2] ?? '';
            $observations = $table2[12][2] ?? '';

            // dd($table4);
            // CUSTOMER DATA (Table 4 - 5th table)
            $customerName = $table4[2][2];
            $customerRaw = $customerName;
            // dd($customerName);
            preg_match('/^(\d+)\s*-\s*(.+)$/', $customerRaw, $m);
            $extCode = $m[1] ?? null;
            $customerName = $m[2] ?? $customerName;

            //  dd($customerName);

            $cnpjRaw = $table4[3][2];
            $cnpj = preg_replace('/\D/', '', $cnpjRaw);
            $stateReg = $this->findValueByLabel($table4, 'Inscrição Estadual:');
            $address = $table4[5][2];
            $neighborhood = $table4[6][2];
            $zipcode = $table4[7][2];
            $cityState = $table4[8][2];
            preg_match('/^(.+?)\s*-\s*([A-Z]{2})$/', $cityState, $csm);
            $city = trim($csm[1] ?? $cityState);
            $state = trim($csm[2] ?? '');
            $phone = $table4[9][2];

            // ITENS (Table 6 - 7th table)
            $items = [];

            $item = [
                'item_number' => (int) $table6[2][1] ?? '',
                'product_code' => $table6[2][2] ?? '',
                'unit' => $table6[2][4] ?? 'UN',
                'quantity' => $this->parseBrDecimal($table6[2][6] ?? '0'),
                'ipi_value' => $this->parseBrDecimal($table6[2][8] ?? '0'),
                'ipi_percent' => $table6[2][10] ?? '0,00%',
                'unit_price' => $this->parseBrDecimal($table6[2][12] ?? '0'),
                'total_price' => $this->parseBrDecimal($table6[2][14] ?? '0'),
                'short_description' => $table6[3][0] ?? '',
                'full_description' => $table6[5][0] ?? '',
            ];

            $items[] = $item;

            // TOTAIS (Table 7 - 8th table)
            $totalItems = 0;
            $grossWeight = 0;
            $totalDiscount = 0;
            $netWeight = 0;
            $totalFreight = 0;
            $cubage = 0;
            $totalIpi = 0;
            $volumes = 0;
            $totalIcms = 0;
            $totalIcmsSt = 0;
            $totalDifal = 0;
            $totalValue = 0;

            // dd($table7);

            $totalItems = $this->parseBrDecimal($table7[3][2]);
            $grossWeight = $this->parseBrDecimal($table7[3][5]);
            $totalDiscount = $this->parseBrDecimal($table7[4][2]);
            $netWeight = $this->parseBrDecimal($table7[4][5]);
            $totalFreight = $this->parseBrDecimal($table7[5][2]);
            $cubage = $this->parseBrDecimal($table7[5][5]);
            $totalIpi = $this->parseBrDecimal($table7[6][2]);
            $volumes = (int) $this->parseBrDecimal($table7[6][5]);
            $totalIcms = $this->parseBrDecimal($table7[7][2]);
            $totalIcmsSt = $this->parseBrDecimal($table7[8][2]);
            $totalDifal = $this->parseBrDecimal($table7[9][2]);
            $totalValue = $this->parseBrDecimal($table7[11][2]);

            // Customer
            $existingCustomer = $this->findCustomerByCnpj($cnpj);
            if ($existingCustomer) {
                $customerId = $existingCustomer['id'];
            } else {
                $customerData = [
                    'name' => $customerName,
                    'full_name' => $customerName,
                    'cnpj' => $cnpj,
                    'state_registration' => $stateReg,
                    'representative_name' => $representative,
                    'address' => $address,
                    'neighborhood' => $neighborhood,
                    'zipcode' => $zipcode,
                    'city' => $city,
                    'state' => $state,
                    'phone' => $phone,
                    'email' => '-',
                    'external_code' => $extCode,
                ];
                $customerId = $this->createCustomer($customerData);
            }

            // Sale
            $saleData = [
                'customer_id' => $customerId,
                'issue_date' => $emissionDate ? $emissionDate->format('Y-m-d') : date('Y-m-d'),
                'expected_delivery_date' => $deliveryDate ? $deliveryDate->format('Y-m-d') : null,
                'sales_responsible' => $responsible,
                'sales_division' => \App\Enums\SalesDivisionEnum::Retail->value,
                'carrier_id' => $this->getCarrierIdByName($carrier) ?? 1,
                'payment_term_id' => $this->getPaymentTermIdByName($paymentCond) ?? 1,
                'contact_name' => $customerName,
                'contact_email' => '-',
                'contact_phone' => $phone,
                'payment_method' => \App\Enums\PaymentMethodEnum::Cash->value,
                'currency' => 'BRL',
                'erp_code' => $orderNumber,
                'notes' => $observations,
                'total_items' => $totalItems,
                'total_discounts' => $totalDiscount,
                'total_ipi' => $totalIpi,
                'shipping_cost' => $totalFreight,
                'grand_total' => $totalValue,
                'total_weight' => $netWeight,
                'total_volume' => $cubage,
                'total_packages' => $volumes,
                'order_status' => \App\Enums\OrderStatusEnum::Open->value,
                'delivery_status' => \App\Enums\DeliveryStatusEnum::Pending->value,
                'shipping_method' => \App\Enums\ShippingMethodEnum::Proprio->value,
                'representative_id' => $this->userId,
            ];

            $sale = Sale::create($saleData);
            $saleId = $sale->id;

            // Items
            foreach ($items as $item) {
                $itemData = [
                    'sale_id' => $saleId,
                    'product_id' => $this->getOrCreateProductId($item['product_code'], $item['short_description'].' '.$item['full_description']),
                    'description' => trim($item['short_description'].' '.$item['full_description']),
                    'IPI' => $this->parseBrDecimal($item['ipi_percent']),
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'item_discount' => 0,
                    'subtotal' => $item['total_price'],
                ];
                SaleItem::create($itemData);
            }

            return (int) $saleId;
        } catch (Exception $e) {
            throw new Exception('Sales order import failed: '.$e->getMessage());
        }
    }
}
