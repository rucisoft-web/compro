<?php

namespace Tests\Feature;

use App\Models\CatalogItem;
use App\Models\Setting;
use App\Models\Solution;
use App\Models\User;
use Database\Seeders\ContentSeeder;
use Database\Seeders\ProductSeeder;
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
            ->assertSee('Ruci Medika')
            ->assertSee('https://notaria.rucisoft.id/img/logo/ruci-notaria.svg', false)
            ->assertSee('Workshop Praktis')
            ->assertSee('BUMN &amp; BUMD', false)
            ->assertSee('https://wa.me/6281199892939', false);
    }

    public function test_solution_page_renders_and_hides_inactive(): void
    {
        $solution = Solution::where('slug', 'ruci-medika')->firstOrFail();

        $this->get('/solusi/ruci-medika')
            ->assertOk()
            ->assertSee('Integrasi BPJS P-Care &amp; SATUSEHAT', false)
            ->assertSee('https://medika.rucisoft.id/', false);

        $solution->update(['is_active' => false]);
        $this->get('/solusi/ruci-medika')->assertNotFound();
    }

    public function test_catalog_filters_by_type(): void
    {
        CatalogItem::create(['type' => 'buku', 'title' => 'Panduan BSC', 'slug' => 'panduan-bsc']);

        $this->get('/katalog?type=buku')
            ->assertOk()
            ->assertSee('Panduan BSC')
            ->assertDontSee('/katalog/software-ruci-notaria');

        $item = CatalogItem::where('slug', 'software-akredia')->firstOrFail();
        $this->get('/katalog/'.$item->slug)->assertOk()->assertSee('Akredia');
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
            ->assertSee(url('/solusi/ruci-bsc'), false);
    }

    public function test_admin_requires_login(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');

        $this->actingAs(User::factory()->create())
            ->get('/admin')
            ->assertOk();
    }

    public function test_product_seeder_replaces_old_products_and_keeps_manual_items(): void
    {
        $old = Solution::create(['name' => 'RuciESG', 'slug' => 'ruciesg', 'tagline' => 'Lama']);
        CatalogItem::create(['type' => 'software', 'title' => 'RuciESG', 'slug' => 'software-ruciesg', 'solution_id' => $old->id]);
        CatalogItem::create(['type' => 'workshop', 'title' => 'Workshop buatan admin', 'slug' => 'workshop-admin']);

        $this->seed(ProductSeeder::class);

        $this->assertDatabaseMissing('solutions', ['slug' => 'ruciesg']);
        $this->assertDatabaseMissing('catalog_items', ['slug' => 'software-ruciesg']);
        $this->assertDatabaseHas('catalog_items', ['slug' => 'workshop-admin']);
        $this->assertSame(6, Solution::count());
        $this->assertSame(6, CatalogItem::where('type', 'software')->count());
    }

    public function test_bundled_images_are_used_when_no_external_image(): void
    {
        $prima = Solution::where('slug', 'ruci-prima')->firstOrFail();

        $this->assertStringEndsWith('images/products/ruci-prima-cover.svg', $prima->imageUrl());
        $this->assertSame('https://prima.rucisoft.id/brand/ruci-prima-logo-simple.svg', $prima->logoUrl());
    }
}
