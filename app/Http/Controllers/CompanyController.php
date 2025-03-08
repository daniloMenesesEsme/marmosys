<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::orderBy('nome_fantasia')->paginate(10);
        return view('companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('companies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome_fantasia' => 'required|string|max:255',
            'razao_social' => 'nullable|string|max:255',
            'cnpj' => 'nullable|string|max:20',
            'inscricao_estadual' => 'nullable|string|max:255',
            'inscricao_municipal' => 'nullable|string|max:255',
            'telefone' => 'nullable|string|max:255',
            'celular' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|string|max:255',
            'cep' => 'nullable|string|max:255',
            'logradouro' => 'nullable|string|max:255',
            'numero' => 'nullable|string|max:255',
            'complemento' => 'nullable|string|max:255',
            'bairro' => 'nullable|string|max:255',
            'cidade' => 'nullable|string|max:255',
            'estado' => 'nullable|string|max:255',
            'observacoes' => 'nullable|text',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Remover formatação do CNPJ e telefones
        if (isset($validated['cnpj'])) {
            $validated['cnpj'] = preg_replace('/[^0-9]/', '', $validated['cnpj']);
        }
        if (isset($validated['telefone'])) {
            $validated['telefone'] = preg_replace('/[^0-9]/', '', $validated['telefone']);
        }
        if (isset($validated['celular'])) {
            $validated['celular'] = preg_replace('/[^0-9]/', '', $validated['celular']);
        }

        // Upload do logo
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('companies', 'public');
            $validated['logo'] = $logoPath;
        }

        Company::create($validated);

        return redirect()->route('companies.index')
            ->with('success', 'Empresa cadastrada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        return view('companies.show', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'nome_fantasia' => 'required|string|max:255',
            'razao_social' => 'nullable|string|max:255',
            'cnpj' => 'nullable|string|max:20',
            'inscricao_estadual' => 'nullable|string|max:255',
            'inscricao_municipal' => 'nullable|string|max:255',
            'telefone' => 'nullable|string|max:255',
            'celular' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|string|max:255',
            'cep' => 'nullable|string|max:255',
            'logradouro' => 'nullable|string|max:255',
            'numero' => 'nullable|string|max:255',
            'complemento' => 'nullable|string|max:255',
            'bairro' => 'nullable|string|max:255',
            'cidade' => 'nullable|string|max:255',
            'estado' => 'nullable|string|max:255',
            'observacoes' => 'nullable|text',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Remover formatação do CNPJ e telefones
        if (isset($validated['cnpj'])) {
            $validated['cnpj'] = preg_replace('/[^0-9]/', '', $validated['cnpj']);
        }
        if (isset($validated['telefone'])) {
            $validated['telefone'] = preg_replace('/[^0-9]/', '', $validated['telefone']);
        }
        if (isset($validated['celular'])) {
            $validated['celular'] = preg_replace('/[^0-9]/', '', $validated['celular']);
        }

        // Upload do logo
        if ($request->hasFile('logo')) {
            // Remover logo anterior se existir
            if ($company->logo) {
                Storage::disk('public')->delete($company->logo);
            }
            
            $logoPath = $request->file('logo')->store('companies', 'public');
            $validated['logo'] = $logoPath;
        }

        $company->update($validated);

        return redirect()->route('companies.index')
            ->with('success', 'Empresa atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        // Soft delete
        $company->delete();

        return redirect()->route('companies.index')
            ->with('success', 'Empresa removida com sucesso!');
    }
}
