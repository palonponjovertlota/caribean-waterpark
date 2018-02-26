<?php

namespace App\Http\Controllers\Root;

use App\{Category, Item};
use ImageUploader;
use Storage, File, URL;
use Carbon, Image, Notify;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ItemsController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        $items = Item::all();

        return view('root.items.index', ['items' => $items, 'categories' => $categories]);
    }

    public function create()
    {
        $categories = Category::all();

        return view('root.items.create', ['categories' => $categories]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'category'      => 'required|integer',
            'name'          => 'required|max:100|unique:items,name,NULL,id,deleted_at,NULL',
            'description'   => 'max:500',
            'price'         => 'required'
        ]);

        try {
            $item = new Item;

            $item->category_id  = $request->input('category');
            $item->name         = $request->input('name');
            $item->description  = $request->input('description');
            $item->price        = $request->input('price');
            $item->quantity     = $request->input('quantity');

            if ($item->save()) {
                Notify::success('Item created.', 'Success!');

                return redirect()->route('root.items.image', $item);
            }

            Notify::warning('Cannot create a item', 'Ooops?');
        } catch (Exception $e) {
            Notify::error($e->getMessage(), 'Ooops!');
        }

        return back();
    }

    public function edit(Item $item)
    {
        try {
            if ($item != null) {
                $categories = Category::all();

                return view('root.items.edit', ['categories' => $categories, 'item' => $item]);
            }

            Notify::warning('Cannot find this item', 'Ooops?');
        } catch(Exception $e) {
            Notify::error($e->getMessage(), 'Ooops!');
        }

        return back();
    }

    public function update(Request $request, Item $item)
    {
        $this->validate($request, [
            'category'      => 'required|integer',
            'name'          => "required|max:100|unique:items,name,{$item->id},id,deleted_at,NULL",
            'description'   => 'max:500',
            'price'         => 'required'
        ]);

        try {
            $item->category_id  = $request->input('category');
            $item->name         = $request->input('name');
            $item->description  = $request->input('description');
            $item->price        = $request->input('price');
            $item->quantity     = $request->input('quantity');

            if ($item->save()) {
                Notify::success('Item updated.', 'Success!');

                return redirect()->route('root.items.index');
            }

            Notify::warning('Cannot update this item', 'Ooops?');
        } catch (Exception $e) {
            Notify::error($e->getMessage(), 'Ooops!');
        }

        return back();
    }

    public function destroy(Item $item)
    {
        try {
            if ($item->delete()) {
                Notify::success('Item deleted.', 'Success!');

                return back();
            }

            Notify::warning('Cannot delete item', 'Ooops?');

        } catch (Exception $e) {
            Notify::error($e->getMessage(), 'Ooops!');
        }

        return back();
    }

    public function toggle(Item $item)
    {
        try {
            $item->active = $item->active ? false : true;

            if ($item->save()) {
                Notify::success('Item toggled.', 'Success!');

                return back();
            }

            Notify::warning('Cannot toggle item', 'Ooops?');

        } catch (Exception $e) {
            Notify::error($e->getMessage(), 'Ooops!');
        }

        return back();
    }

    public function selectImage(Item $item)
    {
        try {
            if ($item != null) {
                return view('root.items.image', ['item' => $item]);
            }

            Notify::warning('Cannot find item', 'Ooops?');
        } catch (Exception $e) {
            Notify::error($e->getMessage(), 'Ooops!');
        }

        return back();
    }

    public function uploadedImage(Request $request, Item $item)
    {
        try {
            $item_images = $item->images;

            $images = [];

            foreach($item_images as $item_image) {
                $thumbs_directory = $item_image->file_directory.'/thumbnails';

                if (File::exists($thumbs_directory.'/'.$item_image->file_name)) {
                    $file_path = $thumbs_directory.'/'.$item_image->file_name;

                    $images[] = [
                        'directory' => URL::to($thumbs_directory),
                        'name'      => File::name($file_path).'.'.File::extension($file_path),
                        'size'      => File::size($file_path)
                    ];
                }
            }

            return response()->json(['images' => $images]);
        } catch(Exception $e) {
            return response()->json($e, 400);
        }

        return response()->json([]);
    }

    public function uploadImage(Request $request, Item $item)
    {
        try {
            if ($item->images()->count() < 5) {
                $upload = ImageUploader::upload($request->file('image'), "items/{$item->id}");
                
                $item->images()->create([
                    'count'             => $item->images->count() + 1,
                    'file_path'         => $upload['file_path'],
                    'file_directory'    => $upload['file_directory'],
                    'file_name'         => $upload['file_name']
                ]);
            }

            return response()->json($upload);
        } catch(Exception $e) {
            return response()->json($e, 400);
        }

        return response()->json('File not uploaded.');
    }

    public function destroyImage(Request $request, Item $item)
    {
        try {
            $file_name = $request->input('file_name');

            $item_image =   $item->images->filter(function($image) use ($file_name) {
                                return $image->file_name == $file_name;
                            })->first();

            if (File::exists($item_image->file_directory.'/'.$item_image->file_name)) {
                File::delete($item_image->file_directory.'/'.$item_image->file_name);
            }

            if (File::exists($item_image->file_directory.'/thumbnails/'.$item_image->file_name)) {
                File::delete($item_image->file_directory.'/thumbnails/'.$item_image->file_name);
            }

            if ($item_image->delete()) {
                return response()->json('File deleted.');
            }
        } catch(Exception $e) {
            return response()->json($e, 400);
        }

        return response()->json('File not deleted.');
    }

}
