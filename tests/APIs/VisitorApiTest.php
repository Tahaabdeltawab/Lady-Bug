<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Visitor;

class VisitorApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_visitor()
    {
        $visitor = Visitor::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/visitors', $visitor
        );

        $this->assertApiResponse($visitor);
    }

    /**
     * @test
     */
    public function test_read_visitor()
    {
        $visitor = Visitor::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/visitors/'.$visitor->id
        );

        $this->assertApiResponse($visitor->toArray());
    }

    /**
     * @test
     */
    public function test_update_visitor()
    {
        $visitor = Visitor::factory()->create();
        $editedVisitor = Visitor::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/visitors/'.$visitor->id,
            $editedVisitor
        );

        $this->assertApiResponse($editedVisitor);
    }

    /**
     * @test
     */
    public function test_delete_visitor()
    {
        $visitor = Visitor::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/visitors/'.$visitor->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/visitors/'.$visitor->id
        );

        $this->response->assertStatus(404);
    }
}
