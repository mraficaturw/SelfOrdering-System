<?php
class AyamGeprek {
    private $bagian_ayam;
    private $pakai_nasi;
    private $jenis_bumbu;
    private $digeprek;

    public function setBagianAyam($bagian) {
        $this->bagian_ayam = $bagian;
    }

    public function setPakaiNasi($pakai_nasi) {
        $this->pakai_nasi = $pakai_nasi;
    }

    public function setJenisBumbu($jenis_bumbu) {
        $this->jenis_bumbu = $jenis_bumbu;
    }

    public function setDigeprek($digeprek) {
        $this->digeprek = $digeprek;
    }

    // Getters
    public function getBagianAyam() { 
        return $this->bagian_ayam ?? 'Tidak ditentukan'; 
    }

    public function getPakaiNasi() { 
        return $this->pakai_nasi ? 'Ya' : 'Tidak'; 
    }

    public function getJenisBumbu() { 
        return $this->jenis_bumbu ?? 'Bumbu default'; 
    }

    public function getDigeprek() { 
        return $this->digeprek ? 'Ya' : 'Tidak'; 
    }
}