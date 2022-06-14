<?php


namespace API\Interfaces;


interface ImgRepoInterface
{
    public function imagesByName($needle, $lookUp);
    public function getCoverImages();
    public function registerNew($data);
    public function getAll();
    public function  needleFilterAll($term, $lookUp);
    public function needleFilter($term);
    public function getByID($id);
    public function update($data);
    public function deleteByID($id);
}