<?php

use App\Http\Controllers\Financial\BudgetController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;  // Adicionado
use App\Http\Controllers\FinancialCategoryController;
use App\Http\Controllers\FinancialAccountController;
use App\Http\Controllers\FinancialReportController;
use App\Http\Controllers\FinancialForecastController;
use App\Http\Controllers\FinancialReconciliationController;
use App\Http\Controllers\CostCenterController;
use App\Http\Controllers\CashFlowProjectionController;
use App\Http\Controllers\FinancialGoalController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\Admin\ApprovalLogController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\Settings\BackupController;

Route::get('/', function () {
    return view('welcome');
});

// Rotas de autenticação
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Rotas protegidas
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Rotas de clientes
    Route::resource('clients', ClientController::class);
    
    // Rotas de produtos
    Route::resource('products', ProductController::class);  // Adicionado
    
    // Rotas financeiras
    Route::prefix('financial')->name('financial.')->group(function () {
        // Contas Financeiras
        Route::resource('accounts', FinancialAccountController::class);
        Route::post('accounts/{account}/pay', [FinancialAccountController::class, 'pay'])
            ->name('accounts.pay');

        // Relatórios Financeiros
        Route::get('reports', [FinancialReportController::class, 'index'])
            ->name('reports.index');
        Route::get('reports/pdf', [FinancialReportController::class, 'pdf'])
            ->name('reports.pdf');
        Route::get('reports/excel', [FinancialReportController::class, 'excel'])
            ->name('reports.excel');

        // Categorias Financeiras
        Route::resource('categories', FinancialCategoryController::class);
        
        // Previsão Financeira
        Route::get('forecast', [FinancialForecastController::class, 'index'])
            ->name('forecast.index');

        // Reconciliação Bancária
        Route::get('reconciliation', [FinancialReconciliationController::class, 'index'])
            ->name('reconciliation.index');
        Route::post('reconciliation', [FinancialReconciliationController::class, 'update'])
            ->name('reconciliation.update');
        Route::get('reconciliation/import', [FinancialReconciliationController::class, 'importForm'])
            ->name('reconciliation.import');
        Route::post('reconciliation/import', [FinancialReconciliationController::class, 'import'])
            ->name('reconciliation.process');

        // Centro de Custos
        Route::resource('cost-centers', CostCenterController::class);

        // Fluxo de Caixa Projetado
        Route::get('projections', [CashFlowProjectionController::class, 'index'])
            ->name('projections.index');

        // Metas Financeiras
        Route::resource('goals', FinancialGoalController::class);
        Route::post('goals/update-status', [FinancialGoalController::class, 'updateStatus'])
            ->name('goals.update-status');

        // Rotas de orçamentos
        Route::resource('budgets', BudgetController::class);
        Route::get('budgets/{budget}/pdf', [BudgetController::class, 'generatePdf'])
            ->name('budgets.pdf');
        Route::post('budgets/{budget}/approve', [BudgetController::class, 'approve'])
            ->name('budgets.approve');
        Route::post('budgets/{budget}/reject', [BudgetController::class, 'reject'])
            ->name('budgets.reject');
        Route::post('budgets/copy', [BudgetController::class, 'copy'])
            ->name('budgets.copy');
    });

    Route::get('/materials/search', [MaterialController::class, 'search'])->name('materials.search');

    // Adicione esta rota dentro do grupo de rotas protegidas
    Route::prefix('stock')->name('stock.')->group(function () {
        Route::resource('materials', MaterialController::class);
    });

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('approval-logs', [ApprovalLogController::class, 'index'])->name('approval-logs.index');
        Route::resource('company', CompanyController::class);
    });

    // Fornecedores
    Route::resource('suppliers', SupplierController::class);

    // Funcionários
    Route::resource('employees', EmployeeController::class);

    // Empresas
    Route::resource('companies', CompanyController::class);

    // Rota para busca de CNPJ
    Route::get('/api/companies/find-cnpj/{cnpj}', [CompanyController::class, 'findByCNPJ'])->name('companies.find-cnpj');

    // Rota para busca de CNPJ
    Route::get('/suppliers/find-cnpj/{cnpj}', [SuppliersController::class, 'findByCNPJ'])->name('suppliers.find-cnpj');

    // Rotas de configurações
    Route::prefix('settings')->name('settings.')->group(function () {
        // Rotas de backup
        Route::get('/backup', [BackupController::class, 'index'])
            ->name('backup.index');
        Route::post('/backup', [BackupController::class, 'store'])
            ->name('backup.store');
        Route::post('/backup/create', [BackupController::class, 'createBackup'])
            ->name('backup.create');
        Route::get('/backup/download/{filename}', [BackupController::class, 'download'])
            ->name('backup.download');
        Route::get('/backup/delete/{filename}', [BackupController::class, 'delete'])
            ->name('backup.delete');
    });
}); 