<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

/**
* @OA\Info(
*   description="Bisa Menambahkan gallery/postingan dan juga bisa menampilkannya",
*   version="0.0.1",
*   title="API Gallery Portfolio Fadhil (Get dan Post)",
*   termsOfService="http://swagger.io/terms/",
*   @OA\Contact(
*       email="fadhillatmojo@gmail.com"
*   ),
*   @OA\License(
*       name="Apache 2.0",
*       url="http://www.apache.org/licenses/LICENSE-2.0.html"
*   )
* )
*/
class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $data = array(
        //     'id' => "posts",
        //     'menu' => "Gallery",
        //     'galleries' => Post::where('picture', '!=', '')->whereNotNull('picture')->orderBy('created_at', 'desc')->paginate(30)
        // );
        // return view('gallery.index')->with($data);
        $response = Http::get('http://127.0.0.1:8000/api/gallery');
        $objectResponse = $response->body();
        $data = json_decode($objectResponse, true);
        return view('gallery.index')->with([
            'galleries' => $data['galleries']['data'],
            'links' => $data['galleries']['links']
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('gallery.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        // $this->validate($request, [
        //     'title' => 'required|max:255',
        //     'description' => 'required',
        //     'picture' => 'image|nullable|max:1999'
        // ]);
        // $this->validate($request, [
        //     'title' => 'required|max:255',
        //     'description' => 'required',
        //     'picture' => 'image|nullable|max:1999'
        //     ]);
        // dd('tet');
        try {
            // dd($request->file('picture')->getClientOriginalExtension());
            if ($request->hasFile('picture')) {
                // $filenameWithExt = $request->file('picture')->getClientOriginalName();
                // $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension = $request->file('picture')->getClientOriginalExtension();
                $basename = uniqid() . time();
                // $smallFilename = "small_{$basename}.{$extension}";
                // $mediumFilename = "medium_{$basename}.{$extension}";
                // $largeFilename = "large_{$basename}.{$extension}";
                $filenameSimpan = "{$basename}.{$extension}";
                $path = $request->file('picture')->storeAs('posts_image', $filenameSimpan);
                } else {
                $filenameSimpan = 'noimage.png';
                }

                // ini untuk mengattach dan juga sekaligus mengepost si gallerynya
            // jadi konseopnya file yang dari user dijadikan parameter picture sebagai key yang diinginkan si APInya
            $response = Http::attach(
                'picture', file_get_contents($request->picture), $filenameSimpan 
            )->post('http://127.0.0.1:8000/api/gallery-store', [
                'title' => $request->title,
                'description' => $request->description,
            ]);
            // dd($response->successful());
            // ini dijalankan ketika responsenya itu success, maka dia akan mereturn success berhasil menambahkan data
            if ($response->successful()) {
                return redirect()->route('gallery.index')->with('Success', 'Berhasil menambahkan data baru');
            }
               
        } catch (\Throwable $th) {
            dd($th);
        }
                    
            // dd($request->input());
            // $post = new Post;
            // $post->picture = $filenameSimpan;
            // $post->title = $request->input('title');
            // $post->description = $request->input('description');
            // $post->save();
            
            // return redirect('gallery')->with('success', 'Berhasil menambahkan data baru');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $gallery = Post::findOrFail($id);
        return view('gallery.edit', compact('gallery'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // $this->validate($request, [
        //     'title' => 'required|max:255',
        //     'description' => 'required',
        //     'picture' => 'image|nullable|max:1999'
        // ]);
        $post = Post::findOrFail($id);
        // check apakah image is uploaded
        if ($request->hasFile('picture')){
            // menghapus image yang lama
            $path = 'posts_image/'. $post->picture;
    
            if (Storage::exists($path)) {
                Storage::delete($path);
            }

            $filenameWithExt = $request->file('picture')->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('picture')->getClientOriginalExtension();
            $basename = uniqid() . time();
            $smallFilename = "small_{$basename}.{$extension}";
            $mediumFilename = "medium_{$basename}.{$extension}";
            $largeFilename = "large_{$basename}.{$extension}";
            $filenameSimpan = "{$basename}.{$extension}";
            $path = $request->file('picture')->storeAs('posts_image', $filenameSimpan);

            // ini path yang baru dimana nantinya path ini yang akan disimpan ke database dan juga image ini yang akan dimasukkan ke folder
            // $path = public_path('storage/posts_image/'.$filenameSimpan);
            // $photoResized = Image::make($request->file('picture'));
            // $photoResized->fit(100,100);
            // $photoResized->save($path);
            //ghjhbb
            

            // $post = new Post;
            // $post->picture = $filenameSimpan;
            // $post->title = $request->input('title');
            // $post->description = $request->input('description');
            // $post->save();
            //update post with new image
            $post->update([
                'title'         => $request->title,
                'description'   => $request->description,
                'picture'       => $filenameSimpan
            ]);
        } else {
            //update post with no image
            $post->update([
                'title'         => $request->title,
                'description'   => $request->description
            ]);
        }
        //redirect to dashboard
        return redirect()->route('gallery.edit', $post->id)->with(['message' => 'Data Berhasil Diubah!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $gallery = Post::findOrFail($id);
        $path = 'posts_image/'. $gallery->picture;

        if (Storage::exists($path)) {
            Storage::delete($path);
        }
        $gallery->delete();

        return redirect()->route('gallery.index')->with(['message' => 'Data Berhasil Dihapus!']);
    }

    // api endpoint untuk get gallery

    /**
     * @OA\Get(
     *      path="/api/gallery",
     *      tags={"Get Gallery"},
     *      summary="Menampilkan data Gallery",
     *      description="Menampilkan data Gallery",
     *      operationId="gallery",
     *      @OA\Response(
     *          response="default",
     *          description="Success Menampilkan Data"
     *      )
     * )
    */
    public function gallery()
    { 
        try{
            $posts = Post::where('picture', '!=', '')->whereNotNull('picture')->orderBy('created_at', 'desc')->paginate(30);
            return response()->json([
                'galleries' => $posts,
                'success' => true
            ], 200);
        } catch(Exception $e){
            return response()->json([
                'message' => $e
            ],404);
        }
    }

    // api endpoint untuk post gallery
    /**
     * @OA\Post(
     *     path="/api/gallery-store",
     *     summary="Add Gallery",
     *     tags={"Store Gallery"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="title",
     *                     type="string",
     *                     description="Judul gallery",
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     description="Deskripsi Gallery",
     *                 ),
     *                 @OA\Property(
     *                     property="picture",
     *                     type="string",
     *                     format="binary",
     *                     description="Image file Gallery",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success menambahkan data",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Error",
     *     )
     * )
     */
    public function addGallery(Request $request){
        try{
            // $this->validate($request, [
            //     'title' => 'required|max:255',
            //     'description' => 'required',
            //     'picture' => 'image|nullable|max:1999'
            // ]);
            if ($request->hasFile('picture')) {
                $extension = $request->file('picture')->getClientOriginalExtension();
                $basename = uniqid() . time();
                $filenameSimpan = "{$basename}.{$extension}";

                $path = $request->file('picture')->storeAs('posts_image', $filenameSimpan);
            } else {
            $filenameSimpan = 'noimage.png';
            }

            Post::create([
                'title' => $request->title,
                'description' => $request->description,
                'picture' => $filenameSimpan
            ]);
            return response()->json([
                'title' => $request->title,
                'description' => $request->description,
                'picture' => $filenameSimpan,
                'message' => 'Data Berhasil ditambahkan!',
            ], 200);
            
        } catch(Exception $e){
            return response()->json([
                'message' => $e
            ],404);
        }
    }
}