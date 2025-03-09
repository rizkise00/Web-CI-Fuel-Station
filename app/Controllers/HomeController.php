<?php

namespace App\Controllers;

use App\Models\FuelStationModel;
use CodeIgniter\Controller;
use Config\Services;

class HomeController extends Controller
{
    protected $fuelStationModel;
    protected $session;
    protected $validation;

    public function __construct()
    {
        $this->fuelStationModel = new FuelStationModel();
        $this->session = session();
        $this->validation = Services::validation();
    }

    public function index()
    {
        $user = json_decode($this->session->get('user_data'), true);
        if (!$user) {
            return redirect()->to(base_url('/'));
        }

        $perPage = 10;
        $currentPage = $this->request->getVar('page') ?? 1;

        $data = [
            'fuel_station_list' => $this->fuelStationModel->getList($perPage, $currentPage),
            'pager' => $this->fuelStationModel->pager
        ];

        $content['content'] = view('home/index', $data);

		return view('layouts/master', $content);
    }

    public function add()
    {
        $user = json_decode($this->session->get('user_data'), true);
        if (!$user) {
            return redirect()->to(base_url('/'));
        }

        $content['content'] = view('home/add');

		return view('layouts/master', $content);
    }

    public function store()
    {
        $rules = [
            'name'           => 'required',
            'fuel_type'      => 'required',
            'fuel_price'     => 'required',
            'image'          => [
                'rules' => 'uploaded[image]|is_image[image]|max_size[image,2048]|mime_in[image,image/jpg,image/jpeg,image/png]',
                'label' => 'Image'
            ],
            'status'         => 'required',
            'address'        => 'required',
            'near_by_place'  => 'required',
            'latitude'       => 'required',
            'longitude'      => 'required'
        ];
        
        $input = $this->request->getPost();
        $image = $this->request->getFile('image');
        
        if (!$this->validation->setRules($rules)->run(array_merge($input, ['image' => $image->getName()]))) {
            $this->session->setFlashdata('validation_errors', $this->validation->getErrors());
            return redirect()->back()->withInput();
        } else {
            $image_upload_path = './uploads/images/';

            if (!is_dir($image_upload_path)) {
                mkdir($image_upload_path, 0755, true);
            }

            $imageFile = $this->request->getFile('image');
            $newName = uniqid('image_') . '.' . $imageFile->getExtension();
            $imageFile->move($image_upload_path, $newName);

            $input = $this->request->getPost();
            $input['image'] = $newName;

            $response = $this->fuelStationModel->store($input);

            if ($response['status'] === 200) {
                $this->session->setFlashdata('success', $response['message']);
                return redirect()->to(base_url('home'));
            } else {
                $this->session->setFlashdata('error', $response['message']);
                return redirect()->back()->withInput();
            }        
        }
    }

    public function edit($userId)
    {
        $user = json_decode($this->session->get('user_data'), true);
        if (!$user) {
            return redirect()->to(base_url('/'));
        }
        
        $response = $this->fuelStationModel->get($userId);

        if ($response['status'] === 200) {
            $content['content'] = view('home/edit', ['station' => $response['data']]);
            return view('layouts/master', $content);
        } else {
            $this->session->setFlashdata('error', $response['message']);
            return redirect()->back()->withInput();
        }
    }

    public function update($stationId)
    {
        $rules = [
            'name'           => 'required',
            'fuel_type'      => 'required',
            'fuel_price'     => 'required',
            'status'         => 'required',
            'address'        => 'required',
            'near_by_place'  => 'required',
            'latitude'       => 'required',
            'longitude'      => 'required'
        ];
        
        $input = $this->request->getPost();
        
        if (!$this->validation->setRules($rules)->run($input)) {
            $this->session->setFlashdata('validation_errors', $this->validation->getErrors());
            return redirect()->back()->withInput();
        } else {
            $image_upload_path = './uploads/images/';

            if (!is_dir($image_upload_path)) {
                mkdir($image_upload_path, 0755, true);
            }

            $newName = null;
            if ($_FILES['image']['name'] !== '') {
                $station = $this->fuelStationModel->get($stationId);
                
                $old_image_path = './uploads/images/' . $station['data']['image'];
                if (file_exists($old_image_path)) {
                    unlink($old_image_path);
                }

                $imageFile = $this->request->getFile('image');
                $newName = uniqid('image_') . '.' . $imageFile->getExtension();
                $imageFile->move($image_upload_path, $newName);
            }

            $input = $this->request->getPost();

            if ($newName) {
                $input['image'] = $newName;
            }

            $response = $this->fuelStationModel->put($stationId, $input);

            if ($response['status'] === 200) {
                $this->session->setFlashdata('success', $response['message']);
                return redirect()->to(base_url('home'));
            } else {
                $this->session->setFlashdata('error', $response['message']);
                return redirect()->back()->withInput();
            }        
        }
    }

    public function delete($stationId)
    {
        $response = $this->fuelStationModel->destroy($stationId);

        if ($response['status'] === 200) {
            $this->session->setFlashdata('success', $response['message']);
            return redirect()->to(base_url('home'));
        } else {
            $this->session->setFlashdata('error', $response['message']);
            return redirect()->back()->withInput();
        }
    }   

    public function fuelStationList()
    {
        $perPage = $this->request->getGet('perPage') ?? 10;
        $page = $this->request->getGet('page') ?? 1;

        $data = $this->fuelStationModel->getList($perPage, $page);

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Data retrieved successfully',
            'data' => $data,
            'pagination' => [
                'total' => $this->fuelStationModel->pager->getTotal(),
                'per_page' => (int)$perPage,
                'current_page' => (int)$page,
                'last_page' => $this->fuelStationModel->pager->getPageCount()
            ]
        ], 200);
    }
}