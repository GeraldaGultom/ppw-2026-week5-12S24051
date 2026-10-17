<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SirkulasiTest extends TestCase
{
    use RefreshDatabase;

    private function buatBuku(int $stok): Buku
    {
        $kategori = Kategori::create(['kode_kategori' => 'UJI', 'nama_kategori' => 'Uji']);

        return Buku::create([
            'isbn' => '978' . random_int(1000000000, 9999999999),
            'judul' => 'Buku Uji', 'penulis' => 'Penulis', 'penerbit' => 'Penerbit',
            'tahun_terbit' => 2025, 'kategori_id' => $kategori->id,
            'stok' => $stok, 'sinopsis' => 'x',
        ]);
    }

    private function payload(Buku $buku, string $nim = '12S24001'): array
    {
        return ['buku_id' => $buku->id, 'nim_peminjam' => $nim, 'nama_peminjam' => 'Mahasiswa Uji'];
    }

    public function test_tamu_tidak_bisa_meminjam(): void
    {
        $buku = $this->buatBuku(3);

        $this->post(route('peminjamans.pinjam'), $this->payload($buku))
             ->assertRedirect(route('login'));

        $this->assertSame(3, $buku->fresh()->stok);
    }

    public function test_pinjam_berhasil_mengurangi_stok_dan_mencatat(): void
    {
        $buku = $this->buatBuku(2);

        $this->actingAs(User::factory()->create())
             ->post(route('peminjamans.pinjam'), $this->payload($buku))
             ->assertSessionHas('success');

        $this->assertSame(1, $buku->fresh()->stok);
        $this->assertDatabaseHas('peminjamans', ['buku_id' => $buku->id, 'status' => 'dipinjam']);
    }

    public function test_stok_nol_ditolak_dan_tidak_minus(): void
    {
        $buku = $this->buatBuku(0);

        $this->actingAs(User::factory()->create())
             ->post(route('peminjamans.pinjam'), $this->payload($buku))
             ->assertSessionHas('error');

        $this->assertSame(0, $buku->fresh()->stok);
        $this->assertDatabaseCount('peminjamans', 0);
    }

    public function test_nim_sama_tidak_bisa_pinjam_buku_sama_dua_kali(): void
    {
        $buku = $this->buatBuku(5);
        $this->actingAs(User::factory()->create());

        $this->post(route('peminjamans.pinjam'), $this->payload($buku));
        $this->post(route('peminjamans.pinjam'), $this->payload($buku))
             ->assertSessionHas('error');

        $this->assertSame(4, $buku->fresh()->stok);
        $this->assertDatabaseCount('peminjamans', 1);
    }

    public function test_kembali_menambah_stok_dan_tidak_bisa_dua_kali(): void
    {
        $buku = $this->buatBuku(1);
        $this->actingAs(User::factory()->create());
        $this->post(route('peminjamans.pinjam'), $this->payload($buku));
        $id = Peminjaman::firstOrFail()->id;
        $this->assertSame(0, $buku->fresh()->stok);

        $this->post(route('peminjamans.kembali', $id))->assertSessionHas('success');
        $this->assertSame(1, $buku->fresh()->stok);

        $this->post(route('peminjamans.kembali', $id))->assertSessionHas('error');
        $this->assertSame(1, $buku->fresh()->stok);   // tidak bertambah lagi
    }

    public function test_rollback_jika_pencatatan_gagal_setelah_stok_berkurang(): void
    {
        $buku = $this->buatBuku(3);

        // Simulasi kegagalan SETELAH decrement, tepat saat INSERT peminjaman
        Peminjaman::creating(function () {
            throw new \RuntimeException('Simulasi kegagalan');
        });

        try {
            $this->actingAs(User::factory()->create())
                 ->post(route('peminjamans.pinjam'), $this->payload($buku))
                 ->assertSessionHas('error');
        } finally {
            Peminjaman::flushEventListeners();
        }

        $this->assertSame(3, $buku->fresh()->stok);   // decrement ikut dibatalkan
        $this->assertDatabaseCount('peminjamans', 0);
    }

    public function test_login_salah_ditolak_dan_login_benar_membuat_cookie(): void
    {
        $user = User::factory()->create();   // password factory = "password"

        $this->post(route('login.attempt'), ['email' => $user->email, 'password' => 'salah'])
             ->assertSessionHas('error');
        $this->assertGuest();

        $this->post(route('login.attempt'), ['email' => $user->email, 'password' => 'password'])
             ->assertRedirect(route('buku.index'))
             ->assertCookie('del_last_user_email');
        $this->assertAuthenticatedAs($user);
    }

    public function test_rute_mutasi_buku_dilindungi_auth(): void
    {
        $this->get(route('buku.create'))->assertRedirect(route('login'));
    }
}