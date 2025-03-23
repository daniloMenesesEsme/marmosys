<?php

namespace App\Http\Controllers\Financial;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class FinancialRegistrationController extends Controller
{
    public function paymentMethods()
    {
        $paymentMethods = PaymentMethod::orderBy('nome')->paginate(10);
        return view('financial.registration.payment-methods.index', compact('paymentMethods'));
    }

    public function createPaymentMethod()
    {
        return view('financial.registration.payment-methods.form');
    }

    public function storePaymentMethod(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|max:100',
            'descricao' => 'nullable',
            'ativo' => 'boolean',
            'parcelas_padrao' => 'required|integer|min:1',
            'taxa_padrao' => 'required|numeric|min:0'
        ]);

        PaymentMethod::create($validated);
        return redirect()
            ->route('financial.registration.payment-methods')
            ->with('success', 'Forma de pagamento cadastrada com sucesso!');
    }

    public function editPaymentMethod(PaymentMethod $paymentMethod)
    {
        return view('financial.registration.payment-methods.form', compact('paymentMethod'));
    }

    public function updatePaymentMethod(Request $request, PaymentMethod $paymentMethod)
    {
        $validated = $request->validate([
            'nome' => 'required|max:100',
            'descricao' => 'nullable',
            'ativo' => 'boolean',
            'parcelas_padrao' => 'required|integer|min:1',
            'taxa_padrao' => 'required|numeric|min:0'
        ]);

        $paymentMethod->update($validated);
        return redirect()
            ->route('financial.registration.payment-methods')
            ->with('success', 'Forma de pagamento atualizada com sucesso!');
    }

    public function destroyPaymentMethod(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();
        return redirect()
            ->route('financial.registration.payment-methods')
            ->with('success', 'Forma de pagamento removida com sucesso!');
    }
} 