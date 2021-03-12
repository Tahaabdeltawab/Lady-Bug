<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Vet;

class VetApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_vet()
    {
        $vet = Vet::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/vets', $vet
        );

        $this->assertApiResponse($vet);
    }

    /**
     * @test
     */
    public function test_read_vet()
    {
        $vet = Vet::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/vets/'.$vet->id
        );

        $this->assertApiResponse($vet->toArray());
    }

    /**
     * @test
     */
    public function test_update_vet()
    {
        $vet = Vet::factory()->create();
        $editedVet = Vet::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/vets/'.$vet->id,
            $editedVet
        );

        $this->assertApiResponse($editedVet);
    }

    /**
     * @test
     */
    public function test_delete_vet()
    {
        $vet = Vet::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/vets/'.$vet->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/vets/'.$vet->id
        );

        $this->response->assertStatus(404);
    }
}
