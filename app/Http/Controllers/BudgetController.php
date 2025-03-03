<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function approve(Request $request, Budget $budget)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'motivo_reprovacao' => 'required_if:action,reject'
        ]);

        if ($request->action === 'approve') {
            $budget->update([
                'status' => 'aprovado',
                'approved_by' => auth()->id(),
                'approved_at' => now()
            ]);

            return redirect()
                ->route('budgets.show', $budget)
                ->with('success', 'Orçamento aprovado com sucesso!');
        }

        $budget->update([
            'status' => 'reprovado',
            'motivo_reprovacao' => $request->motivo_reprovacao
        ]);

        return redirect()
            ->route('budgets.show', $budget)
            ->with('success', 'Orçamento reprovado com sucesso!');
    }
} 