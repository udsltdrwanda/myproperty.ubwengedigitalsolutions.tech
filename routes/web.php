<?php
use App\Http\Controllers\User\admin\AdminController;
use App\Http\Controllers\User\admin\UserRequestController;
use App\Http\Controllers\User\landlord\LandlordDashboardController;
use App\Http\Controllers\User\landlord\PaymentMode\PaymentModeController;
use App\Http\Controllers\User\tenant\TenantDashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\dashboard\DashboardController;
use App\Http\Controllers\User\employee\EmployeeDashboardController;
use App\Http\Controllers\User\landlord\Amenity\PropertyAmenityController;
use App\Http\Controllers\User\landlord\Import\ImportBuildingController;
use App\Http\Controllers\User\landlord\Import\ImportLandController;
use App\Http\Controllers\User\landlord\Import\ImportTenantContractController;
use App\Http\Controllers\User\landlord\Import\ImportUnitController;
use App\Http\Controllers\User\landlord\Invoice\InvoiceController;
use App\Http\Controllers\User\landlord\PaymentMode\RentRecordController;
use App\Http\Controllers\User\landlord\Property\PropertyController;
use App\Http\Controllers\User\landlord\Property\PropertyManagersController;
use App\Http\Controllers\User\landlord\PropertyTax\PropertyTaxController;
use App\Http\Controllers\User\landlord\RentalIncomeTax\RentalIncomeTaxController;
use App\Http\Controllers\User\landlord\Tenant\TenantController;
use App\Livewire\User\LandLord\Adjacement\AdjacementLivewire;
use App\Livewire\User\LandLord\Adjacement\BuildingAdjacementLivewire;
use App\Livewire\User\LandLord\Adjacement\LandAdjacementLivewire;
use App\Livewire\User\LandLord\Import\ImportLandLivewire;
use App\Livewire\User\LandLord\Tax\TaxCalculatorLivewire;
use App\Livewire\User\LandLord\UnitAmenities\UnitAmenitiesLivewire;
use App\Models\UnitAmenities;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
            Route::get('/User', [UserRequestController::class, 'index'])->name('user.request');
        });
    Route::prefix('landlord')
        ->name('landlord.')
        ->group(function () {
            Route::get('/dashboard', [LandlordDashboardController::class, 'index'])->name('dashboard');
            Route::get('/property', [PropertyController::class, 'index'])->name('property');

            Route::get('/House', [PropertyController::class, 'house'])->name('house');
            Route::get('/Unit', [PropertyController::class, 'unit'])->name('unit');
            Route::get('/properties/{property}', [PropertyController::class, 'show'])->name('properties.show');
            Route::get('/Tenant', [TenantController::class, 'index'])->name('tenant');
            Route::get('/Invoice', [InvoiceController::class, 'index'])->name('invoice');
            Route::get('/Property/Manager', [PropertyManagersController::class, 'index'])->name('property.manager');
            Route::get('/user/land-lord/invoice-detail/{invoice}', [InvoiceController::class, 'show'])->name('invoice.detail');
            Route::get('/PaymentMode', [PaymentModeController::class, 'index'])->name('paymentmode');
            Route::get('/RentRecord', [RentRecordController::class, 'index'])->name('RentRecord');
            Route::get('/Amenity', [PropertyAmenityController::class, 'index'])->name('amenity');
            Route::get('/RentalIncomeTax', [RentalIncomeTaxController::class, 'index'])->name('rental-income-tax');
            Route::get('/PropertyTax', [PropertyTaxController::class, 'index'])->name('property-tax');
            Route::get('/landlord/adjacement', AdjacementLivewire::class)->name('adjacement');
            Route::get('/landlord/land/adjacement', LandAdjacementLivewire::class)->name('land.adjacement');
            Route::get('/landlord/Building/adjacement', BuildingAdjacementLivewire::class)->name('building.adjacement');
            Route::get('/landlord/UnitAmenities', UnitAmenitiesLivewire::class)->name('unit.amenities');

            Route::get('/landlord/Land/Import', ImportLandLivewire::class)->name('land.import');
            Route::get('/landlord/Land/Index', [ImportLandController::class, 'index'])->name('import.index');
            Route::post('/landlord/Land', [ImportLandController::class, 'import'])->name('import.store');

            Route::get('/landlord/Building/Index', [ImportBuildingController::class, 'index'])->name('import.house.index');
            Route::post('/landlord/Building', [ImportBuildingController::class, 'import'])->name('import.house.store');

            Route::get('/landlord/unit/Index', [ImportUnitController::class, 'index'])->name('import.unit.index');
            Route::post('/landlord/unit', [ImportUnitController::class, 'import'])->name('import.unit.store');

            Route::get('/landlord/Tenant/Contract/Index', [ImportTenantContractController::class, 'index'])->name('import.Contract.index');
            Route::post('/landlord/Tenant/Contract', [ImportTenantContractController::class, 'import'])->name('import.Contract.store');
            Route::get('/landlord/Tenant/TaxtCalculator', TaxCalculatorLivewire::class)->name('tax-calculator');

        });


    Route::prefix('employee')
        ->name('employee.')
        ->group(function () {
            Route::get('/dashboard', [EmployeeDashboardController::class, 'index'])->name('dashboard');
        });

    Route::prefix('tenant')
        ->name('tenant.')
        ->group(function () {
            Route::get('/dashboard', [TenantDashboardController::class, 'index'])->name('dashboard');
        });
});
