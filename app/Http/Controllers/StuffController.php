<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stuff;
use App\Helpers\Apiformatter;

class StuffController extends Controller
{
    public function index()
    {
        try {
            // Ambil data yang ingin ditampilkan 
            $data = Stuff::all()->toArray();
    
            // Mengembalikan respons dengan format yang diinginkan
            return Apiformatter::sendResponse(200, 'success', $data);
        } catch (\Exception $err) {
            // Mengembalikan respons jika terjadi kesalahan
            return Apiformatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }
    
    public function store(Request $request)
    {
        try {
            // Melakukan validasi data yang diterima
            $this->validate($request,[
                'nama'=>'required', // Memastikan field 'nama' wajib diisi
                'category' => 'required' // Memastikan field 'category' wajib diisi
            ]);

            // Membuat objek Stuff baru dengan data yang diberikan
            $prosesData = Stuff::create([
                'nama' => $request->name, // Mengisi 'nama' dengan nilai 'name' dari request
                'category' => $request->category // Mengisi 'category' dengan nilai 'category' dari request
            ]);
        
            // Memeriksa apakah data berhasil dibuat
            if ($prosesData) {
                // Mengirimkan respons sukses dengan kode status 200 dan data yang dibuat
                return Apiformatter::sendResponse(200,'success',$prosesData);
            } else {
                // Mengirimkan respons error jika pembuatan data gagal
                return Apiformatter::sendResponse(400, 'bad request', 'gagal memproses tambah data stuff! silahkan coba lagi ');
            }
        } catch (\Exception $err) {
            // Menangkap setiap pengecualian dan mengirimkan respons error dengan pesan kesalahan
            return Apiformatter::sendResponse(400,'bad request', $err->getmessage());
        };
    }

    public function show($id)
    {
        try {
            // Mengambil data dari database dimana id cocok
            $data = stuff::where('id', $id)->first();

            // Mengembalikan respons sukses beserta data
            return Apiformatter::sesndResponse(200, 'success', $data);
        } catch (\Exception $err) {
            // Jika terjadi kesalahan, mengembalikan respons permintaan buruk beserta pesan kesalahan
            return Apiformatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

    public function update(Request $request,$id)
    {
        try {
            // Validasi input
            $this->validate($request,[
                'name' => 'required',
                'category' => 'required'
            ]);

            // Update data berdasarkan ID
            $checkProses = Stuff::where('id',$id)->update([
                'name' => $request->name,
                'category' => $request->category,
            ]);

            // Jika proses update berhasil
            if ($checkProses) {
                // Ambil data yang telah diupdate
                $data = stuff::where('id',$id)->first();

                // Kirim response sukses
                return Apiformatter::sendResponse(200, 'success',$data);
            }
        } catch (\Exception $err) {
            // Tangani error
            return Apiformatter::sendResponse(400,'bad request', $err->getmessage());
        }
    }
    
    public function destroy($id)
    {
        try {
            // Menghapus data berdasarkan ID
            $checkProses = stuff::where('id',$id)->delete();

            // Jika proses penghapusan berhasil
            if ($checkProses) {
                return Apiformatter::sendResponse(200, 'success','berhasil hapus data');
            }
        } catch (\Exception $err) {
            // Jika terjadi kesalahan, tangani dan kembalikan respons
            return Apiformatter::sendResponse(400,'bad request', $err->getmessage());
        }
    }

    public function trash()
    {
        try {
            //onlytrash : memanggil kembali data sampah atau yang delete_at nya sudah terisi 
            $data = stuff::onlyTrash()->get();

            return Apiformatter::sendResponse(200, 'success',$data);
        } catch (\Throwable $th) {
            return Apiformatter::sendResponse(400,'bad request', $err->getmessage());
        }
    }

    public function restore()
    {
        try {
            $checkRestore = stuff::onlyTrashed()->where('id', $id)->restore();

            return Apiformatter::sendResponse(200, 'success',$data);
        } catch (\Throwable $th) {
            return Apiformatter::sendResponse(400,'bad request', $err->getmessage());
        }
    }

    public function deletepermanent()
    {
       try {
        $checkPermanentDelete = stuff::onlyTrashed()->where('id',$id)->forcedelete();

        if($checkPermanentDelete){
            return Apiformatter::sendResponse(200, 'success','berhasil menghapus permanent data stuff');
        }
       } catch (\Exception $err) {
        return Apiformatter::sendResponse(400,'bad request', $err->getmessage());
       }
    }
}