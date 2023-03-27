<?php

namespace App\Http\Controllers;

use App\Models\ObjekWisata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ObjekWisataController extends Controller
{
    public function create(Request $request)
    {
        $validateDate = $request->validate([
            'kategori_wisata_id' => 'required',
            'nama_wisata' => 'required',
            'deskripsi' => 'required',
            'fasilitas' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $image = $request->file('fotp');
            $validateDate['foto'] = $image->hashName();
        }

        $result = ObjekWisata::create($validateDate);
        if ($result) {
            if ($request->hasFile('foto')) {
                $image = $request->file('foto');
                $image->storeAs('public/objekwisata', $image->hashName());
            }
            return redirect(Route('objek-wisata.index'))->with('success', 'You Have Successfully Created an News.');
        } else {
            return redirect(Route('objek-wisata.index'))->with('success', 'You Have Failed Create an News.');
        }
    }

    public function update(ObjekWisata $objekWisata, Request $request)
    {
        $validateData = $request->validate([
            'kategori_wisata_id' => 'required',
            'nama_wisata' => 'required',
            'deskripsi' => 'required',
            'fasilitas' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $image = $request->file('foto');
            $validateData['foto'] = $image->hashName();
        }

        $result = $objekWisata->update($validateData);
        if ($result) {
            if ($request->hasFile('foto')) {
                $image = $request->file('foto');
                Storage::delete('public/objekwisata/' . $objekWisata->foto);
                $image->storeAs('public/objekwisata', $image->hashName());
            }
            return redirect(Route('objek-wisata.index'))->with('success', 'You Have Successfully Updated an News.');
        } else {
            return redirect(Route('objek-wisata.index'))->with('success', 'You Have Failed Updated an News.');
        }
    }

    public function delete(ObjekWisata $objekWisata)
    {
        $result = $objekWisata->delete();
        if ($result) {
            if ($objekWisata->foto) {
                Storage::delete('public/o$objekwisata/' . $objekWisata->foto);
            }

            return redirect()->back()->with('success', 'Berita Delete Successfully.');
        } else {

            return redirect()->back()->with('error', 'Berita Delete Failed.');
        }
    }
}
