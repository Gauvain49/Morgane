<?php
namespace App\Services;

class TreeStructure {

	public function treeStructure($parent, $niveau, $array, $space = false) {
		$html = array();
		$addSpace = '';
		foreach($array as $noeud) {
			if($parent == $noeud['parent']) {
				for ($i = 0; $i < $niveau; $i++) {
					$addSpace .= $space;
				}
				$key = $addSpace . ' ' . $noeud['nom'];
				$html[][$key] = $noeud['id'];
				$html[] = $this->treeStructure($noeud['id'], ($niveau + 1), $array, $space);
			}
		}
		$array = [];
		foreach ($html as $key => $value) {
			foreach ($value as $k => $v) {
				$array[$k] = $v;
			}
		}
		return $array;
	}
}