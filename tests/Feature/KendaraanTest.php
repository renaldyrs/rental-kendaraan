<?php

namespace Tests\Feature;

use App\Models\Kendaraan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class KendaraanTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->staff = User::factory()->create(['role' => 'staff']);
    }

    public function test_admin_can_view_kendaraan_index()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('kendaraan.index'));
            
        $response->assertStatus(200);
    }

    public function test_admin_can_create_kendaraan()
    {
        Storage::fake('public');
        
        $data = [
            'merk' => 'Toyota',
            'model' => 'Avanza',
            'warna' => 'Hitam',
            'tahun' => 2022,
            'nomor_plat' => 'B 1234 ABC',
            'bahan_bakar' => 'Pertalite',
            'kapasitas_penumpang' => 7,
            'transmisi' => 'automatic',
            'kategori' => 'mobil',
            'tarif_sewa' => 300000,
            'status' => 'tersedia',
            'gambar' => UploadedFile::fake()->image('kendaraan.jpg'),
            'deskripsi' => 'Kendaraan keluarga',
            'fasilitas' => 'AC, Audio, GPS'
        ];
        
        $response = $this->actingAs($this->admin)
            ->post(route('kendaraan.store'), $data);
            
        $response->assertRedirect(route('kendaraan.index'))
            ->assertSessionHas('success');
            
        $this->assertDatabaseHas('kendaraans', [
            'merk' => 'Toyota',
            'model' => 'Avanza',
            'nomor_plat' => 'B 1234 ABC'
        ]);
        
        Storage::disk('public')->assertExists('kendaraan/'.$data['gambar']->hashName());
    }

    public function test_admin_can_update_kendaraan_status()
    {
        $kendaraan = Kendaraan::factory()->create(['status' => 'tersedia']);
        
        $response = $this->actingAs($this->admin)
            ->post(route('kendaraan.update-status', $kendaraan), [
                'status' => 'perbaikan'
            ]);
            
        $response->assertRedirect()
            ->assertSessionHas('success');
            
        $this->assertEquals('perbaikan', $kendaraan->fresh()->status);
    }

    public function test_staff_cannot_delete_kendaraan()
    {
        $kendaraan = Kendaraan::factory()->create();
        
        $response = $this->actingAs($this->staff)
            ->delete(route('kendaraan.destroy', $kendaraan));
            
        $response->assertForbidden();
    }
}