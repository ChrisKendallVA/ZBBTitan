<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stock extends CI_Controller 
{

	public function __construct() 
	{
		parent::__construct();
		$this->load->model("user_model");
		$this->load->model("stock_model");
		$this->load->model("team_model");
		$this->load->model("projects_model");

		if(!$this->user->loggedin) $this->template->error(lang("error_1"));

		// If the user does not have premium. 
		// -1 means they have unlimited premium
		if($this->settings->info->global_premium && 
			($this->user->info->premium_time != -1 && 
				$this->user->info->premium_time < time()) ) {
			$this->session->set_flashdata("globalmsg", lang("success_29"));
			redirect(site_url("funds/plans"));
		}
		if(!$this->common->has_permissions(array("admin", "project_admin",
		 "stock_manage", "stock_worker"), 
			$this->user)) 
		{
			$this->template->error(lang("error_71"));
		}
	}

	public function index() 
	{
		$this->template->loadData("activeLink", 
			array("stock" => array("general" => 1)));

		if($this->common->has_permissions(
			array("admin", "project_admin", "stock_manage"), $this->user
			)
		) {
			$projects = $this->projects_model->get_all_active_projects();
		} else {
			$projects = $this->projects_model
				->get_projects_user_all_no_pagination($this->user->info->ID, 
					"");
		}

		$this->template->loadContent("stock/index.php", array(
			"projects" => $projects
			)
		);
	}

	public function stock_page() 
	{
		$this->load->library("datatables");

		$this->datatables->set_default_order("stock_items.name", "asc");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"stock_items.name" => 0
				 ),
				 1 => array(
				 	"stock_items.price" => 0
				 ),
				 2 => array(
				 	"stock_items.cost" => 0
				 ),
				 3 => array(
				 	"projects.name" => 0
				 )
			)
		);

		$this->datatables->set_total_rows(
			$this->stock_model
				->get_stock_total()
		);

		$stock = $this->stock_model->get_stocks($this->datatables);
		

		foreach($stock->result() as $r) {

			if(isset($r->project_name)) {
				$project = $r->project_name;
			} else {
				$project = lang("ctn_1000");
			}
		
			$this->datatables->data[] = array(
				$r->name,
				$r->price,
				$r->cost,
				$project,
				'<a href="'.site_url("stock/edit_stock/" . $r->ID) .'" class="btn btn-warning btn-xs" title="'.lang("ctn_55").'" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("stock/delete_stock/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs" onclick="return confirm(\''.lang("ctn_508").'\')" title="'.lang("ctn_57").'" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-trash"></span></a>'
			);
		}

		echo json_encode($this->datatables->process());
	}

	public function add_stock() 
	{
		$projectid = intval($this->input->post("projectid"));
		$name = $this->common->nohtml($this->input->post("name"));
		$desc = $this->lib_filter->go($this->input->post("description"));
		$price = floatval($this->input->post("price"));
		$cost = floatval($this->input->post("cost"));

		if(empty($name)) {
			$this->template->error(lang("error_260"));
		}

		if($projectid > 0) {
			// Check project
			$project = $this->projects_model->get_project($projectid);
			if($project->num_rows() == 0) {
				$this->template->error(lang("error_72"));
			}
			$project = $project->row();
			

			$this->common->check_permissions(
				"Inventory", 
				array("admin", "project_admin", "stock_manage"), // User Roles
				array(),  // Team Roles
				$projectid
			);
		}

		$this->stock_model->add_stock(array(
			"name" => $name,
			"description" => $desc,
			"projectid" => $projectid,
			"price" => $price,
			"cost" => $cost
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_167"));
		redirect(site_url("stock"));
	}

	public function edit_stock($id) 
	{
		$id = intval($id);
		$stock = $this->stock_model->get_stock($id);
		if($stock->num_rows() == 0) {
			$this->template->error(lang("error_298"));
		}

		$stock = $stock->row();

		$this->template->loadData("activeLink", 
			array("stock" => array("general" => 1)));

		if($this->common->has_permissions(
			array("admin", "project_admin", "stock_manage"), $this->user
			)
		) {
			$projects = $this->projects_model->get_all_active_projects();
		} else {
			$projects = $this->projects_model
				->get_projects_user_all_no_pagination($this->user->info->ID, 
					"");
		}

		$this->template->loadContent("stock/edit_stock.php", array(
			"projects" => $projects,
			"stock" => $stock
			)
		);
	}

	public function edit_stock_pro($id) 
	{
		$id = intval($id);
		$stock = $this->stock_model->get_stock($id);
		if($stock->num_rows() == 0) {
			$this->template->error(lang("error_298"));
		}

		$stock = $stock->row();

		$projectid = intval($this->input->post("projectid"));
		$name = $this->common->nohtml($this->input->post("name"));
		$desc = $this->lib_filter->go($this->input->post("description"));
		$price = floatval($this->input->post("price"));
		$cost = floatval($this->input->post("cost"));

		if(empty($name)) {
			$this->template->error(lang("error_260"));
		}

		if($projectid > 0) {
			// Check project
			$project = $this->projects_model->get_project($projectid);
			if($project->num_rows() == 0) {
				$this->template->error(lang("error_72"));
			}
			$project = $project->row();
			

			$this->common->check_permissions(
				lang("ctn_1575"), 
				array("admin", "project_admin", "stock_manage"), // User Roles
				array(),  // Team Roles
				$projectid
			);
		}

		$this->stock_model->update_stock($id, array(
			"name" => $name,
			"description" => $desc,
			"projectid" => $projectid,
			"price" => $price,
			"cost" => $cost
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_168"));
		redirect(site_url("stock"));
	}

	public function delete_stock($id, $hash) 
	{
		if($hash != $this->security->get_csrf_hash()) {
			$this->template->error(lang("error_6"));
		}

		$id = intval($id);
		$stock = $this->stock_model->get_stock($id);
		if($stock->num_rows() == 0) {
			$this->template->error(lang("error_298"));
		}

		$stock = $stock->row();
		$this->stock_model->delete_stock($id);

		$this->session->set_flashdata("globalmsg", lang("success_169"));
		redirect(site_url("stock"));
	}

	public function inventory($projectid = 0) 
	{
		$this->template->loadData("activeLink", 
			array("stock" => array("inventory" => 1)));

		$projectid = intval($projectid);

		if($this->common->has_permissions(
			array("admin", "project_admin", "stock_manage"), $this->user
			)
		) {
			$projects = $this->projects_model->get_all_active_projects();
		} else {
			$projects = $this->projects_model
				->get_projects_user_all_no_pagination($this->user->info->ID, 
					"");
		}

		$this->template->loadContent("stock/inventory.php", array(
			"projects" => $projects,
			"projectid" => $projectid,
			"page" => "inventory"
			)
		);
	}

	public function inventory_all($projectid = 0) 
	{
		$this->template->loadData("activeLink", 
			array("stock" => array("all" => 1)));

		if(!$this->common->has_permissions(
			array("admin", "project_admin", "stock_manage"), $this->user
			)
		) {
			$this->template->error(lang("error_71"));
		}

		$projectid = intval($projectid);

		if($this->common->has_permissions(
			array("admin", "project_admin", "stock_manage"), $this->user
			)
		) {
			$projects = $this->projects_model->get_all_active_projects();
		} else {
			$projects = $this->projects_model
				->get_projects_user_all_no_pagination($this->user->info->ID, 
					"");
		}

		$this->template->loadContent("stock/inventory.php", array(
			"projects" => $projects,
			"projectid" => $projectid,
			"page" => "inventory_all"
			)
		);
	}

	public function inventory_page($projectid = 0, $page = "inventory") 
	{
		$projectid = intval($projectid);
		$this->load->library("datatables");

		$this->datatables->set_default_order("stock_inventory.ID", "DESC");

		// Set page ordering options that can be used
		$this->datatables->ordering(
			array(
				 0 => array(
				 	"stock_items.name" => 0
				 ),
				 1 => array(
				 	"projects.name" => 0
				 ),
				 2 => array(
				 	"stock_inventory.price" => 0
				 ),
				 3 => array(
				 	"stock_inventory.cost" => 0
				 ),
				 4 => array(
				 	"stock_inventory.quantity" => 0
				 ),
				 6 => array(
				 	"stock_inventory.last_updated" => 0
				 ),
			)
		);

		if($page == "inventory_all") {
			$this->datatables->set_total_rows(
				$this->stock_model
					->get_inventory_total($projectid)
			);

			$inventory = $this->stock_model->get_all_inventory($projectid, $this->datatables);
		} else {
			$this->datatables->set_total_rows(
				$this->stock_model
					->get_user_inventory_total($this->user->info->ID, $projectid)
			);

			$inventory = $this->stock_model->get_user_inventory($this->user->info->ID, $projectid, $this->datatables);
		}
		

		foreach($inventory->result() as $r) {

			if(isset($r->project_name)) {
				$project = $r->project_name;
			} else {
				$project = lang("ctn_1000");
			}
		
			$this->datatables->data[] = array(
				$r->name,
				$project,
				$r->price,
				$r->cost,
				$r->quantity,
				number_format(($r->price - $r->cost) * $r->quantity, 2),
				date($this->settings->info->date_format, $r->last_updated),
				'<a href="'.site_url("stock/edit_inventory/" . $r->ID) .'" class="btn btn-warning btn-xs" title="'.lang("ctn_55").'" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-cog"></span></a> <a href="'.site_url("stock/delete_inventory/" . $r->ID . "/" . $this->security->get_csrf_hash()).'" class="btn btn-danger btn-xs" onclick="return confirm(\''.lang("ctn_508").'\')" title="'.lang("ctn_57").'" data-toggle="tooltip" data-placement="bottom"><span class="glyphicon glyphicon-trash"></span></a>'
			);
		}

		echo json_encode($this->datatables->process());
	}

	public function edit_inventory($id) 
	{
		$id = intval($id);
		$inventory = $this->stock_model->get_inventory($id);
		if($inventory->num_rows() == 0) {
			$this->template->error(lang("error_299"));
		}
		$inventory = $inventory->row();

		// Check project
		$this->common->check_permissions(
			"Inventory", 
			array("admin", "project_admin", "stock_manage"), // User Roles
			array(),  // Team Roles
			$inventory->projectid
		);

		$this->template->loadData("activeLink", 
			array("stock" => array("inventory" => 1)));

		if($this->common->has_permissions(
			array("admin", "project_admin", "stock_manage"), $this->user
			)
		) {
			$projects = $this->projects_model->get_all_active_projects();
		} else {
			$projects = $this->projects_model
				->get_projects_user_all_no_pagination($this->user->info->ID, 
					"");
		}

		$items = $this->stock_model->get_project_items($inventory->projectid);

		$this->template->loadContent("stock/edit_inventory.php", array(
			"inventory" => $inventory,
			"projects" => $projects,
			"items" => $items
			)
		);
	}

	public function edit_inventory_pro($id) 
	{
		$id = intval($id);
		$inventory = $this->stock_model->get_inventory($id);
		if($inventory->num_rows() == 0) {
			$this->template->error(lang("error_299"));
		}
		$inventory = $inventory->row();

		$this->template->loadData("activeLink", 
			array("stock" => array("inventory" => 1)));

		$projectid = intval($this->input->post("projectid"));
		$itemid = intval($this->input->post("stockid"));
		$quantity = intval($this->input->post("quantity"));
		$price = floatval($this->input->post("price"));
		$cost = floatval($this->input->post("cost"));
		$note = $this->common->nohtml($this->input->post("note"));

		// Check project
		$this->common->check_permissions(
			lang("ctn_1575"), 
			array("admin", "project_admin", "stock_manage"), // User Roles
			array(),  // Team Roles
			$inventory->projectid
		);

		$project = $this->projects_model->get_project($projectid);
		if($project->num_rows() == 0) {
			$this->template->error(lang("error_72"));
		}
		$project = $project->row();
		

		$item = $this->stock_model->get_stock($itemid);
		if($item->num_rows() == 0) {
			$this->template->error(lang("error_298"));
		}
		$item = $item->row();

		$this->stock_model->update_inventory($id, array(
			"projectid" => $projectid,
			"itemid" => $itemid,
			"quantity" => $quantity,
			"price" => $price,
			"cost" => $cost,
			"note" => $note,
			"last_updated" => time()
			)
		);

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1586") . " <strong>" . $item->name . "</strong>" .lang("ctn_1587"). " <strong>" . $project->name . "</strong>",
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => $projectid,
			"url" => "inventory"
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_170"));
		redirect(site_url("stock/inventory"));
	}

	public function delete_inventory($id) 
	{
		$id = intval($id);
		$inventory = $this->stock_model->get_inventory($id);
		if($inventory->num_rows() == 0) {
			$this->template->error(lang("error_299"));
		}
		$inventory = $inventory->row();

		// Check project
		$this->common->check_permissions(
			lang("ctn_1575"), 
			array("admin", "project_admin", "stock_manage"), // User Roles
			array(),  // Team Roles
			$inventory->projectid
		);

		$this->stock_model->delete_inventory($id);

		$item = $this->stock_model->get_stock($inventory->itemid);
		if($item->num_rows() == 0) {
			$this->template->error(lang("error_298"));
		}
		$item = $item->row();

		$project = $this->projects_model->get_project($inventory->projectid);
		if($project->num_rows() == 0) {
			$this->template->error(lang("error_72"));
		}
		$project = $project->row();

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1588") ." <strong>" . $item->name . "</strong>".lang("ctn_1587")." <strong>" . $project->name . "</strong>",
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => $projectid,
			"url" => "inventory"
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_171"));
		redirect(site_url("stock/inventory"));
	}

	public function add_inventory() 
	{
		$this->template->loadData("activeLink", 
			array("stock" => array("inventory" => 1)));

		if($this->common->has_permissions(
			array("admin", "project_admin", "stock_manage"), $this->user
			)
		) {
			$projects = $this->projects_model->get_all_active_projects();
		} else {
			$projects = $this->projects_model
				->get_projects_user_all_no_pagination($this->user->info->ID, 
					"");
		}

		$items = $this->stock_model->get_project_items(0);

		$this->template->loadContent("stock/add_inventory.php", array(
			"projects" => $projects,
			"items" => $items
			)
		);
	}

	public function load_project_items($projectid) 
	{
		$projectid = intval($projectid);
		$project = $this->projects_model->get_project($projectid);
		if($project->num_rows() == 0) {
			$this->template->error(lang("error_72"));
		}
		$project = $project->row();
			

		$this->common->check_permissions(
			lang("ctn_1575"), 
			array("admin", "project_admin", "stock_manage"), // User Roles
			array(),  // Team Roles
			$projectid
		);

		$items = $this->stock_model->get_project_items($projectid);

		$this->template->loadAjax("stock/ajax_items.php", array(
			"items" => $items
			), 1
		);
	}

	public function load_item_data($itemid) 
	{
		$itemid = intval($itemid);

		$item = $this->stock_model->get_stock($itemid);
		if($item->num_rows() == 0) {
			$this->template->jsonError(lang("error_298"));
		}
		$item = $item->row();

		if($item->projectid > 0) {
			// Check project
			$this->common->check_permissions(
				lang("ctn_1575"), 
				array("admin", "project_admin", "stock_manage"), // User Roles
				array(),  // Team Roles
				$item->projectid
			);
		}

		echo json_encode(array(
			"price" => $item->price,
			"cost" => $item->cost,
			"itemid" => $item->ID
			)
		);
		exit();
	}

	public function add_inventory_pro() 
	{
		$projectid = intval($this->input->post("projectid"));
		$itemid = intval($this->input->post("stockid"));
		$quantity = intval($this->input->post("quantity"));
		$price = floatval($this->input->post("price"));
		$cost = floatval($this->input->post("cost"));
		$note = $this->common->nohtml($this->input->post("note"));
		$replace = intval($this->input->post("replace"));

		// Check project
		$this->common->check_permissions(
			lang("ctn_1575"), 
			array("admin", "project_admin", "stock_manage"), // User Roles
			array(),  // Team Roles
			$projectid
		);

		$project = $this->projects_model->get_project($projectid);
		if($project->num_rows() == 0) {
			$this->template->error(lang("error_72"));
		}
		$project = $project->row();
		

		$item = $this->stock_model->get_stock($itemid);
		if($item->num_rows() == 0) {
			$this->template->error(lang("error_298"));
		}
		$item = $item->row();

		if($replace) {
			$this->stock_model->delete_inventory_project_item($projectid, $itemid);
		}

		$this->stock_model->add_inventory(array(
			"projectid" => $projectid,
			"itemid" => $itemid,
			"quantity" => $quantity,
			"price" => $price,
			"cost" => $cost,
			"note" => $note,
			"last_updated" => time()
			)
		);

		// Log
		$this->user_model->add_user_log(array(
			"userid" => $this->user->info->ID,
			"message" => lang("ctn_1589") . " <strong>" . $item->name . "</strong> ".lang("ctn_1587")." <strong>" . $project->name . "</strong>",
			"timestamp" => time(),
			"IP" => $_SERVER['REMOTE_ADDR'],
			"projectid" => $projectid,
			"url" => "inventory"
			)
		);

		$this->session->set_flashdata("globalmsg", lang("success_172"));
		redirect(site_url("stock/inventory"));
	}


}

?>