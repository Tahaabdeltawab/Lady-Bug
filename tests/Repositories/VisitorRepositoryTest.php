<?php namespace Tests\Repositories;

use App\Models\Visitor;
use App\Repositories\VisitorRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class VisitorRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var VisitorRepository
     */
    protected $visitorRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->visitorRepo = \App::make(VisitorRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_visitor()
    {
        $visitor = Visitor::factory()->make()->toArray();

        $createdVisitor = $this->visitorRepo->create($visitor);

        $createdVisitor = $createdVisitor->toArray();
        $this->assertArrayHasKey('id', $createdVisitor);
        $this->assertNotNull($createdVisitor['id'], 'Created Visitor must have id specified');
        $this->assertNotNull(Visitor::find($createdVisitor['id']), 'Visitor with given id must be in DB');
        $this->assertModelData($visitor, $createdVisitor);
    }

    /**
     * @test read
     */
    public function test_read_visitor()
    {
        $visitor = Visitor::factory()->create();

        $dbVisitor = $this->visitorRepo->find($visitor->id);

        $dbVisitor = $dbVisitor->toArray();
        $this->assertModelData($visitor->toArray(), $dbVisitor);
    }

    /**
     * @test update
     */
    public function test_update_visitor()
    {
        $visitor = Visitor::factory()->create();
        $fakeVisitor = Visitor::factory()->make()->toArray();

        $updatedVisitor = $this->visitorRepo->update($fakeVisitor, $visitor->id);

        $this->assertModelData($fakeVisitor, $updatedVisitor->toArray());
        $dbVisitor = $this->visitorRepo->find($visitor->id);
        $this->assertModelData($fakeVisitor, $dbVisitor->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_visitor()
    {
        $visitor = Visitor::factory()->create();

        $resp = $this->visitorRepo->delete($visitor->id);

        $this->assertTrue($resp);
        $this->assertNull(Visitor::find($visitor->id), 'Visitor should not exist in DB');
    }
}
