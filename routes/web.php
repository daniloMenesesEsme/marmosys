<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;  // Adicionado
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\FinancialCategoryController;
use App\Http\Controllers\FinancialAccountController;
use App\Http\Controllers\FinancialReportController;
use App\Http\Controllers\FinancialForecastController;
use App\Http\Controllers\FinancialReconciliationController;
use App\Http\Controllers\CostCenterController;
use App\Http\Controllers\CashFlowProjectionController;
use App\Http\Controllers\FinancialGoalController;
use App\Http\Controllers\MaterialController;

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
        Route::get('goals', [FinancialGoalController::class, 'index'])->name('goals.index');
        Route::post('goals', [FinancialGoalController::class, 'store'])->name('goals.store');
        Route::get('goals/create', [FinancialGoalController::class, 'create'])->name('goals.create');
        Route::get('goals/{goal}/edit', [FinancialGoalController::class, 'edit'])->name('goals.edit');
        Route::put('goals/{goal}', [FinancialGoalController::class, 'update'])->name('goals.update');
        Route::delete('goals/{goal}', [FinancialGoalController::class, 'destroy'])->name('goals.destroy');
        Route::post('goals/update-status', [FinancialGoalController::class, 'updateStatus'])
            ->name('goals.update-status');

        // Rotas de Orçamentos
        Route::resource('budgets', BudgetController::class);
        
        // Se você precisar da rota de cópia, adicione:
        Route::post('budgets/copy', [BudgetController::class, 'copy'])->name('budgets.copy');

        Route::get('/budgets/{budget}/pdf', [BudgetController::class, 'generatePdf'])->name('budgets.pdf');
        Route::get('/budgets/{budget}/print', [BudgetController::class, 'printView'])->name('budgets.print');
    });

    Route::get('/materials/search', [MaterialController::class, 'search'])->name('materials.search');

    // Adicione esta rota dentro do grupo de rotas protegidas
    Route::prefix('stock')->name('stock.')->group(function () {
        Route::resource('materials', MaterialController::class);
    });
}); 