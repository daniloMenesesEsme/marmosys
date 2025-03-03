<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\CNPJService;

class CompanyController extends Controller
{
    public function index()
    {
        // Como teremos apenas uma empresa, vamos redirecionar para edit
        $company = Company::first();
        if ($company) {
            return redirect()->route('companies.edit', $company);
        }
        return redirect()->route('companies.create');
    }

    public function create()
    {
        // Verificar se já existe uma empresa
        if (Company::exists()) {
            return redirect()->route('companies.edit', Company::first());
        }
        return view('companies.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'razao_social' => 'required|string|max:255',
            'nome_fantasia' => 'nullable|string|max:255',
            'cnpj' => 'required|string|max:18|unique:companies',
            'inscricao_estadual' => 'nullable|string|max:20',
            'inscricao_municipal' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'endereco' => 'nullable|string',
            'cep' => 'nullable|string|max:9',
            'cidade' => 'nullable|string|max:100',
            'estado' => 'nullable|string|size:2',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ativo' => 'boolean'
        ]);

        try {
            if ($request->hasFile('logo')) {
                // Define o nome do arquivo como timestamp + extensão original
                $fileName = time() . '.' . $request->logo->getClientOriginalExtension();
                
                // Salva o arquivo com nome personalizado
                $logoPath = $request->file('logo')->storeAs(
                    'companies/logos',
                    $fileName,
                    'public'
                );
                
                // Log para debug
                \Log::info('Logo salvo em: ' . $logoPath);
                
                $validated['logo_path'] = $logoPath;
            }

            $company = Company::create($validated);

            return redirect()
                ->route('companies.index')
                ->with('success', 'Empresa cadastrada com sucesso!');
        } catch (\Exception $e) {
            \Log::error('Erro ao salvar logo: ' . $e->getMessage());
            return back()->with('error', 'Erro ao salvar o logo da empresa.');
        }
    }

    public function edit(Company $company)
    {
        return view('companies.form', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'razao_social' => 'required|string|max:255',
            'nome_fantasia' => 'nullable|string|max:255',
            'cnpj' => 'required|string|max:18|unique:companies,cnpj,' . $company->id,
            'inscricao_estadual' => 'nullable|string|max:20',
            'inscricao_municipal' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'endereco' => 'nullable|string',
            'cep' => 'nullable|string|max:9',
            'cidade' => 'nullable|string|max:100',
            'estado' => 'nullable|string|size:2',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ativo' => 'boolean'
        ]);

        try {
            if ($request->hasFile('logo')) {
                // Remove logo antigo se existir
                if ($company->logo_path) {
                    Storage::disk('public')->delete($company->logo_path);
                }

                // Garante que o diretório existe
                Storage::disk('public')->makeDirectory('companies/logos');
                
                // Salva o novo logo
                $logoPath = $request->file('logo')->store('companies/logos', 'public');
                
                // Log para debug
                \Log::info('Novo logo salvo em: ' . $logoPath);
                
                $validated['logo_path'] = $logoPath;
            }

            $company->update($validated);

            return redirect()
                ->route('companies.index')
                ->with('success', 'Empresa atualizada com sucesso!');
        } catch (\Exception $e) {
            \Log::error('Erro ao atualizar logo: ' . $e->getMessage());
            return back()->with('error', 'Erro ao atualizar o logo da empresa.');
        }
    }

    public function destroy(Company $company)
    {
        $company->deleteLogo();
        $company->delete();

        return redirect()
            ->route('companies.index')
            ->with('success', 'Empresa excluída com sucesso!');
    }

    public function findByCNPJ(Request $request, CNPJService $cnpjService)
    {
        $data = $cnpjService->find($request->cnpj);
        
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'CNPJ não encontrado'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
} 