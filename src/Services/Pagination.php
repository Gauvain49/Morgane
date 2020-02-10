<?php

namespace App\Services;

use Doctrine\Common\Persistence\ObjectManager;

class Pagination
{
	private $entityClass;
	private $limit = 10;
	private $currentPage = 1;
	private $manager;

	public function __construct(ObjectManager $manager)
	{
		$this->manager = $manager;
	}

	public function getPages($total)
	{

		//Connaitre le total des enregistrements de la table
		/*if (is_null($query)) {
			$repo = $this->manager->getRepository($this->entityClass);
			$total = count($repo->findAll());
		} else {
			$repo = $this->manager->getRepository($this->entityClass);
			$total = count($repo->$query(null, null));
		}*/
		

		//Faire la division, l'arrondi et le renvoyer
		$pages = ceil($total / $this->limit);

		return $pages;
	}

	public function getData($query = null)
	{
		//Calcul de l'offset
		$offset = $this->getOffset();
		//Demander au repository de trouver les éléments
		$repo = $this->manager->getRepository($this->entityClass);
		$data = $repo->findBy([], [], $this->limit, $offset);

		//Renvoyer les élements en question
		return $data;
	}

	public function getOffset()
	{
		return $this->currentPage * $this->limit - $this->limit;
	}

	public function setPage($page)
	{
		$this->currentPage = $page;
		return $this;
	}

	public function getPage()
	{
		return $this->currentPage;
	}
	public function setLimit($limit)
	{
		$this->limit = $limit;

		return $this;
	}

	public function getLimit()
	{
		return $this->limit;
	}

	public function setEntityClass($entityClass)
	{
		$this->entityClass = $entityClass;

		return $this;
	}

	public function getEntityClass()
	{
		return $this->entityClass;
	}
}