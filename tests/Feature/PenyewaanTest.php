<?php

namespace Tests\Feature;

use App\Models\Kendaraan;
use App\Models\Pelanggan;
use App\Models\Penyewaan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PenyewaanTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->staff = User::factory()->create(['role' => 'staff']);
        $this->pelanggan = Pelanggan::factory()->create(['is_verified' => true]);
        $this->kendaraan = Kendaraan::factory()->create(['status' => 'tersedia']);
    }

    public function test_admin_can_create_penyewaan()
    {
        $response = $this->actingAs($this->admin)
            ->post(route('penyewaan.store'), [
                'pelanggan_id' => $this->pelanggan->id,
                'kendaraan_id' => $this->kendaraan->id,
                'tanggal_mulai' => now()->format('Y-m-d'),
                'tanggal_selesai' => now()->addDays(2)->format('Y-m-d'),
                'metode_pembayaran' => 'tunai'
            ]);
            
        $response->assertRedirect(route('penyewaan.show', Penyewaan::first()))
            ->assertSessionHas('success');
            
        $this->assertDatabaseHas('penyewaans', [
            'pelanggan_id' => $this->pelanggan->id,
            'kendaraan_id' => $this->kendaraan->id,
            'status' => 'reservasi'
        ]);
    }

    public function test_staff_can_start_penyewaan()
    {
        $penyewaan = Penyewaan::factory()->create([
            'status' => 'reservasi',
            'kendaraan_id' => $this->kendaraan->id
        ]);
        
        $response = $this->actingAs($this->staff)
            ->post(route('penyewaan.start', $penyewaan));
            
        $response->assertRedirect()
            ->assertSessionHas('success');
            
        $this->assertEquals('berjalan', $penyewaan->fresh()->status);
        $this->assertEquals('disewa', $this->kendaraan->fresh()->status);
    }

    public function test_admin_can_record_payment()
    {
        $penyewaan = Penyewaan::factory()->create([
            'pelanggan_id' => $this->pelanggan->id,
            'kendaraan_id' => $this->kendaraan->id,
            'total_biaya' => 500000,
            'status_pembayaran' => 'pending'
        ]);
        
        $response = $this->actingAs($this->admin)
            ->post(route('penyewaan.payment', $penyewaan), [
                'jumlah' => 300000
            ]);
            
        $response->assertRedirect()
            ->assertSessionHas('success');
            
        $this->assertEquals(300000, $penyewaan->fresh()->dibayar);
        $this->assertEquals('sebagian', $penyewaan->fresh()->status_pembayaran);
    }
}