<?php 

namespace App\Models;

use CodeIgniter\Model;

class FuelStationModel extends Model
{
    protected $table = 'fuel_stations';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'fuel_type', 'fuel_price', 'image', 'status', 'address', 'near_by_place', 'latitude', 'longitude', 'created_at', 'updated_at'];
    protected $useTimestamps = true;

    public function getList($perPage, $page)
    {
        return $this->orderBy('id', 'DESC')->paginate($perPage, 'default', $page);
    }

    public function store($data)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $this->insert($data);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return ['status' => 401, 'message' => 'Failed to create fuel station'];
        }

        return ['status' => 200, 'message' => 'Successfully created fuel station'];
    }

    public function get($stationId)
    {
        $data = $this->where('id', $stationId)->first();

        if (!$data) {
            return ['status' => 401, 'message' => 'Failed to get fuel station', 'data' => $data];
        }

        return ['status' => 200, 'message' => 'Successfully get fuel station', 'data' => $data];
    }

    public function put($stationId, $data)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $data['updated_at'] = date('Y-m-d H:i:s');
        
        $this->update($stationId, $data);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return ['status' => 401, 'message' => 'Failed to update fuel station'];
        }

        return ['status' => 200, 'message' => 'Successfully updated fuel station'];
    }

    public function destroy($stationId)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $this->where('id', $stationId)->delete();

        $db->transComplete();

        if ($db->transStatus() === false) {
            return ['status' => 401, 'message' => 'Failed to delete fuel station'];
        }

        return ['status' => 200, 'message' => 'Successfully deleted fuel station'];
    }
}