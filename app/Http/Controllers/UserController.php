<?php

namespace App\Http\Controllers;

use App\Helpers\ApiFormatter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class UserController extends Controller
{
    public function index()
    {
        try {
            $data = User::all()->toArray();

            return Apiformatter::sendResponse(200, 'success', $data);
        } catch (\Exception $err) {
            return Apiformatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'username' => 'required|unique:users',
                'email' => 'required|unique:users',
                'password' => 'required',
                'role' => 'required'
            ]);

            $prosesData = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Crypt::encrypt($request->password),
                'role' => $request->role
            ]);

            if ($prosesData) {
                return ApiFormatter::sendResponse(200, 'success', $prosesData);
            } else {
                return ApiFormatter::sendResponse(400, 'bad request', 'Gagal memproses data User!
                 Silahkan coba lagi.');
            }
        } catch (\Exception $err) {
            return Apiformatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $data = User::where('id', $id)->first();
            return Apiformatter::sendResponse(200, 'success', $data);
        } catch (\Exception $err) {
            return Apiformatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

    public function update(Request $Request, $id)
    {
        try {
            $this->validate($Request, [
                'username' => 'required|unique:users,username,' . $id,
                'email' => 'required|unique:users,email,' . $id,
                'password' => 'required',
                'role' => 'required'
            ]);

            $checkProses = User::where('id', $id)->update([
                'username' => $Request->username,
                'email' => $Request->email,
                'password' => Crypt::encrypt($Request->password),
                'role' => $Request->role,
                // '_method' => $Request-> PATCH
            ]);

            if ($checkProses) {
                $data = User::where('id', $id)->first();

                return Apiformatter::sendResponse(200, 'success', $data);
            }
        } catch (\Exception $err) {
            return Apiformatter::sendResponse(400, 'bad request', $err->getmessage());
        }
    }

    public function destroy($id)
    {
        try {
            $checkproses = User::where('id', $id)->delete();

            if ($checkproses) {
                return
                    Apiformatter::sendResponse(200, 'succes', 'berhasil hapus data User!');
            }
        } catch (\Exception $err) {
            return
                Apiformatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

    public function trash()
    {
        try {
            $data = User::onlyTrashed()->get();

            return
                Apiformatter::sendResponse(200, 'succes', $data);
        } catch (\Exception $err) {
            return
                Apiformatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            $checkRestore = User::onlyTrashed()->where('id',$id)->restore();

            if ($checkRestore) {
                $data = User::where('id', $id)->first();
                return Apiformatter::sendResponse(200, 'succes', $data);
            }
        }catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

    public function permanenDelete($id)
    {
        try {
            $checkpermanenDelete =
            User::onlyTrashed()->where('id', $id)->forceDelete();

            if ($checkpermanenDelete) {
                return Apiformatter:: sendResponse(200, 'succes', 'berhasil menghapus permanen data User!');
            }
        } catch (\Exception $err) {
            return Apiformatter:: sendResponse(400, 'bad request', $err->getMessage());
        }
    }
}