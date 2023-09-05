<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Penempatan;
use App\Models\Perbaikan;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $barangs = Barang::all();
        $jumlahTotalBarang = Barang::sum('jumlah');
        // Hitung jumlah total barang yang rusak
        $jumlahBarangRusak = Perbaikan::where('is_selesai', false)->sum('jumlah_perbaikan');
        // Hitung jumlah total barang yang rusak
        $jumlahBarangPerbaikan = Perbaikan::where('is_selesai', true)->sum('jumlah_perbaikan');
        $jumlahBarangDiTempatkan = Penempatan::sum('jumlah_ditempatkan');

        return view('homes.index', compact('barangs', 'jumlahTotalBarang', 'jumlahBarangRusak', 'jumlahBarangPerbaikan', 'jumlahBarangDiTempatkan'));
    }

    public function filter(Request $request)
    {
        $query = Barang::query();

        if ($request->has('keyword')) {
            $query->where('nama', 'like', '%' . $request->input('keyword') . '%');
        }

        // Tambahkan logika penyaringan lainnya sesuai kebutuhan

        // Ambil daftar barang sesuai dengan kriteria filter
        $barangs = $query->paginate(10);

        // Hitung jumlah total barang dari seluruh data (tanpa memperhatikan hasil pencarian)
        $jumlahTotalBarang = Barang::sum('jumlah');

        // Hitung jumlah total barang yang rusak
        $jumlahBarangRusak = Perbaikan::where('is_selesai', false)->sum('jumlah_perbaikan');

        // Hitung jumlah total barang yang telah diperbaiki
        $jumlahBarangPerbaikan = Perbaikan::sum('jumlah_perbaikan');

        return view('homes.index', compact('barangs', 'jumlahTotalBarang', 'jumlahBarangRusak', 'jumlahBarangPerbaikan'));
    }
}
