<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BankSeeder extends Seeder
{
    public function run()
    {
        $banks = [
            [
                'codigo' => '001',
                'nome' => 'Banco do Brasil S.A.',
                'ispb' => '00000000',
                'ativo' => true,
            ],
            [
                'codigo' => '341',
                'nome' => 'Itaú Unibanco S.A.',
                'ispb' => '60701190',
                'ativo' => true,
            ],
            [
                'codigo' => '033',
                'nome' => 'Banco Santander (Brasil) S.A.',
                'ispb' => '90400888',
                'ativo' => true,
            ],
            [
                'codigo' => '104',
                'nome' => 'Caixa Econômica Federal',
                'ispb' => '00360305',
                'ativo' => true,
            ],
            [
                'codigo' => '237',
                'nome' => 'Banco Bradesco S.A.',
                'ispb' => '60746948',
                'ativo' => true,
            ],
            [
                'codigo' => '260',
                'nome' => 'Nu Pagamentos S.A. (Nubank)',
                'ispb' => '18236120',
                'ativo' => true,
            ],
            [
                'codigo' => '077',
                'nome' => 'Banco Inter S.A.',
                'ispb' => '00416968',
                'ativo' => true,
            ],
            [
                'codigo' => '336',
                'nome' => 'Banco C6 S.A.',
                'ispb' => '31872495',
                'ativo' => true,
            ],
            [
                'codigo' => '290',
                'nome' => 'PagBank',
                'ispb' => '08561701',
                'ativo' => true,
            ],
            [
                'codigo' => '655',
                'nome' => 'Banco Votorantim S.A.',
                'ispb' => '59588111',
                'ativo' => true,
            ],
        ];

        $now = Carbon::now();

        foreach ($banks as $bank) {
            DB::table('banks')->insert(array_merge($bank, [
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }
    }
} 