<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function index()
    {
        $company = CompanySetting::first();
        return view('admin.company.index', compact('company'));
    }

    public function update(Request $request, CompanySetting $company)
    {
        $validated = $request->validate([
            'nome_empresa' => 'required|string|max:255',
            'cnpj' => 'required|string|max:18',
            'telefone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'endereco' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('logo')) {
            if ($company->logo_path) {
                Storage::delete($company->logo_path);
            }
            $path = $request->file('logo')->store('logos', 'public');
            $validated['logo_path'] = $path;
        }

        $company->update($validated);

        return redirect()
            ->route('admin.company.index')
            ->with('success', 'Dados da empresa atualizados com sucesso!');
    }
} 