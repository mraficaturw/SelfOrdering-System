<?php
require_once 'AyamGeprek.php';

class AyamGeprekBuilder {
    private $ayam_geprek;

    public function __construct() {
        $this->ayam_geprek = new AyamGeprek();
    }

    public function setBagianAyam($bagian) {
        $this->ayam_geprek->setBagianAyam($bagian);
        return $this;
    }

    public function setPakaiNasi($pakai_nasi) {
        $this->ayam_geprek->setPakaiNasi($pakai_nasi);
        return $this;
    }

    public function setJenisBumbu($jenis_bumbu) {
        $this->ayam_geprek->setJenisBumbu($jenis_bumbu);
        return $this;
    }

    public function setDigeprek($digeprek) {
        $this->ayam_geprek->setDigeprek($digeprek);
        return $this;
    }

    public function build() {
        return $this->ayam_geprek;
    }
}