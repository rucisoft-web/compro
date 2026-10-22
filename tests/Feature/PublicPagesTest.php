<?php

namespace Tests\Feature;

use App\Models\CatalogItem;
use App\Models\Setting;
use App\Models\Solution;
use App\Models\User;
use Database\Seeders\ContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ContentSeeder::class);
    }

    public function test_home_page_shows_seeded_content(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Strategi terlaksana. Kinerja terukur.')
            ->assertSee('RuciESG')
            ->assertSee('Workshop Praktis')
            ->assertSee('BUMN &amp; BUMD', false)
            ->assertSee('https://wa.me/6281199892939', false);
    }

    public function test_solution_page_renders_and_hides_inactive(): void
    {
        $solution = Solution::where('slug', 'ruciesg')->firstOrFail();

        $this->get('/solusi/ruciesg')->assertOk()->assertSee('Disclosure PSPK 1 &amp; PSPK 2', false);

        $solution->update(['is_active' => false]);
        $this->get('/solusi/ruciesg')->assertNotFound();
    }

    public function test_catalog_filters_by_type(): void
    {
        $this->get('/katalog?type=buku')
            ->assertOk()
            ->assertSee('Panduan Balanced Scorecard')
            ->assertDontSee('Workshop Penyusunan Strategy Map');

        $item = CatalogItem::where('type', 'workshop')->firstOrFail();
        $this->get('/katalog/'.$item->slug)->assertOk()->assertSee($item->title);
    }

    public function test_whatsapp_url_normalizes_local_number(): void
    {
        Setting::updateOrCreate(['key' => 'whatsapp_number'], ['value' => '0811-9989-2939']);

        $this->assertStringStartsWith('https://wa.me/6281199892939?text=', Setting::whatsappUrl('Halo'));
    }

    public function test_sitemap_lists_pages(): void
    {
        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml')
            ->assertSee(url('/solusi/ruci-bsc-professional'), false);
    }

    public function test_admin_requires_login(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');

        $this->actingAs(User::factory()->create())
            ->get('/admin')
            ->assertOk();
    }
}
