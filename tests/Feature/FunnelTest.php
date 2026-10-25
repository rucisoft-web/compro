<?php

namespace Tests\Feature;

use App\Models\CatalogItem;
use App\Models\Lead;
use App\Models\Opportunity;
use App\Models\Organization;
use App\Models\Solution;
use App\Models\User;
use Database\Seeders\ContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FunnelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(ContentSeeder::class);
    }

    private function submit(array $overrides = [])
    {
        return $this->from('/solusi/ruci-bsc')->post('/prospek', $overrides + [
            'kind' => 'webinar',
            'form_id' => 'test',
            'name' => 'Sari',
            'position' => 'Kepala Strategi',
            'email' => 'sari@contoh.co.id',
            'phone' => '081234567890',
            'organization_name' => 'PT Contoh Jaya',
            'solution_id' => Solution::where('slug', 'ruci-bsc')->value('id'),
            'consent' => '1',
        ]);
    }

    public function test_lead_creates_organization_and_opportunity(): void
    {
        $this->submit()->assertRedirect(route('leads.thanks'));

        $this->assertSame(1, Organization::count());
        $opportunity = Opportunity::firstOrFail();
        $this->assertSame('edukasi', $opportunity->stage);
        $this->assertNotNull($opportunity->next_action_at);
        $this->assertSame($opportunity->id, Lead::firstOrFail()->opportunity_id);
    }

    public function test_more_people_from_same_organization_stay_one_opportunity_and_advance_stage(): void
    {
        $this->submit();
        $this->submit(['kind' => 'demo', 'name' => 'Budi', 'email' => 'budi@contoh.co.id']);
        $this->submit(['kind' => 'asesmen', 'name' => 'Ani', 'email' => 'ani@contoh.co.id']);

        $this->assertSame(1, Organization::count());
        $this->assertSame(1, Opportunity::count());
        $this->assertSame(3, Lead::count());
        // Later, earlier-stage requests never move the opportunity backwards.
        $this->assertSame('klinik', Opportunity::first()->stage);
    }

    public function test_consent_is_required_and_errors_go_to_the_form_bag(): void
    {
        $this->submit(['consent' => null, 'form_id' => 'sol_demo'])
            ->assertSessionHasErrorsIn('sol_demo', ['consent']);

        $this->assertSame(0, Lead::count());
    }

    public function test_honeypot_drops_spam(): void
    {
        $this->submit(['website' => 'http://spam.example'])->assertRedirect(route('leads.thanks'));

        $this->assertSame(0, Lead::count());
    }

    public function test_event_registration_takes_product_from_event(): void
    {
        $workshop = CatalogItem::where('slug', 'workshop-strategy-map-kpi-rencana-kerja')->firstOrFail();

        $this->submit(['kind' => 'workshop', 'solution_id' => null, 'catalog_item_id' => $workshop->id]);

        $lead = Lead::firstOrFail();
        $this->assertSame($workshop->solution_id, $lead->solution_id);
        $this->assertSame('workshop', $lead->opportunity->stage);
    }

    public function test_academy_and_event_pages_render(): void
    {
        $this->get('/academy')
            ->assertOk()
            ->assertSee('Rucisoft Academy')
            ->assertSee('Workshop Menyusun Strategy Map, KPI, dan Rencana Kerja');

        $this->get('/katalog/workshop-strategy-map-kpi-rencana-kerja')
            ->assertOk()
            ->assertSee('Praktik menggunakan perangkat lunak Ruci BSC')
            ->assertSee('Lima sampai sepuluh KPI prioritas')
            ->assertSee('name="kind" value="workshop"', false);
    }

    public function test_product_page_shows_funnel(): void
    {
        $this->get('/solusi/ruci-bsc')
            ->assertOk()
            ->assertSee('Jalur menuju penerapan')
            ->assertSee('Satu direktorat atau departemen')
            ->assertSee('Lisensi atau langganan')
            ->assertSee('name="kind" value="proposal"', false);

        // Products without their own pilot scope fall back to the generic one.
        $this->get('/solusi/ruci-medika')->assertOk()->assertSee('Satu unit kerja, divisi, atau cabang');
    }

    public function test_thanks_page_offers_whatsapp_follow_up(): void
    {
        $this->followingRedirects()->submit(['kind' => 'demo'])
            ->assertOk()
            ->assertSee('Terima kasih, Sari')
            ->assertSee('https://wa.me/6281199892939', false);
    }

    public function test_crm_admin_pages_load(): void
    {
        $this->submit();
        $this->actingAs(User::factory()->create());

        $this->get('/admin')->assertOk()->assertSee('Funnel penjualan');
        $this->get('/admin/leads')->assertOk()->assertSee('PT Contoh Jaya');
        $this->get('/admin/opportunities')->assertOk();
        $this->get('/admin/opportunities/'.Opportunity::first()->id.'/edit')->assertOk();
        $this->get('/admin/organizations')->assertOk();
        $this->get('/admin/catalog-items/workshop-strategy-map-kpi-rencana-kerja/edit')->assertOk();
    }
}
