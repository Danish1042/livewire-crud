<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class PostForm extends Component
{
    use WithFileUploads;
    public $post = null;
    public $isView = false;

    #[Validate('required', message: 'Post title is required')]
    #[Validate('min: 3', message: 'Post title must be mininum 3 characters long')]
    #[Validate('max: 150', message: 'Post title must not be more then 150 characters long')]
    public $title;

    #[Validate('required', message: 'Post title is required')]
    #[Validate('min: 10', message: 'Post title must be mininum 10 characters long')]
    public $content;


    public $featuredImage;

    public function mount(Post $post)
    {
        $this->isView = request()->routeIs('posts.view');

        if ($post->id) {
            $this->post = $post;
            $this->title = $post->title;
            $this->content = $post->content;
        }
    }

    public function savePost()
    {

        $this->validate();

        // Define the validation rules
        $rules = [
            'featuredImage' => $this->post && $this->post->featured_image
                ? 'nullable|image|mimes:jpg,jpeg,webp,png,svg,bmp,gif|max:2048'
                : 'required|image|mimes:jpg,jpeg,webp,png,svg,bmp,gif|max:2048'
        ];

        // Custom error messages
        $messages = [
            'featuredImage.required' => 'Featured Image is required',
            'featuredImage.image' => 'Featured Image must be an image',
            'featuredImage.mimes' => 'Featured Image accept only jpg, jpeg, webp, png, svg, bmp, gif',
            'featuredImage.max' => 'Featured Image must not be larger than 2MB',
        ];

        // Validate the request
        $this->validate($rules, $messages);
        // dd($this->post);

        $imagePath = null;

        if ($this->featuredImage) {
            $imageName = time() . '.' . $this->featuredImage->extension();
            // $imagePath = $this->featuredImage->storeAs('public/uploads', $imageName);
            $imagePath = $this->featuredImage->storeAs('uploads', $imageName, 'public');
        }

        if ($this->post) {
            $this->post->title = $this->title;
            $this->post->content = $this->content;

            if ($imagePath) {
                $this->post->featured_image = $imagePath;
            }
            // Update functionality
            $updatePost = $this->post->save();

            if ($updatePost) {
                session()->flash('success', 'Post has been Updated Susscully!');
            } else {
                session()->flash('error', 'Unable to update Post. Please try again!');
            }
        } else {
            $post = Post::create([
                'title' => $this->title,
                'content' => $this->content,
                'featured_image' => $imagePath,
            ]);

            if ($post) {
                session()->flash('success', 'Post has been Published Susscully!');
            } else {
                session()->flash('error', 'Unable to create Post. Please try again!');
            }
        }

        return $this->redirect('/posts', navigate: true);
    }

    public function render()
    {
        return view('livewire.post-form');
    }
}
