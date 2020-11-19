<?php

namespace App\Traits;
use App\Models\Crop;
use App\Models\CropImage;

trait PackageTraits {

    private function getCropsInPackages (&$packages, $multiple = true) {
        $cropsIds = [];
        foreach($packages as $package) {
            if (in_array($package->crop, $cropsIds)) {
                continue;
            }
            array_push($cropsIds, $package->crop);
        }

        $crops = Crop::whereIn('id', $cropsIds)->get();
        $cropsImages = CropImage::whereIn('crop_id', $cropsIds)->get();
        $cropsSorted = [];

        $cropsImagesHashed = [];
        foreach($cropsImages as $cropsImage) {
            $cropsImage->url = asset("/storage/images/$cropsImage->image_uuid");
            $cropsImagesHashed[$cropsImage->crop_id][] = $cropsImage;
        }

        //Create a  hash map
        foreach ($crops as $crop) {
            $crop->images = array_key_exists($crop->id, $cropsImagesHashed)
                ? $cropsImagesHashed[$crop->id]
                : [];

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

}