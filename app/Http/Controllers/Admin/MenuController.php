<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::all();
        return view('admin.menu.index', compact('menus'));
    }

    public function create()
    {
        return view('admin.menu.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_menu' => 'required|string|max:100',
            'harga' => 'required|numeric|min:0',
            'kategori' => 'required|string|max:50',
            'stok' => 'required|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean'
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        if ($request->hasFile('gambar')) {
            $image = $request->file('gambar');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/menu', $imageName);
            $data['gambar'] = 'menu/' . $imageName;
        }

        Menu::create($data);

        return redirect()->route('admin.menu.index')
            ->with('success', 'Menu berhasil ditambahkan');
    }

    public function edit(Menu $menu)
    {
        return view('admin.menu.edit', compact('menu'));
    }


    public function update(Request $request, Menu $menu)
    {
        
        $validated = $request->validate([
            'nama_menu' => 'required|string|max:100',
            'harga' => 'required|numeric|min:0',
            'kategori' => 'required|string|max:50',
            'stok' => 'required|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'nullable',
        ]);

        // checkbox fix
        $validated['is_active'] = $request->has('is_active');

        // upload gambar
        if ($request->hasFile('gambar')) {

            // hapus gambar lama
            if ($menu->gambar && $menu->gambar !== 'placeholder.jpg') {
                Storage::disk('public')->delete($menu->gambar);
            }

            $path = $request->file('gambar')->store('menu', 'public');
            $validated['gambar'] = $path;
        }

        // UPDATE DATABASE
        $menu->update($validated);

        return redirect()
            ->route('admin.menu.index')
            ->with('success', 'Menu berhasil diperbarui');
    }

    public function destroy(Menu $menu)
    {
        // Check if menu is used in transactions
        if ($menu->transactionDetails()->count() > 0) {
            return redirect()->route('admin.menu.index')
                ->with('error', 'Menu tidak dapat dihapus karena sudah digunakan dalam transaksi');
        }

        // Delete image if exists
        if ($menu->gambar && $menu->gambar !== 'placeholder.jpg') {
            Storage::delete('public/' . $menu->gambar);
        }

        $menu->delete();

        return redirect()->route('admin.menu.index')
            ->with('success', 'Menu berhasil dihapus');
    }
}
