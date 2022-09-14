<?php

class Stock_Model extends CI_Model 
{

	public function add_stock($data) 
	{
		$this->db->insert("stock_items", $data);
	}

	public function get_stock($id) 
	{
		return $this->db->where("ID", $id)->get("stock_items");
	}

	public function update_stock($id, $data) 
	{
		$this->db->where("ID", $id)->update("stock_items", $data);
	}

	public function delete_stock($id) 
	{
		$this->db->where("ID", $id)->delete("stock_items");
	}

	public function get_stock_total() 
	{
		return $this->db->from("stock_items")->count_all_results();
	}

	public function get_stocks($datatable) 
	{
		$datatable->db_order();

		$datatable->db_search(array(
			"stock_items.name"
			)
		);

		return $this->db
			->select("stock_items.name, stock_items.ID, stock_items.projectid, stock_items.price,
				stock_items.cost,
				projects.name as project_name")
			->join("projects", "projects.ID = stock_items.projectid", "left outer")
			->limit($datatable->length, $datatable->start)
			->get("stock_items");
	}

	public function get_project_items($projectid) 
	{
		return $this->db->where("projectid", $projectid)->or_where("projectid", 0)->get("stock_items");
	}

	public function add_inventory($data) 
	{
		$this->db->insert("stock_inventory", $data);
	}

	public function get_inventory($id) 
	{
		return $this->db->where("ID", $id)->get("stock_inventory");
	}

	public function update_inventory($id, $data) 
	{
		$this->db->where("ID", $id)->update("stock_inventory", $data);
	}

	public function delete_inventory($id) 
	{
		$this->db->where("ID", $id)->delete("stock_inventory");
	}

	public function get_inventory_total($projectid) 
	{
		if($projectid > 0) {
			$this->db->where("stock_inventory.projectid", $projectid);
		}
		return $this->db->from("stock_inventory")->count_all_results();
	}

	public function get_all_inventory($projectid, $datatable) 
	{
		$datatable->db_order();

		$datatable->db_search(array(
			"stock_items.name"
			)
		);

		if($projectid > 0) {
			$this->db->where("stock_inventory.projectid", $projectid);
		}

		return $this->db
			->select("stock_items.name, stock_items.projectid,
				stock_inventory.ID, stock_inventory.cost, stock_inventory.price, stock_inventory.quantity,
				stock_inventory.last_updated,
				projects.name as project_name")
			->join("stock_items", "stock_items.ID = stock_inventory.itemid")
			->join("projects", "projects.ID = stock_inventory.projectid", "left outer")
			->limit($datatable->length, $datatable->start)
			->get("stock_inventory");
	}

	public function get_user_inventory_total($userid, $projectid) 
	{
		if($projectid > 0) {
			$this->db->where("stock_inventory.projectid", $projectid);
		}
		return $this->db->from("stock_inventory")
			->join("projects", "projects.ID = stock_inventory.projectid", "left outer")
			->join("project_members as pm2", "pm2.projectid = stock_inventory.projectid", "left outer")
			->group_start()
			->where("pm2.userid", $userid)
			->group_end()
			->count_all_results();
	}

	public function get_user_inventory($userid, $projectid, $datatable) 
	{
		$datatable->db_order();

		$datatable->db_search(array(
			"stock_items.name"
			)
		);

		if($projectid > 0) {
			$this->db->where("stock_inventory.projectid", $projectid);
		}

		return $this->db
			->select("stock_items.name, stock_items.projectid,
				stock_inventory.ID, stock_inventory.cost, stock_inventory.price, stock_inventory.quantity,
				stock_inventory.last_updated,
				projects.name as project_name")
			->join("stock_items", "stock_items.ID = stock_inventory.itemid")
			->join("projects", "projects.ID = stock_inventory.projectid", "left outer")
			->join("project_members as pm2", "pm2.projectid = stock_inventory.projectid", "left outer")
			->group_start()
			->where("pm2.userid", $userid)
			->group_end()
			->limit($datatable->length, $datatable->start)
			->get("stock_inventory");
	}

	public function delete_inventory_project_item($projectid, $itemid) 
	{
		$this->db->where("itemid", $itemid)->where("projectid", $projectid)->delete("stock_inventory");
	}

}

?>