<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGalleryRequest;
use App\Http\Requests\UpdateGalleryRequest;
use App\Http\Resources\GalleryResource;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use App\Models\Gallery;
use App\Models\GalleryImage;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        $orderColumn = request('order_column', 'created_at');
        if (!in_array($orderColumn, ['id', 'name', 'created_at'])) {
            $orderColumn = 'created_at';
        }
        $orderDirection = request('order_direction', 'desc');
        if (!in_array($orderDirection, ['asc', 'desc'])) {
            $orderDirection = 'desc';
        }
        $gallerys = Gallery::when(request('search_id'), function ($query) {
            $query->where('id', request('search_id'));
        })
            ->when(request('search_title'), function ($query) {
                $query->where('name', 'like', '%' . request('search_title') . '%');
            })
            ->when(request('search_parent_id'), function ($query) {
                $query->where('parent_id', 'like', '%' . request('search_parent_id') . '%');
            })
            ->when(request('search_global'), function ($query) {
                $query->where(function ($q) {
                    $q->where('id', request('search_global'))
                        ->orWhere('name', 'like', '%' . request('search_global') . '%');
                });
            })
            ->orderBy($orderColumn, $orderDirection)
            ->paginate(10000);
        return GalleryResource::collection($gallerys);
    }

    public function store(StoreGalleryRequest $request)
    {
        $this->authorize('product-create');

        //dd($request);

        // Check if the gallery with the same name already exists
        $existingGallery = Gallery::where('name', $request->name)->first();
        if ($existingGallery) {
            return response()->json(['errors' => ['name' => ['Gallery with the same name already exists.']]], 409);
        }


        $validatedData = $request->validated();
        $validatedData['created_by'] = auth()->user()->id;

        if ($request->hasFile('main_image')) {

            $file = $request->file('main_image');

            $file_name = time() . '_' . $request->file('main_image')->getClientOriginalName();
            $file_path = 'uploads/' . $file_name;

            // Resize and optimize the image
            $image = Image::make($file)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->encode('jpg', 85); // Specify the desired encoding format and quality (80% in this example)

            // Store the optimized image
            Storage::disk('public')->put($file_path, $image);
            //$file_path = $request->file('main_image')->storeAs('uploads', $file_name, 'public');

            $validatedData['main_image_path'] = $file_path;
        }


        $gallery = Gallery::create($validatedData);



        if ($request->hasFile('gallery_gallery')) {

            $galleryGallery = collect($request->file('gallery_gallery'))->map(function ($file) {

                $file_name = time() . '_' . '_gallery_' . $file->getClientOriginalName();
                $file_path = 'uploads/' . $file_name;

                // Resize and optimize the image
                $image = Image::make($file)->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->encode('jpg', 85); // Specify the desired encoding format and quality (80% in this example)

                // Store the optimized image
                Storage::disk('public')->put($file_path, $image);


                return $file_path;
            })->toArray();


            foreach ($galleryGallery as $imagePath) {
                $gallery->galleryImages()->create([
                    'name' => $imagePath,
                ]);
            }
        }

        return new GalleryResource($gallery);
    }

    public function show(Gallery $gallery)
    {
        $this->authorize('product-edit');
        return new GalleryResource($gallery);
    }

    public function update(Gallery $gallery, UpdateGalleryRequest $request)
    {
        $this->authorize('product-edit');

        $validatedData = $request->validated();


        // Check if the gallery with the same name already exists (excluding the current gallery)
        $existingGallery = Gallery::where('name', $request->name)
            // ->where('brand', '=', $gallery->brand)
            ->where('id', '!=', $gallery->id)
            ->first();
        if ($existingGallery) {
            return response()->json(['errors' => ['name' => ['Gallery with the same name already exists.']]], 409);
        }

        if ($request->hasFile('main_image')) {

            $file = $request->file('main_image');

            $file_name = time() . '_' . $request->file('main_image')->getClientOriginalName();
            $file_path = 'uploads/' . $file_name;

            // Resize and optimize the image
            $image = Image::make($file)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })->encode('jpg', 85); // Specify the desired encoding format and quality (80% in this example)

            // Store the optimized image
            Storage::disk('public')->put($file_path, $image);
            //$file_path = $request->file('main_image')->storeAs('uploads', $file_name, 'public');

            $validatedData['main_image_path'] = $file_path;
        }

        if ($request->hasFile('document')) {

            $file_name = time() . '_' . $request->file('document')->getClientOriginalName();
            $file_path = $request->file('document')->storeAs('uploads', $file_name, 'public');
            $validatedData['document_path'] = $file_path;
        }





        $gallery->update($validatedData);




        if ($request->hasFile('gallery_gallery')) {

            $galleryGallery = collect($request->file('gallery_gallery'))->map(function ($file) {

                $file_name = time() . '_' . '_gallery_' . $file->getClientOriginalName();
                $file_path = 'uploads/' . $file_name;

                // Resize and optimize the image
                $image = Image::make($file)->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->encode('jpg', 85); // Specify the desired encoding format and quality (80% in this example)

                // Store the optimized image
                Storage::disk('public')->put($file_path, $image);


                return $file_path;
            })->toArray();


            foreach ($galleryGallery as $imagePath) {
                $gallery->galleryImages()->create([
                    'name' => $imagePath,
                ]);
            }
        }


        //dd("test");

        return new GalleryResource($gallery);
    }

    public function destroy(Gallery $gallery)
    {
        $this->authorize('product-delete');
        $gallery->delete();

        return response()->noContent();
    }

    public function getList()
    {
        return GalleryResource::collection(Gallery::all());
    }

    public function getGallerys()
    {
        $perPage = request('per_page', 12);
        $gallerys = Gallery::with('showroomBrand')->paginate($perPage);


        return response()->json($gallerys);
    }
}