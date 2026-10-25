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

        // A post published on the website links to its article page.
        $this->assertStringContainsString('/artikel/'.$post->slug.'?utm_source=linkedin&utm_medium=social&utm_campaign=akredia-edukasi', $text);
        $this->assertStringEndsWith($post->hashtags, $text);

        $post->update(['funnel_stage' => 'pilot', 'published_on_site' => false]);
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

    public function test_published_articles_appear_on_the_website(): void
    {
        $bsc = SocialPost::whereRelation('solution', 'slug', 'ruci-bsc')->first();
        $this->assertSame('mengapa-strategi-yang-bagus-sering-berhenti-di-dokumen-rencana', $bsc->slug);

        $this->get('/')->assertSee(route('articles.index'));
        $this->get('/artikel')->assertOk()->assertSee($bsc->headline())->assertSee('Ruci MUFAKAT');
        $this->get('/artikel?produk=akredia')->assertOk()->assertDontSee($bsc->headline());

        $this->get('/artikel/'.$bsc->slug)
            ->assertOk()
            ->assertSee('Mengapa ini terjadi')
            ->assertSee('Bagaimana Ruci BSC membantu')
            ->assertSee('sosmed/ruci-bsc/2-peta-strategi.jpg', false)
            ->assertSee('langkah=demo', false);

        $this->get('/sitemap.xml')->assertSee('/artikel/'.$bsc->slug, false);

        $bsc->update(['published_on_site' => false]);
        $this->get('/artikel/'.$bsc->slug)->assertNotFound();
        $this->get('/artikel')->assertDontSee($bsc->headline());

        SocialPost::query()->update(['published_at' => now()->addDay()]);
        $this->get('/artikel')->assertSee('Belum ada artikel');
    }

    public function test_admin_pages_load(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/admin/social-posts')->assertOk()->assertSee('Mengapa strategi berhenti di dokumen rencana');
        $this->get('/admin/social-posts/create')->assertOk();
        $this->get('/admin/social-posts/'.SocialPost::first()->slug.'/edit')->assertOk();
    }
}
