<?php

namespace Tests\Feature\Financial;

use Tests\TestCase;
use App\Models\User;
use App\Models\Transaction;
use App\Enums\PaymentMethod;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReportControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_can_view_reports_page()
    {
        $response = $this->actingAs($this->user)
            ->get(route('financial.reports.index'));

        $response->assertStatus(200);
        $response->assertViewIs('financial.reports.index');
    }

    public function test_can_filter_reports()
    {
        Transaction::factory()->count(5)->create([
            'forma_pagamento' => PaymentMethod::CREDIT_CARD
        ]);

        $response = $this->actingAs($this->user)
            ->get(route('financial.reports.index', [
                'forma_pagamento' => PaymentMethod::CREDIT_CARD->value
            ]));

        $response->assertStatus(200);
        $response->assertViewHas('transactions');
    }

    public function test_can_export_pdf()
    {
        $response = $this->actingAs($this->user)
            ->get(route('financial.reports.export.pdf'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_can_export_excel()
    {
        $response = $this->actingAs($this->user)
            ->get(route('financial.reports.export.excel'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }
} 