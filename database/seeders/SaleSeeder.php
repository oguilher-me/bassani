<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sale;
use App\Models\Customer;
use App\Models\Representative;
use App\Models\Carrier;
use App\Models\PaymentTerm;
use App\Models\Product;
use App\Enums\SalesDivisionEnum;
use App\Enums\OrderStatusEnum;
use App\Enums\DeliveryStatusEnum;
use App\Enums\ShippingMethodEnum;
use App\Enums\PaymentStatusEnum;
use App\Enums\PaymentMethodEnum;

class SaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = Customer::all();
        $representatives = Representative::all();
        $carriers = Carrier::all();
        $paymentTerms = PaymentTerm::all();
        $products = Product::all();

        if ($customers->isEmpty()) {
            \App\Models\Customer::factory()->count(10)->create();
            $customers = Customer::all();
        }
        if ($representatives->isEmpty()) {
            \App\Models\Representative::factory()->count(5)->create();
            $representatives = Representative::all();
        }
        if ($carriers->isEmpty()) {
            \App\Models\Carrier::factory()->count(3)->create();
            $carriers = Carrier::all();
        }
        if ($paymentTerms->isEmpty()) {
            \App\Models\PaymentTerm::factory()->count(4)->create();
            $paymentTerms = PaymentTerm::all();
        }
        if ($products->isEmpty()) {
            \App\Models\Product::factory()->count(20)->create();
            $products = Product::all();
        }

        Sale::factory()->count(50)->create()->each(function ($sale) use ($products) {
            $sale->order_status = OrderStatusEnum::cases()[array_rand(OrderStatusEnum::cases())];
            $sale->payment_status = PaymentStatusEnum::cases()[array_rand(PaymentStatusEnum::cases())];
            $sale->delivery_status = DeliveryStatusEnum::cases()[array_rand(DeliveryStatusEnum::cases())];
            $sale->payment_method = PaymentMethodEnum::cases()[array_rand(PaymentMethodEnum::cases())];
            $sale->save();

            $sale->saleItems()->createMany(
                Product::all()->random(rand(1, 5))->map(function ($product) {
                    return [
                        'product_id' => $product->id,
                        'description' => $product->name,
                        'quantity' => $quantity = rand(1, 10),
                        'unit_price' => $unitPrice = $product->base_price,
                        'item_discount' => $itemDiscount = rand(0, 100) / 100,
                        'ipi' => $ipi = rand(0, 100) / 100,
                        'subtotal' => ($quantity * $unitPrice) - $itemDiscount,
                    ];
                })->toArray()
            );

            // Calculate totals for the sale
            $totalItems = 0;
            $totalDiscounts = 0;
            $totalIpi = 0;

            foreach ($sale->saleItems as $item) {
                $subtotal = ($item->quantity * $item->unit_price) - $item->item_discount;
                $totalItems += $subtotal;
                $totalDiscounts += $item->item_discount;
                $totalIpi += ($item->ipi * $item->quantity);
            }

            $sale->total_items = $totalItems;
            $sale->total_discounts = $totalDiscounts;
            $sale->total_ipi = $totalIpi;

            // Assuming some default values for freight, ICMS, etc. for seeding purposes
            $sale->total_freight = rand(10, 100);
            $sale->total_icms = rand(5, 50);
            $sale->total_icms_st = rand(2, 20);
            $sale->total_difal = rand(1, 10);

            $sale->grand_total = $totalItems
                                + $sale->total_freight
                                + $totalIpi
                                + $sale->total_icms
                                + $sale->total_icms_st
                                + $sale->total_difal
                                - $totalDiscounts;
            $sale->save();
        });
    }
}