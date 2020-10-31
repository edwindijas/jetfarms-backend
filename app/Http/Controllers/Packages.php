<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Crop;

class Packages extends Controller
{
    const REGEX_VAL_NAME = '',
            REGEX_VAL_NUMBER = '';

    private function getCrop($name) {
        $crops = Crop::where('name', $name)->get();

        if (count($crops) === 0) {
            return $this->createCrop($name);
        }

        return $crops[0];
    }

    private function errorGenerate ($error, $message, $code) {
        return ["error" => $error, "message" => $message, "code" => $code];
    }

    private function validateCropName (String $name, Array &$errors) {
        if (!preg_match(self::REGEX_VAL_NAME, $name)) {
            $errors['crop'] = $this->errorGenerate("Invalid crop name");
            return;
        }
    }

    private function validateInterestRate (String $rate, Array $errors) {
        
    }

    private function createCrop ($name) {
        $crop = new Crop();
        $crop->name = $name;
        $crop->save();
        return $crop;
    }

    private function checkRequiredKeys(Array &$errors, Array &$data) {
        //if ()
        $required = ['crop', 'price', 'units', 'rate', 'label', 'state', 'openingDate', 'closingDate', 'closingDate'];
        //Check if content of the data is okay;
        //Push the errors to the error message
        foreach ($required as $key) {
            if (!array_key_exists($key, $data)) {
                $errors[$key] = ["Message" => "$key is required"];
            }
        }
    }

    private function packageFill (Package &$investment, Request &$request) {

        $errors = [];
        $data = json_decode($request->getContent(), true);
        
        //Check required keys
        $this->checkRequiredKeys($errors, $data);

        
        //Early Exists
        if (count(array_keys($errors)) > 0) {
            return $errors;
        }



        //Validate Crop name
        //if ()

        
        $crop = $this->getCrop($data['crop']);
        $investment->crop = $crop->id;
        $investment->price = $data['price'];
        $investment->units = $data['units'];
        $investment->rate = $data['rate'];
        $investment->label = $data['label'];

        if (array_key_exists('description', $data)) {
            $investment->description = $data['description'];
        }
        
        $investment->state = $data['state'];
        $investment->opening_date = $data['openingDate'];
        $investment->closing_date = $data['closingDate'];

        return $errors;
    }

    function add (Request $request) {
        
        $package = new Package();
        $errors = $this->packageFill($package, $request);

        if (count(array_keys($errors)) > 0) {
            return response()->json(
                [
                    'userMessage' => 'failed to complete task, check form and try again.',
                    'errors' => $errors
                ], 400
             );
        }
        $package->save();
        return response()->json(['package' => $package->crop]);
    }


    private function getCropsInPackages (&$packages, $multiple = true) {
        $cropsIds = [];
        foreach($packages as $package) {
            if (in_array($package->crop, $cropsIds)) {
                continue;
            }
            array_push($cropsIds, $package->crop);
        }

        $crops = Crop::whereIn('id', $cropsIds)->get();
        $cropsSorted = [];
        //Create a  hash map
        foreach ($crops as $crop) {
            $cropsSorted[$crop->id] = $crop;
        }

        return $cropsSorted;
    }

    private function addCropToPackage (&$packages, &$cropsHashed) {
        foreach ($packages as $key => $package) {
            $packages[$key]->crop = $cropsHashed[$package->crop];
        }
    }

    private function addCropInfoToPackages (&$packages) {
       $cropsHashed = $this->getCropsInPackages($packages);
       $this->addCropToPackage($packages, $cropsHashed);
    }

    function getNew () {
        $data = Package::all();
        $this->addCropInfoToPackages($data);
        return $data;
    }

    function getAll () {
        $data = Package::all();
        $this->addCropInfoToPackages($data);
        return response()->json($data);
    }

    function getById($id) {
        $item = Package::where('id', $id)->first();
        if ($item) {
            $item->crop = Crop::where('id', $item->crop)->first();
            return response()->json(
                ['item' => $item, 'state' => true]
            );
        }
        return response()->json( ["message" => 'Item not found'], 404);
    }
}
