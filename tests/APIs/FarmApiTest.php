<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Farm;

class FarmApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_farm()
    {
        $farm = Farm::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/farms', $farm
        );

        $this->assertApiResponse($farm);
    }

    /**
     * @test
     */
    public function test_read_farm()
    {
        $farm = Farm::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/farms/'.$farm->id
        );

        $this->assertApiResponse($farm->toArray());
    }

    /**
     * @test
     */
    public function test_update_farm()
    {
        $farm = Farm::factory()->create();
        $editedFarm = Farm::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/farms/'.$farm->id,
            $editedFarm
        );

        $this->assertApiResponse($editedFarm);
    }

    /**
     * @test
     */
    public function test_delete_farm()
    {
        $farm = Farm::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/farms/'.$farm->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/farms/'.$farm->id
        );

        $this->response->assertStatus(404);
    }
}
