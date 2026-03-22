<?php

use App\Http\Controllers\DriverController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\AssemblerController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CarBrandController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\DriverDocumentController;
use App\Http\Controllers\Admin\AssemblyExpenseController;
use App\Http\Controllers\Admin\FuelUpController;
use App\Http\Controllers\Admin\MaintenanceController;
use App\Http\Controllers\PlannedShipmentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RepresentativeController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleFineController;
use App\Http\Controllers\Admin\VehicleUsageController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\Admin\FleetReportController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ShipmentTrackingController;
use App\Http\Controllers\Admin\DeliveryWindowController;
use App\Http\Controllers\AssemblyScheduleController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\InteractionController;
use App\Http\Controllers\Admin\TaskController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth', 'role'])->prefix('admin')->group(function () {
    Route::resource('roles', RolePermissionController::class);
    Route::resource('users', UserController::class);
    Route::resource('vehicles', VehicleController::class);
    Route::resource('drivers', DriverController::class);
    Route::resource('drivers.documents', DriverDocumentController::class)->only(['index', 'store', 'destroy']);
    Route::get('assemblers/data', [AssemblerController::class, 'data'])->name('assemblers.data');
    Route::resource('assemblers', AssemblerController::class);
    Route::resource('vehicle_usages', VehicleUsageController::class);
    Route::resource('fuel_ups', FuelUpController::class);
    Route::resource('maintenances', MaintenanceController::class);
    Route::resource('suppliers', SupplierController::class);
    // Vertical CRM (Furniture) Routes
    Route::group(['prefix' => 'crm', 'as' => 'crm.'], function () {
        Route::get('dashboard', [App\Http\Controllers\Admin\CrmDashboardController::class, 'index'])->name('dashboard');
        
        // Leads Module
        Route::get('leads', [App\Http\Controllers\Admin\LeadController::class, 'index'])->name('leads.index');
        Route::get('leads/create', [App\Http\Controllers\Admin\LeadController::class, 'create'])->name('leads.create'); // Fallback if not using modal
        Route::post('leads', [App\Http\Controllers\Admin\LeadController::class, 'store'])->name('leads.store');
        Route::get('leads/{lead}', [App\Http\Controllers\Admin\LeadController::class, 'show'])->name('leads.show');
        Route::get('leads/{lead}/edit', [App\Http\Controllers\Admin\LeadController::class, 'edit'])->name('leads.edit');
        Route::put('leads/{lead}', [App\Http\Controllers\Admin\LeadController::class, 'update'])->name('leads.update');
        Route::delete('leads/{lead}', [App\Http\Controllers\Admin\LeadController::class, 'destroy'])->name('leads.destroy');
        Route::post('leads/{lead}/convert', [App\Http\Controllers\Admin\LeadController::class, 'convert'])->name('leads.convert');
        Route::post('leads/{lead}/interaction', [App\Http\Controllers\Admin\LeadController::class, 'logInteraction'])->name('leads.interaction');

        // Route::get('leads', [App\Http\Controllers\Admin\CrmEntityController::class, 'index'])->defaults('type', 'lead')->name('leads.index');
        // Route::get('leads/create', [App\Http\Controllers\Admin\CrmEntityController::class, 'create'])->defaults('type', 'lead')->name('leads.create');
        
        // Route::get('architects', [App\Http\Controllers\Admin\CrmEntityController::class, 'index'])->defaults('type', 'architect')->name('architects.index');
        // Route::get('architects/create', [App\Http\Controllers\Admin\CrmEntityController::class, 'create'])->defaults('type', 'architect')->name('architects.create');

        Route::get('partners', [App\Http\Controllers\Admin\CrmEntityController::class, 'index'])->defaults('type', 'partner')->name('partners.index');
        Route::get('partners/create', [App\Http\Controllers\Admin\CrmEntityController::class, 'create'])->defaults('type', 'partner')->name('partners.create');

        Route::resource('entities', App\Http\Controllers\Admin\CrmEntityController::class);

        // Architects (Specialized Module)
        Route::resource('architects', App\Http\Controllers\Admin\ArchitectController::class);
        
        // Pipeline & Opportunities
        Route::get('pipeline', [App\Http\Controllers\Admin\CrmPipelineController::class, 'index'])->name('pipeline.index');
        Route::get('pipeline/create', [App\Http\Controllers\Admin\CrmPipelineController::class, 'create'])->name('pipeline.create');
        Route::post('pipeline', [App\Http\Controllers\Admin\CrmPipelineController::class, 'store'])->name('pipeline.store');
        
        // Stage Settings
        Route::prefix('settings/stages')->name('settings.stages.')->group(function() {
            Route::get('/', [App\Http\Controllers\Admin\PipelineStageController::class, 'index'])->name('index');
            Route::post('/', [App\Http\Controllers\Admin\PipelineStageController::class, 'store'])->name('store');
            Route::put('/{stage}', [App\Http\Controllers\Admin\PipelineStageController::class, 'update'])->name('update');
            Route::delete('/{stage}', [App\Http\Controllers\Admin\PipelineStageController::class, 'destroy'])->name('destroy');
            Route::post('/reorder', [App\Http\Controllers\Admin\PipelineStageController::class, 'reorder'])->name('reorder');
        });
    });

    // API Route for External Updates (e.g. Production) & Pipeline API
    Route::post('api/crm/opportunities/{opportunity}/stage', [App\Http\Controllers\Admin\CrmPipelineController::class, 'updateStage'])->name('crm.api.opportunity.stage');
    
    // Additional Opportunity Routes (outside resource if necessary or integrated)
    Route::group(['prefix' => 'crm', 'as' => 'crm.'], function () {
        Route::get('opportunities/{opportunity}', [\App\Http\Controllers\Admin\CrmPipelineController::class, 'show'])->name('opportunities.show');
        Route::get('opportunities/{opportunity}/edit', [\App\Http\Controllers\Admin\CrmPipelineController::class, 'edit'])->name('opportunities.edit');
        Route::put('opportunities/{opportunity}', [\App\Http\Controllers\Admin\CrmPipelineController::class, 'update'])->name('opportunities.update');

        Route::post('opportunities/{opportunity}/won', [App\Http\Controllers\Admin\CrmPipelineController::class, 'markWon'])->name('opportunities.won'); // Need to add method to controller
        Route::post('opportunities/{opportunity}/lost', [App\Http\Controllers\Admin\CrmPipelineController::class, 'markLost'])->name('opportunities.lost'); // Need to add method to controller
        Route::post('opportunities/{opportunity}/interaction', [App\Http\Controllers\Admin\LeadController::class, 'logInteraction'])->name('opportunities.interaction'); // Reuse or create new
        
        // CRM Activities & Tasks
        Route::post('opportunities/{opportunity}/activities', [App\Http\Controllers\Admin\CrmActivityController::class, 'store'])->name('opportunities.activities.store');
        Route::post('activities/{activity}/complete', [App\Http\Controllers\Admin\CrmActivityController::class, 'complete'])->name('activities.complete');
        
        // CRM Activity Dashboard
        Route::get('activities/dashboard', [App\Http\Controllers\Admin\ActivityDashboardController::class, 'index'])->name('activities.dashboard');

        // CRM Attachments
        Route::post('opportunities/{opportunity}/attachments', [App\Http\Controllers\Admin\CrmAttachmentController::class, 'store'])->name('opportunities.attachments.store');
        Route::delete('attachments/{attachment}', [App\Http\Controllers\Admin\CrmAttachmentController::class, 'destroy'])->name('attachments.destroy');

        // Sellers CRUD
        Route::resource('sellers', App\Http\Controllers\Admin\SellerController::class);
    });
    Route::get('customers-data', [CustomerController::class, 'data'])->name('customers.data');
    Route::resource('vehicle_fines', VehicleFineController::class);
    Route::get('vehicle_fines-data', [VehicleFineController::class, 'data'])->name('vehicle_fines.data');
    Route::resource('planned_shipments', PlannedShipmentController::class)->parameters([
        'planned_shipments' => 'plannedShipment'
    ]);
    Route::get('planned_shipments-data', [PlannedShipmentController::class, 'data'])->name('planned_shipments.data');
    Route::get('reports/customers', [ReportController::class, 'customerReports'])->name('reports.customers');
    Route::get('fleet-report', [FleetReportController::class, 'index'])->name('fleet_report.index');
    Route::get('vehicle-detailed-report', [FleetReportController::class, 'vehicleDetailedReport'])->name('fleet_report.vehicle_detailed_report');

    Route::get('dashboard', [App\Http\Controllers\Admin\GeneralDashboardController::class, 'index'])->name('dashboard.index');

    Route::get('permissions', [RolePermissionController::class, 'permissionIndex'])->name('permissions.index');
    Route::get('permissions/create', [RolePermissionController::class, 'permissionCreate'])->name('permissions.create');
    Route::post('permissions', [RolePermissionController::class, 'permissionStore'])->name('permissions.store');
    Route::get('permissions/{permission}', [RolePermissionController::class, 'permissionShow'])->name('permissions.show');
    Route::get('permissions/{permission}/edit', [RolePermissionController::class, 'permissionEdit'])->name('permissions.edit');
    Route::put('permissions/{permission}', [RolePermissionController::class, 'permissionUpdate'])->name('permissions.update');
    Route::delete('permissions/{permission}', [RolePermissionController::class, 'permissionDestroy'])->name('permissions.destroy');

    // Route::resource('planned_shipments', PlannedShipmentController::class); // Removido: Definição redundante
    Route::patch('planned_shipments/{plannedShipment}/update-status', [PlannedShipmentController::class, 'updateStatus'])->name('planned_shipments.updateStatus');
    Route::post('shipment-tracking', [ShipmentTrackingController::class, 'store'])->name('shipment-tracking.store');
    Route::get('delivery_windows-data', [DeliveryWindowController::class, 'data'])->name('delivery_windows.data');
    Route::resource('delivery_windows', DeliveryWindowController::class);
    Route::resource('assembly-schedules', AssemblyScheduleController::class);
    Route::get('assembly-dashboard', [AssemblyScheduleController::class, 'dashboard'])->name('assembly-schedules.dashboard');
    Route::get('fleet-dashboard', [App\Http\Controllers\Admin\FleetDashboardController::class, 'index'])->name('fleet.dashboard');
    Route::get('customers-dashboard', [App\Http\Controllers\Admin\CustomersDashboardController::class, 'index'])->name('customers.dashboard');
    Route::get('sales-dashboard', [App\Http\Controllers\Admin\SalesDashboardController::class, 'index'])->name('sales.dashboard');

    // Assembly Expenses
    Route::resource('assembly-expenses', AssemblyExpenseController::class)->only(['index', 'store', 'destroy']);
    Route::post('assembly-expenses/{expense}/approve', [AssemblyExpenseController::class, 'approve'])->name('assembly-expenses.approve');
    Route::post('assembly-expenses/{expense}/reject',  [AssemblyExpenseController::class, 'reject'])->name('assembly-expenses.reject');
});



