<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Http\Controllers\Controller;

class ConcreteTestController extends Controller
{
    public function successAction()
    {
        return $this->sendResponse(['item' => 'test'], 'Fetched successfully');
    }

    public function errorAction()
    {
        return $this->sendError('Failed operation', ['field' => 'Required'], 422);
    }
}

class BaseControllerTest extends TestCase
{
    public function test_it_formats_success_and_error_responses(): void
    {
        $controller = new ConcreteTestController();

        $successRes = $controller->successAction();
        $this->assertEquals(200, $successRes->getStatusCode());
        $this->assertEquals('success', json_decode($successRes->getContent(), true)['status']);

        $errorRes = $controller->errorAction();
        $this->assertEquals(422, $errorRes->getStatusCode());
        $this->assertEquals('error', json_decode($errorRes->getContent(), true)['status']);
    }
}
