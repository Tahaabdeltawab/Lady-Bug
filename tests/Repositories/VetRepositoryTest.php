<?php namespace Tests\Repositories;

use App\Models\Vet;
use App\Repositories\VetRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class VetRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var VetRepository
     */
    protected $vetRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->vetRepo = \App::make(VetRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_vet()
    {
        $vet = Vet::factory()->make()->toArray();

        $createdVet = $this->vetRepo->create($vet);

        $createdVet = $createdVet->toArray();
        $this->assertArrayHasKey('id', $createdVet);
        $this->assertNotNull($createdVet['id'], 'Created Vet must have id specified');
        $this->assertNotNull(Vet::find($createdVet['id']), 'Vet with given id must be in DB');
        $this->assertModelData($vet, $createdVet);
    }

    /**
     * @test read
     */
    public function test_read_vet()
    {
        $vet = Vet::factory()->create();

        $dbVet = $this->vetRepo->find($vet->id);

        $dbVet = $dbVet->toArray();
        $this->assertModelData($vet->toArray(), $dbVet);
    }

    /**
     * @test update
     */
    public function test_update_vet()
    {
        $vet = Vet::factory()->create();
        $fakeVet = Vet::factory()->make()->toArray();

        $updatedVet = $this->vetRepo->update($fakeVet, $vet->id);

        $this->assertModelData($fakeVet, $updatedVet->toArray());
        $dbVet = $this->vetRepo->find($vet->id);
        $this->assertModelData($fakeVet, $dbVet->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_vet()
    {
        $vet = Vet::factory()->create();

        $resp = $this->vetRepo->delete($vet->id);

        $this->assertTrue($resp);
        $this->assertNull(Vet::find($vet->id), 'Vet should not exist in DB');
    }
}
