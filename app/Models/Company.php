<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'razao_social',
        'nome_fantasia',
        'cnpj',
        'inscricao_estadual',
        'inscricao_municipal',
        'email',
        'telefone',
        'endereco',
        'cep',
        'cidade',
        'estado',
        'logo_path',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean'
    ];

    // Método para retornar a URL do logo
    public function getLogoUrlAttribute()
    {
        if ($this->logo_path && Storage::disk('public')->exists($this->logo_path)) {
            return asset('storage/' . $this->logo_path);
        }
        
        return asset('images/no-logo.png'); // Imagem padrão quando não houver logo
    }

    // Método para excluir o logo antigo
    public function deleteLogo()
    {
        if ($this->logo_path && Storage::exists($this->logo_path)) {
            Storage::delete($this->logo_path);
        }
    }
} 