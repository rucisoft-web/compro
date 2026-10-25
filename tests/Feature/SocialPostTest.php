<?php

namespace Tests\Feature;

use App\Models\SocialPost;
use App\Models\User;
use Database\Seeders\ContentSeeder;
use Database\Seeders\SocialPostSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SocialPostTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        $this->seed([ContentSeeder::class, SocialPostSeeder::class]);
    }

    public function test_seeder_creates_one_article_per_product_with_images(): void
    {
        $this->assertSame(6, SocialPost::count());

        $bsc = SocialPost::whereRelation('solution', 'slug', 'ruci-bsc')->first();
        $this->assertStringContainsString('MENGAPA INI TERJADI', $bsc->body);
        $this->assertSame('sosmed/ruci-bsc/1-infografis.jpg', $bsc->image);
        $this->assertCount(4, $bsc->gallery);
        Storage::disk('public')->assertExists($bsc->gallery);

        // Rerunning keeps edits on posts that are no longer drafts.
        $bsc->update(['status' => 'siap', 'body' => 'Sudah diedit']);
        $this->seed(SocialPostSeeder::class);
        $this->assertSame('Sudah diedit', $bsc->fresh()->body);
        $this->assertSame(6, SocialPost::count());
    }

    public function test_copied_text_has_tracked_link_and_hashtags(): void
    {
        $post = SocialPost::whereRelation('solution', 'slug', 'akredia')->first();
        $text = $post->fullText('linkedin');

        $this->assertStringContainsString('/solusi/akredia?utm_source=linkedin&utm_medium=social&utm_campaign=akredia-edukasi', $text);
        $this->assertStringEndsWith($post->hashtags, $text);

        $post->update(['funnel_stage' => 'pilot']);
        $this->assertStringContainsString('?langkah=pilot&utm_source=', $post->trackedLink());
        $this->assertStringEndsWith('#mulai', $post->trackedLink());

        $post->update(['link_url' => 'https://example.com/x']);
        $this->assertSame('https://example.com/x', $post->trackedLink());
    }

    public function test_marking_posted_sets_the_date(): void
    {
        $post = SocialPost::first();
        $post->update(['status' => 'terposting']);

        $this->assertNotNull($post->posted_at);
    }

    public function test_admin_pages_load(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/admin/social-posts')->assertOk()->assertSee('Mengapa strategi berhenti di dokumen rencana');
        $this->get('/admin/social-posts/create')->assertOk();
        $this->get('/admin/social-posts/'.SocialPost::first()->id.'/edit')->assertOk();
    }
}
