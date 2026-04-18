<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('user_id', Auth::id())->get();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:50',
            'type'  => 'required|in:income,expense',
            'color' => 'required|string',
        ]);

        Category::create([
            'user_id' => Auth::id(),
            'name'    => $request->name,
            'type'    => $request->type,
            'color'   => $request->color,
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit(Category $category)
    {
        abort_if($category->user_id !== Auth::id(), 403);
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        abort_if($category->user_id !== Auth::id(), 403);

        $request->validate([
            'name'  => 'required|string|max:50',
            'type'  => 'required|in:income,expense',
            'color' => 'required|string',
        ]);

        $category->update($request->only('name', 'type', 'color'));

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil diupdate!');
    }

    public function destroy(Category $category)
    {
        abort_if($category->user_id !== Auth::id(), 403);
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }
}