<?php namespace Tests\Repositories;

use App\Models\Farm;
use App\Repositories\FarmRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class FarmRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var FarmRepository
     */
    protected $farmRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->farmRepo = \App::make(FarmRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_farm()
    {
        $farm = Farm::factory()->make()->toArray();

        $createdFarm = $this->farmRepo->create($farm);

        $createdFarm = $createdFarm->toArray();
        $this->assertArrayHasKey('id', $createdFarm);
        $this->assertNotNull($createdFarm['id'], 'Created Farm must have id specified');
        $this->assertNotNull(Farm::find($createdFarm['id']), 'Farm with given id must be in DB');
        $this->assertModelData($farm, $createdFarm);
    }

    /**
     * @test read
     */
    public function test_read_farm()
    {
        $farm = Farm::factory()->create();

        $dbFarm = $this->farmRepo->find($farm->id);

        $dbFarm = $dbFarm->toArray();
        $this->assertModelData($farm->toArray(), $dbFarm);
    }

    /**
     * @test update
     */
    public function test_update_farm()
    {
        $farm = Farm::factory()->create();
        $fakeFarm = Farm::factory()->make()->toArray();

        $updatedFarm = $this->farmRepo->update($fakeFarm, $farm->id);

        $this->assertModelData($fakeFarm, $updatedFarm->toArray());
        $dbFarm = $this->farmRepo->find($farm->id);
        $this->assertModelData($fakeFarm, $dbFarm->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_farm()
    {
        $farm = Farm::factory()->create();

        $resp = $this->farmRepo->delete($farm->id);

        $this->assertTrue($resp);
        $this->assertNull(Farm::find($farm->id), 'Farm should not exist in DB');
    }
}
