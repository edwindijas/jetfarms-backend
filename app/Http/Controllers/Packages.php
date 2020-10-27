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
        $required = ['crop', 'price', 'units', 'state', 'openingDate', 'closingDate', 'closingDate'];
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
        if (array_keys($errors) > 0) {
            return $errors;
        }

        //Validate Crop name
        if ()

        
        $crop = $this->getCrop($data['crop']);
        $investment->crop = $crop->id;
        $investment->price = $data['price'];
        $investment->units = $data['units'];

        if (array_key_exists('description', $data)) {
            $investment->description = $data['description'];
        }
        
        $investment->state = $data['state'];
        $investment->opening_date = $data['openingDate'];
        $investment->closing_date = $data['closingDate'];
    }

    function add (Request $request) { 
        $package = new Package();
        $this->packageFill($package, $request);
        $package->save();
        return json_encode($package);
    }

    function getAll () {
        return Package::all();
    }
}