Route::get('/', function () {
    return redirect()->route('dashboard.index');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->middleware('guest')->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->middleware('guest')->name('password.request');



Route::get('/sales/export/excel', [SaleController::class, 'exportExcel'])->name('sales.export.excel');
Route::get('/sales/export/pdf', [SaleController::class, 'exportPdf'])->name('sales.export.pdf');

Route::get('sales/data', [SaleController::class, 'data'])->name('sales.data');
Route::get('drivers/data', [DriverController::class, 'data'])->name('drivers.data');
Route::get('/assemblers-available', [App\Http\Controllers\AssemblerController::class, 'getAvailableAssemblers'])->name('assemblers.available');
Route::get('/sales/{sale}/schedule-assembly', [App\Http\Controllers\SaleController::class, 'scheduleAssemblyCreate'])->name('sales.scheduleAssembly.create');


Route::get('sales/kanbanData', [SaleController::class, 'kanbanData'])->name('sales.kanbanData');
Route::patch('sales/{sale}/update-status', [SaleController::class, 'updateStatus'])->name('sales.updateStatus');
Route::get('/crm/dashboard', [App\Http\Controllers\Admin\CrmDashboardController::class, 'index'])->name('crm.dashboard');
Route::resource('sales', SaleController::class);


Route::middleware('auth')->group(function () {
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // My Schedule for Assemblers
    Route::get('/my-schedule', [AssemblyScheduleController::class, 'mySchedule'])->name('assembler.my-schedule');
    Route::post('/my-schedule/confirm', [AssemblyScheduleController::class, 'confirmPresence'])->name('assembler.my-schedule.confirm');
    Route::post('/my-schedule/start', [AssemblyScheduleController::class, 'startAssembly'])->name('assembler.my-schedule.start');
    Route::get('/my-schedule/start/{assemblySchedule}', [AssemblyScheduleController::class, 'startForm'])->name('assembler.my-schedule.start.form');
    Route::get('/my-schedule/finish/{assemblySchedule}', [AssemblyScheduleController::class, 'finishForm'])->name('assembler.my-schedule.finish.form');
    Route::post('/my-schedule/finish', [AssemblyScheduleController::class, 'finishAssembly'])->name('assembler.my-schedule.finish');
    Route::post('/my-schedule/notes', [AssemblyScheduleController::class, 'saveNotes'])->name('assembler.my-schedule.notes');

    // Admin view of all schedules (with filters)
    Route::get('/assembly-schedules/all', [AssemblyScheduleController::class, 'allSchedules'])->name('assembly-schedules.all');
    Route::get('/assembly-schedules/events', [AssemblyScheduleController::class, 'getCalendarEvents'])->name('assembly-schedules.events');
    Route::get('/assembly-schedules/{assemblySchedule}/details', [AssemblyScheduleController::class, 'showDetails'])->name('assembly-schedules.showDetails');

    Route::get('/driver-schedules/all', [App\Http\Controllers\DriverScheduleController::class, 'allSchedules'])->name('driver-schedules.all');
    Route::get('/driver-schedules/events', [App\Http\Controllers\DriverScheduleController::class, 'getCalendarEvents'])->name('driver-schedules.events');
    Route::get('/driver/my-schedule', [App\Http\Controllers\DriverScheduleController::class, 'mySchedule'])->name('driver.my-schedule');
    Route::get('/driver/destinations/{destination}', [App\Http\Controllers\DriverScheduleController::class, 'showDestination'])->name('driver.destinations.show');
    Route::get('/driver/destinations/{destination}/start', [App\Http\Controllers\DriverScheduleController::class, 'startForm'])->name('driver.destinations.start.form');
    Route::post('/driver/destinations/start', [App\Http\Controllers\DriverScheduleController::class, 'startDelivery'])->name('driver.destinations.start');
    Route::get('/driver/destinations/{destination}/finish', [App\Http\Controllers\DriverScheduleController::class, 'finishForm'])->name('driver.destinations.finish.form');
    Route::post('/driver/destinations/finish', [App\Http\Controllers\DriverScheduleController::class, 'finishDelivery'])->name('driver.destinations.finish');
});

// Public evaluation routes
Route::get('/assembly-evaluation/{token}', [App\Http\Controllers\AssemblyEvaluationController::class, 'show'])->name('assembly-evaluation.show');
Route::post('/assembly-evaluation/{token}', [App\Http\Controllers\AssemblyEvaluationController::class, 'submit'])->name('assembly-evaluation.submit');
Route::get('/delivery-evaluation/{token}', [App\Http\Controllers\DeliveryEvaluationController::class, 'show'])->name('delivery-evaluation.show');
Route::post('/delivery-evaluation/{token}', [App\Http\Controllers\DeliveryEvaluationController::class, 'submit'])->name('delivery-evaluation.submit');


