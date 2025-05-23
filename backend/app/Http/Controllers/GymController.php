<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\HttpResponseTrait;
use App\Http\Requests\GymRequests\AddGymRequest;
use App\Services\GymServices\GymServices;
use App\Http\Requests\GymRequests\GymManipulationAuthorizationRequest;

class GymController extends Controller
{
    use HttpResponseTrait;
    protected $gymServices;

    public function __construct(GymServices $gymServices)
    {
        $this->gymServices = $gymServices;
    }
    
    public function getGyms(Request $request)
    {
        try{
            $gyms = $this->gymServices->getGyms($request);
            return $this->respond(
                'true',
                'Gyms retrieved successfully',
                $gyms,
                200
            );
        }catch(\Exception $e){
            return $this->respond(
                'false',
                $e->getMessage(),
                null,
                500
            );
        }
    }
    
    public function addGym(AddGymRequest $request)
    {
        try{
            $data = $this->gymServices->addGym($request->all());
            return $this->respond(
                'true',
                'Gym added successfully',
                $data,
                201
            );
        }catch(\Exception $e){
            return $this->respond(
                'false',
                $e->getMessage(),
                null,
                500
            );
        }
    }

    public function deleteGym(GymManipulationAuthorizationRequest $request)
    {
        try{
            $data = $this->gymServices->deleteGym($request->only('id'));
            return $this->respond(
                'true',
                'Gym deleted successfully',
                $data,
                200
            );
        }catch(\Exception $e){
            return $this->respond(
                'false',
                $e->getMessage(),
                null,
                500
            );
        }
    }

    public function updateGym(GymManipulationAuthorizationRequest $authRequest,AddGymRequest $request)
    {
       try{
        $gym=$this->gymServices->updateGym($request->all());
        return $this->respond(
            'true',
            'Gym updated successfully',
            $gym,
            200
        );
       }catch(\Exception $e){
            return $this->respond(
                'false',
                $e->getMessage(),
                null,
                500
            );
        } 
    }
}
