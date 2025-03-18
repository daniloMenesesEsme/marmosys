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
use App\Http\Controllers\Settings\BackupController;
use App\Http\Controllers\Financial\ReportController;
use App\Http\Controllers\Financial\PaymentMethodController;
use App\Http\Controllers\Financial\FinancialRegistrationController;
use App\Http\Controllers\Financial\PaymentPlanController;
use App\Http\Controllers\Financial\AgentController;
use App\Http\Controllers\Financial\Reports\FinancialAgentReportController;

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
    Route::get('/products/generate-code/{type}', [ProductController::class, 'generateCode'])
        ->name('products.generate-code');
    Route::resource('products', ProductController::class);  // Adicionado
    
    // Rotas financeiras
    Route::prefix('financial')->name('financial.')->middleware(['auth'])->group(function () {
        // Cadastros Financeiros
        Route::prefix('registration')->name('registration.')->group(function () {
            // Formas de Pagamento
            Route::get('payment-methods', [PaymentMethodController::class, 'index'])->name('payment-methods.index');
            Route::get('payment-methods/create', [PaymentMethodController::class, 'create'])->name('payment-methods.create');
            Route::post('payment-methods', [PaymentMethodController::class, 'store'])->name('payment-methods.store');
            Route::get('payment-methods/{paymentMethod}/edit', [PaymentMethodController::class, 'edit'])->name('payment-methods.edit');
            Route::put('payment-methods/{paymentMethod}', [PaymentMethodController::class, 'update'])->name('payment-methods.update');
            Route::delete('payment-methods/{paymentMethod}', [PaymentMethodController::class, 'destroy'])->name('payment-methods.destroy');
            
            // Planos de Pagamento
            Route::resource('payment-plans', PaymentPlanController::class);
            Route::post('payment-plans/{paymentPlan}/simulate', [PaymentPlanController::class, 'simulate'])
                ->name('payment-plans.simulate');

            // Rotas para Agentes Financeiros
            Route::resource('agents', AgentController::class)->except(['show'])->names([
                'index' => 'agents.index',
                'create' => 'agents.create',
                'store' => 'agents.store',
                'edit' => 'agents.edit',
                'update' => 'agents.update',
                'destroy' => 'agents.destroy'
            ]);
        });

        // Contas Financeiras
        Route::resource('accounts', FinancialAccountController::class);
        Route::post('accounts/{account}/pay', [FinancialAccountController::class, 'pay'])
            ->name('accounts.pay');

        // Relatórios Financeiros
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/export/pdf', [ReportController::class, 'exportPDF'])->name('export.pdf');
            Route::get('/export/excel', [ReportController::class, 'exportExcel'])->name('export.excel');
            Route::get('agents', [FinancialAgentReportController::class, 'index'])->name('agents');
            Route::get('agents/import', [FinancialAgentReportController::class, 'showImportForm'])->name('agents.import.form');
            Route::post('agents/import', [FinancialAgentReportController::class, 'import'])->name('agents.import');
            Route::get('agents/template', [FinancialAgentReportController::class, 'exportTemplate'])->name('agents.template');
        });

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
        Route::get('budgets/{budget}/generatePdf', [BudgetController::class, 'generatePdf'])
            ->name('budgets.generatePdf');
        Route::get('budgets/{budget}/pdf', [BudgetController::class, 'generatePdf'])
            ->name('budgets.pdf');
        Route::post('budgets/{budget}/approve', [BudgetController::class, 'approve'])
            ->name('budgets.approve');
        Route::post('budgets/{budget}/reject', [BudgetController::class, 'reject'])
            ->name('budgets.reject');
        Route::post('budgets/copy', [BudgetController::class, 'copy'])
            ->name('budgets.copy');

        // Rotas de agentes financeiros
        Route::resource('agents', AgentController::class);
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
    // Route::resource('suppliers', SupplierController::class);

    // Funcionários
    Route::resource('employees', EmployeeController::class);

    // Empresas
    Route::resource('companies', CompanyController::class);

    // Rota para busca de CNPJ
    Route::get('/api/companies/find-cnpj/{cnpj}', [CompanyController::class, 'findByCNPJ'])->name('companies.find-cnpj');

    // Rota para busca de CNPJ
    // Route::get('/suppliers/find-cnpj/{cnpj}', [SupplierController::class, 'findByCNPJ'])->name('suppliers.find-cnpj');

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

    Route::post('/products/{product}/ajustar-estoque', [ProductController::class, 'ajustarEstoque'])
        ->name('products.ajustar-estoque');
}); 