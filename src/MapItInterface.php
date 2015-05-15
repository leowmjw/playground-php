<?php

namespace EC;

/**
 * Description of MapItInterface
 *
 * @author leow
 */
interface MapItInterface {

    public function getMapItViewModel($output_json = false);

    public function getPARPolygons();

    public function getDUNPolygons();

    public function getDMPolygons();

    public function extractMapIt(\Geocoder\Result\ResultInterface $result);
}
