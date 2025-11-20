<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KerusakanTeknik extends Model
{


    // Nama tabel
    protected $table = 'kerusakan_teknik';

    // Primary key
    protected $primaryKey = 'id';

    // Laravel default pakai timestamps (created_at & updated_at),
    // tapi tabel kita tidak punya, jadi matikan
    public $timestamps = false;

    // Field yang boleh diisi (mass assignment)
    protected $fillable = [
        'tgl',
        'lokasi_line',
        'no_mesin',
        'nama_mesin',
        'deskripsi_masalah',
        'tindakan_perbaikan',
        'klasifikasi',
        'sparepart_cod',
        'waktu_mulai',
        'waktu_selesai',
        'durasi_jam',
        'pelaksana',
        'keterangan',
    ];

    // Jika ada kolom bertipe date, bisa tambahkan cast
    protected $casts = [
        'tgl' => 'date',
        'waktu_mulai' => 'datetime:H:i',
        'waktu_selesai' => 'datetime:H:i',
        'durasi_jam' => 'decimal:2',
    ];
}
