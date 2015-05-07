<?php

namespace EC;

/**
 * Description of MapItInterface
 *
 * @author leow
 */
interface MapItInterface {

    public function getPARPolygons();

    public function getDUNPolygons();

    public function getDMPolygons();

    public function getMapItPoint(\Geocoder\Result\ResultInterface $result);
}
