<?php

/**
* Block
*/
class Block
{
	public $deger;
	
	public function __construct($index, $zaman, $veri, $prevHash = null)
	{
		$this->index = $index;
		$this->zaman = $zaman;
		$this->veri = $veri;
		$this->prevHash = $prevHash;
		$this->hash = $this->hesaplamaHash();
		$this->deger = 0; 
	}

	public function hesaplamaHash()
	{
		return hash("sha256", $this->index.$this->prevHash.$this->zaman.((string)$this->veri).$this->deger);
	}
}

/**
* BlockChain 
*/
class BlockChain
{
	// Yeni blockchain
	public function __construct()
	{
		$this->chain = [$this->olusturBlock()];
		$this->derece = 1;
	}

	//Block oluşturucu
	private function olusturBlock()
	{
		return new Block(0,strtotime("2018-28-02"), "Olusturulan Block");
	}

	//Son block
	public function sonBlock()
	{
		return $this->chain[count($this->chain)-1];
	}

	//Zincire yeni block
	public function ekleme($block)
	{
		$block->prevHash = $this->sonBlock()->hash;
		$this->hepsi($block);
		array_push($this->chain, $block);

	}

	//Tüm zincirler
	public function hepsi($block)
	{
		while (substr($block->hash,0, $this->derece) !== str_repeat("0", $this->derece)) {
			$block->deger++;
			$block->hash = $block->hesaplamaHash();
		}

		echo "Blok Zinciri: ".$block->hash. "\n";;
	}

	//Blok Zinciri Kontrolü
	public function kontrol()
	{
		for ($i=1; $i < count($this->chain); $i++) { 
			$simdikiBlock = $this->chain[$i];
			$oncekiBlock = $this->chain[$i-1];
			if($simdikiBlock->hash != $simdikiBlock->hesaplamaHash()){
				return false;
			}
			if($simdikiBlock->prevHash != $oncekiBlock->hash){
				return false;
			}
		}		

		return true;
	}
}

// İki blok oluşturalım. 

$blockchain = new BlockChain();

echo "Birinci Block <br>";
$blockchain->ekleme(new Block(1, strtotime("now"), "derece:1"));

echo "<br><br> İkinci Block <br>";
$blockchain->ekleme(new Block(2, strtotime("now"), "derece:2"));

echo "<br><br>";
echo json_encode($blockchain, JSON_PRETTY_PRINT);