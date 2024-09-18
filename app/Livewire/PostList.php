<?php

namespace App\Livewire;

use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class PostList extends Component
{
    use WithPagination, WithoutUrlPagination;

    public function render()
    {
        $posts =  Post::orderBy('id', 'DESC')->paginate(3);

        return view('livewire.post-list', compact('posts'));
    }

    public function deletePost(Post $post)
    {
        if ($post) {
            // Get the image path stored in the database
            $imagePath = $post->featured_image;

            // If the path doesn't already include 'uploads/', prepend it
            if (!str_contains($imagePath, 'uploads/')) {
                $imagePath = 'uploads/' . $imagePath;
            }

            // Debug the exact path Laravel is checking
            // dd(Storage::disk('public')->path($imagePath));

            // Check if the file exists in the storage/app/public/uploads folder
            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            } else {
                dd('File does not exist.');
            }

            // Delete the post after deleting the file
            $post->delete();

            if ($post) {
                session()->flash('success', 'Post has been Deleted Susscully!');
            } else {
                session()->flash('error', 'Unable to Delete the Post. Please try again!');
            }
        }
    }
}
