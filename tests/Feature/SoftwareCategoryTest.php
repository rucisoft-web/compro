<?php

namespace Tests\Feature;

use App\Models\SoftwareCategory;
use App\Models\Solution;
use App\Models\User;
use Database\Seeders\ContentSeeder;
use Database\Seeders\SoftwareCatalogSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SoftwareCategoryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ContentSeeder::class);
    }

    public function test_every_subdomain_product_is_seeded_with_a_category(): void
    {
        $this->assertSame(20, Solution::count());
        $this->assertSame(0, Solution::whereNull('software_category_id')->count());
        $this->assertSame(7, SoftwareCategory::count());

        foreach (['akredia', 'aset', 'blu', 'bsc', 'izinara', 'kinera', 'medika',
            'mufakat', 'notaria', 'pranata', 'promise', 'rasentra', 'rekana', 'setia', 'siaga', 'simpul'] as $sub) {
            $this->assertTrue(
                Solution::where('website_url', "https://{$sub}.rucisoft.id/")->exists(),
                "{$sub}.rucisoft.id is missing",
            );
        }
    }

    public function test_client_name_is_not_published(): void
    {
        $this->assertSame(3, Solution::where('slug', 'like', '%-hotel-konvensi')->whereNull('website_url')->count());
        $this->get('/')->assertOk()->assertDontSee('Pasundan')->assertDontSee('GPCH')->assertDontSee('gpch-');
        $this->get('/solusi/crm-hotel-konvensi')->assertOk()->assertDontSee('Pasundan')->assertDontSee('gpch');
    }

    public function test_seeder_removes_products_seeded_under_the_client_name(): void
    {
        Solution::where('slug', 'crm-hotel-konvensi')->update(['slug' => 'gpch-crm', 'name' => 'CRM GPCH']);

        $this->seed(SoftwareCatalogSeeder::class);

        $this->assertDatabaseMissing('solutions', ['slug' => 'gpch-crm']);
        $this->assertDatabaseHas('solutions', ['slug' => 'crm-hotel-konvensi', 'website_url' => null]);
    }

    public function test_pages_still_work_before_the_migration_runs(): void
    {
        Schema::table('solutions', fn ($t) => $t->dropConstrainedForeignId('software_category_id'));
        Schema::drop('software_categories');

        $this->get('/')->assertOk()->assertSee('Ruci BSC');
        $this->get('/solusi/ruci-bsc')->assertOk();
        $this->get('/artikel')->assertOk();
    }

    public function test_seeder_keeps_admin_edits_and_chosen_category(): void
    {
        $other = SoftwareCategory::firstWhere('slug', 'edisi-klien');
        Solution::where('slug', 'kinera')->update(['tagline' => 'Diubah di admin', 'software_category_id' => $other->id]);

        $this->seed(SoftwareCatalogSeeder::class);

        $kinera = Solution::firstWhere('slug', 'kinera');
        $this->assertSame('Diubah di admin', $kinera->tagline);
        $this->assertSame($other->id, $kinera->software_category_id);
        $this->assertSame(20, Solution::count());
    }

    public function test_home_groups_products_by_category(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('id="kategori-strategi-kinerja"', false)
            ->assertSee('id="kategori-edisi-klien"', false)
            ->assertSeeInOrder(['Strategi &amp; Kinerja', 'Ruci BSC BLU', 'Tata Kelola, Risiko &amp; Kepatuhan', 'Rekana'], false);
    }

    public function test_uncategorized_products_fall_into_lainnya(): void
    {
        Solution::where('slug', 'simpul')->update(['software_category_id' => null]);

        $this->get('/')->assertOk()->assertSee('id="kategori-lainnya"', false);
    }

    public function test_product_page_shows_category_and_related_products_first(): void
    {
        $this->get('/solusi/kinera')
            ->assertOk()
            ->assertSee('Strategi &amp; Kinerja', false)
            ->assertSeeInOrder(['Produk terkait dan lainnya', 'Ruci BSC'], false);
    }

    public function test_bundled_screenshot_wins_over_svg_placeholder(): void
    {
        $this->assertStringEndsWith('ruci-setia-cover.jpg', Solution::firstWhere('slug', 'ruci-setia')->imageUrl());
        $this->assertStringEndsWith('siaga-cover.svg', Solution::firstWhere('slug', 'siaga')->imageUrl());
    }

    public function test_admin_can_manage_categories(): void
    {
        $this->actingAs(User::factory()->create());

        $this->get('/admin/software-categories')->assertOk()->assertSee('Edisi Klien');
        $this->get('/admin/solutions')->assertOk()->assertSee('Proyek &amp; Layanan TI', false);
    }
}
